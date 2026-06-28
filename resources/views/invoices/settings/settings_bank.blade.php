@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Invoice Settings" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => '#'],
        ['label' => 'Bank Settings', 'url' => '#']
    ]" />

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Sidebar Navigation -->
        <div class="md:col-span-4 lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <ul class="flex flex-col py-2">
                    <li>
                        <a href="{{ route('setting/page') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-cog w-5 text-center"></i> General Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoice/settings/tax/page') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                            <i class="far fa-bookmark w-5 text-center"></i> Tax Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoice/settings/bank/page') }}" class="flex items-center gap-3 px-6 py-3 text-sm font-medium text-blue-600 bg-blue-50 border-r-4 border-blue-600 transition-colors">
                            <i class="fas fa-university w-5 text-center"></i> Bank Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-8 lg:col-span-9">
            <x-card title="Bank Settings">
                <div id="form-errors-container" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
                    <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
                </div>

                <form action="#" class="x-submit space-y-6" data-then="reload">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-center">
                        <label class="text-sm font-medium text-gray-700 md:text-right">Default Bank Account</label>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600">Check to enable Bank Account default</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-center">
                        <label class="text-sm font-medium text-gray-700 md:text-right">Account Holder Name</label>
                        <div class="md:col-span-2">
                            <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-center">
                        <label class="text-sm font-medium text-gray-700 md:text-right">Bank Name</label>
                        <div class="md:col-span-2">
                            <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-center">
                        <label class="text-sm font-medium text-gray-700 md:text-right">IFSC Code</label>
                        <div class="md:col-span-2">
                            <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 items-center">
                        <label class="text-sm font-medium text-gray-700 md:text-right">Account Number</label>
                        <div class="md:col-span-2">
                            <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">Save Changes</button>
                    </div>

                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection
