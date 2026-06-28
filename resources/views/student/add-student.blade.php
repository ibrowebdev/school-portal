@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Add Students" parent="Student" :parentRoute="route('students.create')" />

    <x-card title="Student Information">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="x-submit" data-then="reload">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-form.input name="first_name" label="First Name" required="true" placeholder="Enter First Name" />
                <x-form.input name="last_name" label="Last Name" required="true" placeholder="Enter Last Name" />
                
                <x-form.select name="gender" label="Gender" required="true" :options="['Female' => 'Female', 'Male' => 'Male', 'Others' => 'Others']" />
                
                <x-form.input name="date_of_birth" label="Date Of Birth" required="true" placeholder="DD-MM-YYYY" class="datetimepicker" />
                <x-form.input name="roll" label="Roll" placeholder="Enter Roll Number" />
                
                <x-form.select name="blood_group" label="Blood Group" required="true" :options="['A+' => 'A+', 'B+' => 'B+', 'O+' => 'O+']" />
                <x-form.select name="religion" label="Religion" required="true" :options="['Hindu' => 'Hindu', 'Christian' => 'Christian', 'Others' => 'Others']" />
                
                <x-form.input type="email" name="email" label="E-Mail" required="true" placeholder="Enter Email Address" />
                
                <x-form.select name="class" label="Class" required="true" :options="['12' => '12', '11' => '11', '10' => '10']" />
                <x-form.select name="section" label="Section" required="true" :options="['A' => 'A', 'B' => 'B', 'C' => 'C']" />
                
                <x-form.input name="admission_id" label="Admission ID" placeholder="Enter Admission ID" />
                <x-form.input name="phone_number" label="Phone" placeholder="Enter Phone Number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Student Photo (150px X 150px)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2 py-1 shadow-sm border border-gray-200">
                                    <span>Choose File</span>
                                    <input type="file" name="upload" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('upload')
                        <span class="text-sm text-red-500 mt-1 block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shadow-sm">
                    Submit
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
