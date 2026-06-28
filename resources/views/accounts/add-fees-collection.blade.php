@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Add Fees" :breadcrumbs="[
        ['label' => 'Accounts', 'url' => route('fees.index')],
        ['label' => 'Add Fees', 'url' => '#']
    ]" />

    <x-card>
        <div id="form-errors-container" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
            <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
        </div>
        
        <form action="{{ route('fees/collection/save') }}" method="POST" class="x-submit space-y-6" data-then="reload">
            @csrf
            
            <h5 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Fees Information</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Name <span class="text-red-500">*</span></label>
                    <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" id="student_name" name="student_name">
                        <option selected disabled>-- Select --</option>
                        @foreach($users as $key => $names)
                            <option value="{{ $names->name }}" data-student_id="{{ $names->user_id }}" {{ old('full_name') == $names->name ? "selected" :""}}>{{ $names->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student ID <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border-gray-300 bg-gray-50 text-gray-500 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="student_id" name="student_id" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" name="gender">
                        <option selected disabled>Select Gender</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fees Type <span class="text-red-500">*</span></label>
                    <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" id="fees_type" name="fees_type">
                        <option selected disabled>-- Select Type --</option>
                        @foreach($feesType as $key => $feesTypes)
                            <option value="{{ $feesTypes->fees_type }}"> {{ $feesTypes->fees_type }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fees Amount <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="fees_amount" name="fees_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="{{ old('fees_amount') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid Date <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm datetimepicker" id="paid_date" name="paid_date" placeholder="DD-MM-YYYY">
                </div>
                
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Submit</button>
            </div>
        </form>
    </x-card>
</div>
@section('script')
<script>
    // select auto id and email
    $('#student_name').on('change',function()
    {
        $('#student_id').val($(this).find(':selected').data('student_id'));
    });
</script>
@endsection
@endsection
