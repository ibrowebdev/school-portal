@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Invoices" parent="Dashboard" :parentRoute="route('home')" />

    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('invoice/cancelled/page') }}" class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors bg-blue-600 text-white shadow-sm">
            <i class="fa fa-list"></i>
        </a>
        <a href="{{ route('invoices.grid') }}" class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors bg-white text-gray-400 hover:text-gray-600 border border-gray-200">
            <i class="fa fa-th"></i>
        </a>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- User Filter -->
            <div class="relative group">
                <button class="w-full px-4 py-2.5 text-left border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white flex justify-between items-center text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2"><i class="fas fa-user-plus text-gray-400"></i> Select User</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
            </div>

            <!-- Date Filter -->
            <div class="relative group">
                <button class="w-full px-4 py-2.5 text-left border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white flex justify-between items-center text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2"><i class="fas fa-calendar text-gray-400"></i> Select Date</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
            </div>

            <!-- Status Filter -->
            <div class="relative group">
                <button class="w-full px-4 py-2.5 text-left border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white flex justify-between items-center text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2"><i class="fas fa-book-open text-gray-400"></i> Select Status</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
            </div>

            <!-- Category Filter -->
            <div class="relative group">
                <button class="w-full px-4 py-2.5 text-left border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white flex justify-between items-center text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2"><i class="fas fa-bookmark text-gray-400"></i> By Category</span>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
            </div>

            <!-- Generate Report Button -->
            <div>
                <a href="#" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2 text-sm font-medium">
                    <img src="{{ URL::to('assets/img/icons/invoices-icon5.png') }}" alt="" class="w-4 h-4">
                    Generate report
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="overflow-x-auto w-full md:w-auto pb-2 md:pb-0 hide-scrollbar">
                <ul class="flex items-center space-x-1 px-2 min-w-max">
                    <li><a href="{{ route('invoices.index') }}" class="px-4 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">All Invoice</a></li>
                    <li><a href="{{ route('invoice/paid/page') }}" class="px-4 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">Paid</a></li>
                    <li><a href="{{ route('invoice/overdue/page') }}" class="px-4 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">Overdue</a></li>
                    <li><a href="{{ route('invoice/draft/page') }}" class="px-4 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">Draft</a></li>
                    <li><a href="{{ route('invoice/recurring/page') }}" class="px-4 py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">Recurring</a></li>
                    <li><a href="{{ route('invoice/cancelled/page') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium whitespace-nowrap">Cancelled</a></li>
                </ul>
            </div>
            
            <div class="flex items-center gap-2 px-2 w-full md:w-auto justify-end">
                <a href="invoices-settings.html" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors border border-gray-200">
                    <i class="feather feather-settings"></i>
                </a>
                <a href="{{ route('invoices.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm font-medium shadow-sm whitespace-nowrap">
                    <i class="feather feather-plus-circle"></i> New Invoice
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                    <img src="{{ URL::to('assets/img/icons/invoices-icon1.svg') }}" alt="" class="w-6 h-6">
                </div>
                <h3 class="text-2xl font-bold text-gray-800">$8,78,797</h3>
            </div>
            <p class="text-sm text-gray-500 font-medium flex justify-between items-center">
                All Invoices <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">50</span>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <img src="{{ URL::to('assets/img/icons/invoices-icon2.svg') }}" alt="" class="w-6 h-6">
                </div>
                <h3 class="text-2xl font-bold text-gray-800">$4,5884</h3>
            </div>
            <p class="text-sm text-gray-500 font-medium flex justify-between items-center">
                Paid Invoices <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">60</span>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                    <img src="{{ URL::to('assets/img/icons/invoices-icon3.svg') }}" alt="" class="w-6 h-6">
                </div>
                <h3 class="text-2xl font-bold text-gray-800">$2,05,545</h3>
            </div>
            <p class="text-sm text-gray-500 font-medium flex justify-between items-center">
                Unpaid Invoices <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">70</span>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                    <img src="{{ URL::to('assets/img/icons/invoices-icon4.svg') }}" alt="" class="w-6 h-6">
                </div>
                <h3 class="text-2xl font-bold text-gray-800">$8,8,797</h3>
            </div>
            <p class="text-sm text-gray-500 font-medium flex justify-between items-center">
                Cancelled Invoices <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">80</span>
            </p>
        </div>
    </div>

    <!-- Data Table Card -->
    <x-card noPadding="true">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left datatable">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-4">Invoice ID</th>
                        <th class="px-4 py-4">Invoice to</th>
                        <th class="px-4 py-4">Amount</th>
                        <th class="px-4 py-4">Created on</th>
                        <th class="px-4 py-4">Cancelled on</th>
                        <th class="px-4 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <a href="view-invoice.html" class="text-blue-600 hover:text-blue-800 font-medium">IN093439#@09</a>
                            </label>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <a href="profile.html" class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image"> 
                                <span class="font-medium text-gray-800">StarCode Moore</span>
                            </a>
                        </td>
                        <td class="px-4 py-4 text-blue-600 font-bold whitespace-nowrap">$1,54,220</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">16 Mar 2022</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">23 Mar 2022</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="edit-invoice.html" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_paid" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors ml-2">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <a href="view-invoice.html" class="text-blue-600 hover:text-blue-800 font-medium">IN093439#@10</a>
                            </label>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <a href="profile.html" class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image"> 
                                <span class="font-medium text-gray-800">StarCode Moore</span>
                            </a>
                        </td>
                        <td class="px-4 py-4 text-blue-600 font-bold whitespace-nowrap">$1,222</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">14 Mar 2022</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">18 Mar 2022</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="edit-invoice.html" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_paid" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors ml-2">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <a href="view-invoice.html" class="text-blue-600 hover:text-blue-800 font-medium">IN093439#@11</a>
                            </label>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <a href="profile.html" class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image"> 
                                <span class="font-medium text-gray-800">StarCode Moore</span>
                            </a>
                        </td>
                        <td class="px-4 py-4 text-blue-600 font-bold whitespace-nowrap">$3,470</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">7 Mar 2022</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">10 Mar 2022</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="edit-invoice.html" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_paid" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors ml-2">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <a href="view-invoice.html" class="text-blue-600 hover:text-blue-800 font-medium">IN093439#@12</a>
                            </label>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <a href="profile.html" class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image"> 
                                <span class="font-medium text-gray-800">StarCode Moore</span>
                            </a>
                        </td>
                        <td class="px-4 py-4 text-blue-600 font-bold whitespace-nowrap">$8,265</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">24 Mar 2022</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">30 Mar 2022</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="edit-invoice.html" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_paid" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors ml-2">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <a href="view-invoice.html" class="text-blue-600 hover:text-blue-800 font-medium">IN093439#@13</a>
                            </label>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <a href="profile.html" class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image"> 
                                <span class="font-medium text-gray-800">StarCode Moore</span>
                            </a>
                        </td>
                        <td class="px-4 py-4 text-blue-600 font-bold whitespace-nowrap">$5,200</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">17 Mar 2022</td>
                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">20 Mar 2022</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="edit-invoice.html" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_paid" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors ml-2">
                                <i class="far fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="delete_paid" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Invoice Cancelled</h3>
                    <p class="text-gray-500 mb-6">Are you sure want to delete?</p>
                    <div class="flex gap-3 justify-center">
                        <a href="javascript:void(0);" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</a>
                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
