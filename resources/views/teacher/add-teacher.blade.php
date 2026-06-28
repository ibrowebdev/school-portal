@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Add Teachers" parent="Teachers" :parentRoute="route('teachers.index')" />

    <x-card title="Basic Details">
        <div id="form-errors-container" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200" style="display: none;">
            <ul id="form-errors-list" class="list-disc pl-5 mb-0 text-sm"></ul>
        </div>
        
        <form action="{{ route('teachers.store') }}" method="POST" class="x-submit" data-then="reload">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <select class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors @error('full_name') border-red-500 @enderror" id="full_name" name="full_name">
                        <option selected disabled>-- Select Name --</option>
                        @foreach($users as $key => $names)
                            <option value="{{ $names->name }}" data-teacher_id={{ $names->user_id }} {{ old('full_name') == $names->name ? "selected" : "" }}>{{ $names->name }}</option>
                        @endforeach
                    </select>
                    @error('full_name')
                        <span class="text-sm text-red-500 block mt-1"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                
                <x-form.input name="teacher_id" id="teacher_id" label="Teacher ID" required="true" :value="old('teacher_id')" readonly="true" />
                
                <x-form.select name="gender" label="Gender" required="true" :options="['Female' => 'Female', 'Male' => 'Male', 'Others' => 'Others']" />
                
                <x-form.input name="experience" label="Experience" required="true" :value="old('experience')" placeholder="Enter Experience" />
                
                <x-form.input name="qualification" label="Qualification" required="true" class="datetimepicker" :value="old('qualification')" placeholder="DD-MM-YYYY" />
                
                <x-form.input name="date_of_birth" label="Date Of Birth" required="true" class="datetimepicker" :value="old('date_of_birth')" placeholder="DD-MM-YYYY" />
            </div>

            <h5 class="text-lg font-bold text-gray-800 mb-6 border-b border-gray-100 pb-2">Address</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="address" label="Address" required="true" :value="old('address')" placeholder="Enter address" />
                </div>
                
                <x-form.input name="phone_number" label="Phone" :value="old('phone_number')" placeholder="Enter Phone Number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                
                <x-form.input name="city" label="City" required="true" :value="old('city')" placeholder="Enter City" />
                
                <x-form.input name="state" label="State" required="true" :value="old('state')" placeholder="Enter State" />
                
                <x-form.input name="zip_code" label="Zip Code" required="true" :value="old('zip_code')" placeholder="Enter Zip" />
                
                <x-form.input name="country" label="Country" required="true" :value="old('country')" placeholder="Enter Country" />
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shadow-sm">
                    Submit
                </button>
            </div>
        </form>
    </x-card>
</div>

@section('script')
<script>
    // select auto teacher id
    $('#full_name').on('change',function()
    {
        $('#teacher_id').val($(this).find(':selected').data('teacher_id'));
    });
</script>
@endsection
@endsection
