@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Invoice Grid" parent="Dashboard" :parentRoute="route('home')" />

    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('invoices.index') }}" class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors bg-white text-gray-400 hover:text-gray-600 border border-gray-200">
            <i class="fa fa-list"></i>
        </a>
        <a href="{{ route('invoices.grid') }}" class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors bg-blue-600 text-white shadow-sm">
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

    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-auto"></div>
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

    <!-- Invoice Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($invoiceList as $key => $value)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <a href="{{ url('invoice/edit/'.$value->invoice_id) }}" class="text-blue-600 hover:text-blue-800 font-bold">{{ $value->invoice_id }}</a>
                    <div class="relative dropdown dropdown-action">
                        <a href="#" class="w-8 h-8 rounded text-gray-500 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-colors dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-lg py-2 right-0 absolute z-50">
                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 flex items-center gap-2" href="edit-invoice.html">
                                <i class="far fa-edit w-4"></i> Edit
                            </a>
                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 flex items-center gap-2" href="{{ url('invoice/view/'.$value->invoice_id) }}">
                                <i class="far fa-eye w-4"></i> View Detail
                            </a>
                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center gap-2" href="javascript:void(0);">
                                <i class="far fa-trash-alt w-4"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 text-center border-b border-gray-100">
                    <a href="profile.html" class="inline-block group">
                        <img class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm mb-3 mx-auto group-hover:border-blue-100 transition-colors" src="{{ URL::to('/images/photo_defaults.jpg') }}" alt="User Image">
                        <h2 class="text-gray-800 font-bold group-hover:text-blue-600 transition-colors">{{ $value->customer_name }}</h2>
                    </a>
                </div>
                
                <div class="p-4 bg-gray-50/50">
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <span class="text-gray-500 flex items-center gap-1 mb-1"><i class="far fa-money-bill-alt text-gray-400"></i> Amount</span>
                            <h6 class="font-bold text-gray-800">${{ $value->total_amount }}</h6>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-500 flex items-center gap-1 mb-1 justify-end"><i class="far fa-calendar-alt text-gray-400"></i> Due Date</span>
                            <h6 class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($value->due_date)->format('d M Y') }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Paid</span>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="flex justify-center mt-8">
        <a href="#" class="px-6 py-2.5 bg-white border border-gray-200 text-blue-600 rounded-lg hover:bg-blue-50 hover:border-blue-100 transition flex items-center gap-2 font-medium shadow-sm">
            <span class="animate-spin w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full"></span> Load more
        </a>
    </div>
</div>
@endsection
