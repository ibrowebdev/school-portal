<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('upload-results');
    }

    public function rules(): array
    {
        return [
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'results' => ['required', 'array', 'min:1'],
            'results.*.student_id' => ['required', 'exists:users,id'],
            'results.*.ca_score' => ['required', 'numeric', 'min:0', 'max:40'],
            'results.*.exam_score' => ['required', 'numeric', 'min:0', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'results.*.ca_score.max' => 'CA score cannot exceed 40 marks.',
            'results.*.exam_score.max' => 'Exam score cannot exceed 60 marks.',
        ];
    }
}
