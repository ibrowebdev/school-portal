<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\ClassFee;
use App\Models\StudentPayment;

class RecordStudentPayment extends Component
{
    public $sessions = [];
    public $terms = [];
    
    public $selectedSession = null;
    public $selectedTerm = null;

    public $searchStudent = '';
    public $students = [];
    public $selectedStudent = null;

    public $totalBilled = 0;
    public $totalPaid = 0;
    public $outstanding = 0;
    public $billables = [];
    public $paymentHistory = [];

    // Payment Form
    public $payAmount = '';
    public $payMethod = 'Cash';
    public $payReference = '';
    public $payDate = '';

    public function mount()
    {
        $this->sessions = AcademicSession::all();
        $this->terms = Term::all();
        $this->payDate = now()->format('Y-m-d');
    }

    public function updatedSelectedSession() { $this->calculateFinancials(); }
    public function updatedSelectedTerm() { $this->calculateFinancials(); }
    public function updatedSearchStudent() { $this->searchStudents(); }

    public function searchStudents()
    {
        if (!empty($this->searchStudent)) {
            $this->students = User::where('type', User::STUDENT)
                ->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->searchStudent . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchStudent . '%')
                      ->orWhere('email', 'like', '%' . $this->searchStudent . '%');
                })
                ->take(10)
                ->get();
        } else {
            $this->students = [];
        }
    }

    public function selectStudent($id)
    {
        $this->selectedStudent = User::find($id);
        $this->searchStudent = '';
        $this->students = [];
        $this->calculateFinancials();
    }

    public function calculateFinancials()
    {
        if ($this->selectedStudent && $this->selectedSession && $this->selectedTerm) {
            $classId = $this->selectedStudent->studentProfile?->school_class_id;

            if ($classId) {
                // Get Class Fees
                $this->billables = ClassFee::with('feesType')
                    ->where('school_class_id', $classId)
                    ->where('academic_session_id', $this->selectedSession)
                    ->where('term_id', $this->selectedTerm)
                    ->get();
                $this->totalBilled = $this->billables->sum('amount');
            } else {
                $this->billables = [];
                $this->totalBilled = 0;
            }

            // Get Payments
            $this->paymentHistory = StudentPayment::where('student_id', $this->selectedStudent->id)
                ->where('academic_session_id', $this->selectedSession)
                ->where('term_id', $this->selectedTerm)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $this->totalPaid = $this->paymentHistory->sum('amount_paid');
            $this->outstanding = $this->totalBilled - $this->totalPaid;
        }
    }

    public function recordPayment()
    {
        $this->validate([
            'payAmount' => 'required|numeric|min:0.01',
            'payDate' => 'required|date',
            'payMethod' => 'required|string',
            'selectedStudent' => 'required',
            'selectedSession' => 'required',
            'selectedTerm' => 'required',
        ]);

        StudentPayment::create([
            'student_id' => $this->selectedStudent->id,
            'academic_session_id' => $this->selectedSession,
            'term_id' => $this->selectedTerm,
            'amount_paid' => $this->payAmount,
            'payment_date' => $this->payDate,
            'payment_method' => $this->payMethod,
            'reference_number' => $this->payReference,
            'recorded_by' => auth()->id(),
        ]);

        $this->payAmount = '';
        $this->payReference = '';
        $this->calculateFinancials();
        session()->flash('payment_success', 'Payment recorded successfully!');
    }

    public function render()
    {
        return view('livewire.record-student-payment');
    }
}
