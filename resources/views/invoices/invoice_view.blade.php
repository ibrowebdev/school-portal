@extends('layouts.master')
@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-12">
        <div class="flex flex-col sm:flex-row justify-between border-b border-gray-100 pb-8 mb-8">
            <div class="mb-6 sm:mb-0">
                <div class="w-32 mb-4">
                    <img src="{{ URL::to('assets/img/logo.png') }}" alt="logo" class="w-full h-auto">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Invoice</h2>
                    <p class="text-gray-500 font-medium">Invoice Number : {{ $invoiceView->invoice_id }}</p>
                </div>
            </div>
            <div class="text-left sm:text-right">
                <strong class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Invoice From</strong>
                <h6 class="text-lg font-bold text-gray-800 mb-1">Company Name</h6>
                <p class="text-gray-600 leading-relaxed text-sm">
                    {!! nl2br(($invoiceView->invoice_to)) !!}
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between mb-8 gap-8">
            <div class="w-full sm:w-1/2">
                <strong class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Billed to</strong>
                <p class="text-gray-800 leading-relaxed">
                    {!! nl2br(($invoiceView->invoice_from)) !!}
                </p>
            </div>
            <div class="w-full sm:w-1/2 text-left sm:text-right">
                <strong class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Payment Details</strong>
                <p class="text-gray-800 leading-relaxed mb-4">
                    Debit Card <br>
                    <span class="text-gray-500">XXXXXXXXXXXX-{{ $invoiceView->account_number }}</span> <br>
                    <span class="font-medium">{{  $invoiceView->bank_name }}</span>
                </p>
                <div class="bg-gray-50 p-4 rounded-lg inline-block text-left">
                    <p class="text-sm text-gray-600 mb-1">Recurring : <span class="font-medium text-gray-800">15 Months</span></p>
                    <p class="text-sm text-gray-600 mb-0">PO Number : <span class="font-medium text-gray-800">{{ $invoiceView->po_number }}</span></p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-lg p-6 mb-8 flex flex-wrap gap-6 justify-between items-center border border-blue-100">
            <div>
                <p class="text-sm text-blue-600 font-medium mb-1">Issue Date</p>
                <p class="font-bold text-blue-900">27 Jul 2022</p>
            </div>
            <div>
                <p class="text-sm text-blue-600 font-medium mb-1">Due Date</p>
                <p class="font-bold text-blue-900">27 Aug 2022</p>
            </div>
            <div>
                <p class="text-sm text-blue-600 font-medium mb-1">Due Amount</p>
                <p class="font-bold text-blue-900 text-xl">₹ 1,54,22</p>
            </div>
        </div>

        <div class="mb-8 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-200">
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm">Items</th>
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm">Category</th>
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm">Rate/Item</th>
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm">Quantity</th>
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm">Discount (%)</th>
                        <th class="py-3 px-4 font-bold text-gray-700 text-sm text-right">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoiceDetails as $key => $value)
                        <tr>
                            <td class="py-4 px-4 text-gray-800">{{ $value->items }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $value->category }}</td>
                            <td class="py-4 px-4 text-gray-600">${{ $value->amount }}</td>
                            <td class="py-4 px-4 text-gray-800 font-medium">{{ $value->quantity }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $value->discount }}%</td>
                            <td class="py-4 px-4 text-gray-800 font-bold text-right">${{ $value->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row justify-between gap-8 mb-12">
            <div class="w-full md:w-1/2 space-y-6 text-sm">
                <div>
                    <h6 class="font-bold text-gray-700 mb-2">Notes:</h6>
                    <p class="text-gray-500">Enter customer notes or any other details</p>
                </div>
                <div>
                    <h6 class="font-bold text-gray-700 mb-2">Terms and Conditions:</h6>
                    <p class="text-gray-500">Enter customer notes or any other details</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/3">
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <div class="space-y-3 mb-4 text-sm border-b border-gray-200 pb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Taxable</span>
                            <span class="font-medium text-gray-800">$6,660.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Additional Charges</span>
                            <span class="font-medium text-gray-800">$6,660.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Discount</span>
                            <span class="font-medium text-gray-800">$3,300.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Sub total</span>
                            <span class="font-medium text-gray-800">$3,300.00</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Total Amount</h4>
                        <span class="text-xl font-bold text-blue-600">${{ $invoiceView->total_amount }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-right border-t border-gray-100 pt-8 mt-8">
            <div class="inline-block text-center">
                <img src="{{ Storage::url($invoiceView->upload_sign) }}" alt="sign" class="h-16 object-contain mx-auto mb-2">
                <span class="block text-gray-600 font-medium border-t border-gray-300 pt-2 px-4">{{ $invoiceView->name_of_the_signatuaory }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
