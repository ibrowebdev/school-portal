<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\FeesType;
use App\Models\ClassFee;

class ManageClassFees extends Component
{
    public $classes = [];
    public $sessions = [];
    public $terms = [];
    public $feesTypes = [];

    public $selectedClass = null;
    public $selectedSession = null;
    public $selectedTerm = null;

    public $classFees = [];

    // New Fee Type state
    public $newFeeTypeName = '';

    // New Class Fee state
    public $addFeeTypeId = '';
    public $addAmount = '';

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->sessions = AcademicSession::all();
        $this->terms = Term::all();
        $this->loadFeesTypes();
    }

    public function loadFeesTypes()
    {
        $this->feesTypes = FeesType::all();
    }

    public function createFeeType()
    {
        $this->validate([
            'newFeeTypeName' => 'required|string|max:255|unique:fees_types,fees_type',
        ]);

        FeesType::create(['fees_type' => $this->newFeeTypeName]);
        $this->newFeeTypeName = '';
        $this->loadFeesTypes();
        session()->flash('type_message', 'Fee Type created.');
    }

    public function loadClassFees()
    {
        if ($this->selectedClass && $this->selectedSession && $this->selectedTerm) {
            $this->classFees = ClassFee::with('feesType')
                ->where('school_class_id', $this->selectedClass)
                ->where('academic_session_id', $this->selectedSession)
                ->where('term_id', $this->selectedTerm)
                ->get();
        } else {
            $this->classFees = [];
        }
    }

    public function updatedSelectedClass() { $this->loadClassFees(); }
    public function updatedSelectedSession() { $this->loadClassFees(); }
    public function updatedSelectedTerm() { $this->loadClassFees(); }

    public function addClassFee()
    {
        $this->validate([
            'selectedClass' => 'required',
            'selectedSession' => 'required',
            'selectedTerm' => 'required',
            'addFeeTypeId' => 'required',
            'addAmount' => 'required|numeric|min:0',
        ]);

        // Check for duplicates
        $exists = ClassFee::where('school_class_id', $this->selectedClass)
            ->where('academic_session_id', $this->selectedSession)
            ->where('term_id', $this->selectedTerm)
            ->where('fees_type_id', $this->addFeeTypeId)
            ->exists();

        if ($exists) {
            session()->flash('fee_error', 'This fee type is already added to this class for the selected term.');
            return;
        }

        ClassFee::create([
            'school_class_id' => $this->selectedClass,
            'academic_session_id' => $this->selectedSession,
            'term_id' => $this->selectedTerm,
            'fees_type_id' => $this->addFeeTypeId,
            'amount' => $this->addAmount,
        ]);

        $this->addFeeTypeId = '';
        $this->addAmount = '';
        $this->loadClassFees();
        session()->flash('fee_message', 'Class fee added successfully.');
    }

    public function removeClassFee($id)
    {
        ClassFee::find($id)?->delete();
        $this->loadClassFees();
        session()->flash('fee_message', 'Class fee removed.');
    }

    public function render()
    {
        return view('livewire.manage-class-fees');
    }
}
