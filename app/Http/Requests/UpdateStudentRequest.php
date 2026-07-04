<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-students');
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$userId],
            'gender' => ['required', 'string', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],

            // Profile fields
            'admission_id' => ['required', 'string', 'unique:student_profiles,admission_id,'.$userId.',user_id'],
            'roll_number' => ['nullable', 'string', 'max:50'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'class_section_id' => ['nullable', 'exists:class_sections,id'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'religion' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
