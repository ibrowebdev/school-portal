<?php

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Term;
use Livewire\Component;

class RegisterClassSubjects extends Component
{
    public $sessions = [];
    public $terms = [];
    public $classes = [];
    public $subjects = [];

    public $selectedSessionId;
    public $selectedTermId;
    public $selectedClassId;
    
    public $selectedSubjectIds = [];

    public function mount()
    {
        $this->sessions = AcademicSession::orderByDesc('id')->get();
        $this->terms = Term::orderBy('id')->get();
        $this->subjects = Subject::orderBy('name')->get();

        if (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin')) {
            $this->classes = SchoolClass::orderBy('name')->get();
        } else {
            $this->classes = auth()->user()->assignedClasses()->orderBy('name')->distinct()->get();
        }

        // Default selections
        $currentSession = AcademicSession::current()->first();
        if ($currentSession) {
            $this->selectedSessionId = $currentSession->id;
        }

        if (count($this->terms) > 0) {
            $this->selectedTermId = $this->terms->first()->id;
        }
    }

    public function updatedSelectedSessionId()
    {
        $this->loadRegisteredSubjects();
    }

    public function updatedSelectedTermId()
    {
        $this->loadRegisteredSubjects();
    }

    public function updatedSelectedClassId()
    {
        $this->loadRegisteredSubjects();
    }

    public function loadRegisteredSubjects()
    {
        if ($this->selectedSessionId && $this->selectedTermId && $this->selectedClassId) {
            $class = SchoolClass::find($this->selectedClassId);
            
            if ($class) {
                // Get the subjects specifically mapped to this session and term
                $mappedSubjectIds = $class->subjects()
                    ->wherePivot('academic_session_id', $this->selectedSessionId)
                    ->wherePivot('term_id', $this->selectedTermId)
                    ->pluck('subjects.id')
                    ->toArray();

                $this->selectedSubjectIds = $mappedSubjectIds;
            } else {
                $this->selectedSubjectIds = [];
            }
        } else {
            $this->selectedSubjectIds = [];
        }
    }

    public function saveRegistration()
    {
        $this->validate([
            'selectedSessionId' => 'required|exists:academic_sessions,id',
            'selectedTermId' => 'required|exists:terms,id',
            'selectedClassId' => 'required|exists:school_classes,id',
            'selectedSubjectIds' => 'array',
            'selectedSubjectIds.*' => 'exists:subjects,id',
        ]);

        $class = SchoolClass::find($this->selectedClassId);

        // Delete previous mappings for this session + term
        $class->subjects()
            ->wherePivot('academic_session_id', $this->selectedSessionId)
            ->wherePivot('term_id', $this->selectedTermId)
            ->detach();

        // Re-attach selected subjects with specific session and term
        $syncData = [];
        foreach ($this->selectedSubjectIds as $subjectId) {
            if ($subjectId) {
                $syncData[$subjectId] = [
                    'academic_session_id' => $this->selectedSessionId,
                    'term_id' => $this->selectedTermId,
                ];
            }
        }

        if (!empty($syncData)) {
            $class->subjects()->syncWithoutDetaching($syncData);
        }

        session()->flash('success', 'Subjects registered successfully for the selected term and session.');
    }

    public function render()
    {
        return view('livewire.register-class-subjects');
    }
}
