@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Edit Teachers" parent="Teachers" :parentRoute="route('teachers.index')" />

    <x-card title="Basic Details">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        
        <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="x-submit" data-then="reload">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <x-form.input name="full_name" label="Name" required="true" :value="$teacher->full_name" placeholder="Enter Name" />
                
                <x-form.select name="gender" label="Gender" required="true" :selected="$teacher->gender" :options="['Female' => 'Female', 'Male' => 'Male', 'Others' => 'Others']" />
                
                <x-form.input name="date_of_birth" label="Date Of Birth" required="true" class="datetimepicker" :value="$teacher->date_of_birth" placeholder="DD-MM-YYYY" />
                
                <x-form.input name="joining_date" label="Joining Date" required="true" :value="$teacher->join_date" readonly="true" />
                
                <x-form.input name="qualification" label="Qualification" required="true" class="datetimepicker" :value="$teacher->qualification" placeholder="Enter Joining Date" />
                
                <x-form.input name="experience" label="Experience" required="true" :value="$teacher->experience" placeholder="Enter Experience" />
            </div>

            <h5 class="text-lg font-bold text-gray-800 mb-6 border-b border-gray-100 pb-2">Address</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="address" label="Address" required="true" :value="$teacher->address" placeholder="Enter address" />
                </div>
                
                <x-form.input name="phone_number" label="Phone" required="true" :value="$teacher->phone_number" placeholder="Enter phone number" />
                
                <x-form.input name="city" label="City" required="true" :value="$teacher->city" placeholder="Enter City" />
                
                <x-form.input name="state" label="State" required="true" :value="$teacher->state" placeholder="Enter State" />
                
                <x-form.input name="zip_code" label="Zip Code" required="true" :value="$teacher->zip_code" placeholder="Enter Zip" />
                
                <x-form.input name="country" label="Country" required="true" :value="$teacher->country" placeholder="Enter Country" />
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
