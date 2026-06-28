@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Edit Students" parent="Student" :parentRoute="route('students.create')" />

    <x-card title="Student Information">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        <form action="{{ route('students.update', $studentEdit->id) }}" method="POST" enctype="multipart/form-data" class="x-submit" data-then="reload">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-form.input name="first_name" label="First Name" required="true" :value="$studentEdit->first_name" />
                <x-form.input name="last_name" label="Last Name" required="true" :value="$studentEdit->last_name" />
                
                <x-form.select name="gender" label="Gender" required="true" :selected="$studentEdit->gender" :options="['Female' => 'Female', 'Male' => 'Male', 'Others' => 'Others']" />
                
                <x-form.input name="date_of_birth" label="Date Of Birth" required="true" :value="$studentEdit->date_of_birth" class="datetimepicker" />
                <x-form.input name="roll" label="Roll" :value="$studentEdit->roll" />
                
                <x-form.select name="blood_group" label="Blood Group" required="true" :selected="$studentEdit->blood_group" :options="['A+' => 'A+', 'B+' => 'B+', 'O+' => 'O+']" />
                <x-form.select name="religion" label="Religion" required="true" :selected="$studentEdit->religion" :options="['Hindu' => 'Hindu', 'Christian' => 'Christian', 'Others' => 'Others']" />
                
                <x-form.input type="email" name="email" label="E-Mail" required="true" :value="$studentEdit->email" />
                
                <x-form.select name="class" label="Class" required="true" :selected="$studentEdit->class" :options="['12' => '12', '11' => '11', '10' => '10']" />
                <x-form.select name="section" label="Section" required="true" :selected="$studentEdit->section" :options="['A' => 'A', 'B' => 'B', 'C' => 'C']" />
                
                <x-form.input name="admission_id" label="Admission ID" :value="$studentEdit->admission_id" />
                <x-form.input name="phone_number" label="Phone" :value="$studentEdit->phone_number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Student Photo (150px X 150px)</label>
                    <div class="mt-1 flex items-center gap-6 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors bg-gray-50">
                        <div class="shrink-0">
                            <img class="w-20 h-20 rounded-full object-cover border border-gray-200" src="{{ asset($studentEdit->upload) }}" alt="Current Photo">
                        </div>
                        <div class="space-y-1">
                            <div class="flex text-sm text-gray-600">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-3 py-1.5 shadow-sm border border-gray-200">
                                    <span>Change Photo</span>
                                    <input type="file" name="upload" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <input type="hidden" name="image_hidden" value="{{ $studentEdit->upload }}">
                    </div>
                    @error('upload')
                        <span class="text-sm text-red-500 mt-1 block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shadow-sm">
                    Update
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
