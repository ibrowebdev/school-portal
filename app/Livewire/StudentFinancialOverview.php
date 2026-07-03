<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\ClassFee;
use App\Models\StudentPayment;
use App\Models\Setting;

class StudentFinancialOverview extends Component
{
    public $student;

    public $activeSession;
    public $activeTerm;

    public $totalBilled = 0;
    public $totalPaid = 0;
    public $outstanding = 0;
    public $billables = [];
    public $paymentHistory = [];

    public function mount($student)
    {
        $this->student = $student;
        
        // Try to get active session and term from Settings table
        $sessionSetting = Setting::where('type', 'current_session')->first();
        $termSetting = Setting::where('type', 'current_term')->first();
        
        // If not found in settings, fallback to the latest
        $this->activeSession = $sessionSetting ? AcademicSession::find($sessionSetting->description) : AcademicSession::latest()->first();
        $this->activeTerm = $termSetting ? Term::find($termSetting->description) : Term::latest()->first();

        $this->calculateFinancials();
    }

    public function calculateFinancials()
    {
        if ($this->student && $this->activeSession && $this->activeTerm) {
            $classId = $this->student->studentProfile?->school_class_id;

            if ($classId) {
                $this->billables = ClassFee::with('feesType')
                    ->where('school_class_id', $classId)
                    ->where('academic_session_id', $this->activeSession->id)
                    ->where('term_id', $this->activeTerm->id)
                    ->get();
                $this->totalBilled = $this->billables->sum('amount');
            }

            $this->paymentHistory = StudentPayment::where('student_id', $this->student->id)
                ->where('academic_session_id', $this->activeSession->id)
                ->where('term_id', $this->activeTerm->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $this->totalPaid = $this->paymentHistory->sum('amount_paid');
            $this->outstanding = $this->totalBilled - $this->totalPaid;
        }
    }

    public function render()
    {
        return view('livewire.student-financial-overview');
    }
}
