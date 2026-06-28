@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Edit Department" parent="Department" :parentRoute="route('departments.index')" />

    <x-card title="Department Details">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        
        <form action="{{ route('department/update') }}" method="POST" class="x-submit" data-then="reload">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-form.input name="department_id" label="Department ID" required="true" :value="$department->department_id" readonly="true" />
                
                <x-form.input name="department_name" label="Department Name" required="true" :value="$department->department_name" />
                
                <x-form.input name="head_of_department" label="Head of Department" required="true" :value="$department->head_of_department" />
                
                <x-form.input name="department_start_date" label="Department Start Date" required="true" class="datetimepicker" placeholder="DD-MM-YYYY" :value="$department->department_start_date" />
                
                <x-form.input name="no_of_students" label="No of Students" required="true" :value="$department->no_of_students" />
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
