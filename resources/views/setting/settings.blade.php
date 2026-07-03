@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Settings" :breadcrumbs="[
        ['label' => 'Settings', 'url' => route('setting/page')],
        ['label' => 'General Settings', 'url' => '#']
    ]" />

    <!-- Settings Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <ul class="flex whitespace-nowrap px-4 border-b border-gray-100">
            <li>
                <a class="block px-4 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600" href="settings.html">General Settings</a>
            </li>
            <li>
                <a class="block px-4 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition" href="others-settings.html">Others</a>
            </li>
        </ul>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Website Basic Details -->
        <div>
            <x-card title="Website Basic Details">
                <div id="form-errors-container" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
                    <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
                </div>
                <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="x-submit space-y-6" data-then="reload">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website Name <span class="text-red-500">*</span></label>
                        <input type="text" name="website_name" value="{{ $settings['website_name'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter Website Name" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            <label for="file_logo" class="cursor-pointer w-12 h-12 flex items-center justify-center bg-gray-50 border border-dashed border-gray-300 rounded-lg hover:bg-gray-100 transition text-gray-500">
                                <i class="fas fa-upload"></i>
                            </label>
                            <input type="file" accept="image/*" name="logo" id="file_logo" onchange="loadFile(event, 'preview_logo')" class="hidden">
                            <div class="text-xs text-gray-500">
                                <p>Recommended image size is <span class="font-medium text-gray-700">150px x 150px</span></p>
                            </div>
                        </div>
                        <div class="mt-4 relative inline-block">
                            <img id="preview_logo" src="{{ asset($settings['logo']) }}" alt="Logo" class="max-w-[150px] border border-gray-200 rounded-lg p-2 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                        <div class="flex items-center gap-4">
                            <label for="file_favicon" class="cursor-pointer w-12 h-12 flex items-center justify-center bg-gray-50 border border-dashed border-gray-300 rounded-lg hover:bg-gray-100 transition text-gray-500">
                                <i class="fas fa-upload"></i>
                            </label>
                            <input type="file" accept="image/png, image/x-icon" name="favicon" id="file_favicon" onchange="loadFile(event, 'preview_favicon')" class="hidden">
                            <div class="text-xs text-gray-500">
                                <p>Recommended image size is <span class="font-medium text-gray-700">16px x 16px or 32px x 32px</span></p>
                                <p class="mt-1">Accepted formats: only png and ico</p>
                            </div>
                        </div>
                        <div class="mt-4 relative inline-block">
                            <img id="preview_favicon" src="{{ asset($settings['favicon']) }}" alt="Favicon" class="max-w-[32px] border border-gray-200 rounded p-1 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <span class="text-sm font-medium text-gray-700">RTL</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="rtl" value="1" class="sr-only peer" {{ $settings['rtl'] === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-medium text-sm shadow-sm">Update</button>
                    </div>

                </form>
            </x-card>
        </div>

        <!-- Address Details -->
        <div>
            <x-card title="Address Details">
                <div id="form-errors-container-address" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
                    <ul id="form-errors-list-address" class="list-disc list-inside text-sm"></ul>
                </div>
                <form action="{{ route('setting.update') }}" method="POST" class="x-submit space-y-6" data-then="reload">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input type="text" name="address_line_1" value="{{ $settings['address_line_1'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter Address Line 1">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" name="address_line_2" value="{{ $settings['address_line_2'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter Address Line 2">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" value="{{ $settings['city'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter City">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                            <input type="text" name="state" value="{{ $settings['state'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter State/Province">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zip/Postal Code</label>
                            <input type="text" name="zip_code" value="{{ $settings['zip_code'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter Zip/Postal Code">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" value="{{ $settings['country'] }}" class="w-full p-2.5 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter Country">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-medium text-sm shadow-sm">Update Address</button>
                    </div>

                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function loadFile(event, id) {
        var output = document.getElementById(id);
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    }
</script>
@endsection