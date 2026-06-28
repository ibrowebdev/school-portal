@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Edit Subject" parent="Subject" :parentRoute="route('subjects.index')" />

    <x-card title="Subject Information">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        
        <form action="{{ route('subject/update') }}" method="POST" class="x-submit" data-then="reload">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-form.input name="subject_id" label="Subject ID" required="true" :value="$subjectEdit->subject_id" readonly="true" />
                
                <x-form.input name="subject_name" label="Subject Name" required="true" :value="$subjectEdit->subject_name" />
                
                <x-form.input name="class" label="Class" required="true" :value="$subjectEdit->class" />
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
