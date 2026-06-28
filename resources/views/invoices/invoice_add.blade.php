@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <ul class="flex items-center text-sm font-medium text-gray-500">
            <li>
                <a href="{{ route('invoices.index') }}" class="hover:text-blue-600 flex items-center gap-2 transition-colors">
                    <i class="fas fa-chevron-left"></i> Back to Invoice List
                </a>
            </li>
        </ul>
        <div class="flex items-center gap-2">
            <a href="#" data-bs-toggle="modal" data-bs-target="#invoices_preview" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2 text-sm font-medium shadow-sm">
                <i class="far fa-eye"></i> Preview
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#delete_invoices_details" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-sm">
                Delete Invoice
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#save_invocies_details" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">
                Save Draft
            </a>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6">
            <div id="form-errors-container" class="hidden mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-100">
                <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
            </div>

            <form action="{{ route('invoices.store') }}" class="x-submit space-y-8" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Top Section: Customer, Details, Settings -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Customer & PO -->
                    <div class="lg:col-span-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                            <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" id="customer_name" name="customer_name">
                                <option selected disabled>-- Select Customer --</option>
                                @foreach($users as $key => $names)
                                    <option value="{{ $names->name }}" {{ old('full_name') == $names->name ? "selected" :""}}>{{ $names->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Po Number</label>
                            <input class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('po_number') border-red-500 @enderror" type="text" id="po_number" name="po_number" placeholder="Enter Reference Number" value="{{ old('po_number') }}">
                        </div>
                    </div>

                    <!-- Invoice Details Box -->
                    <div class="lg:col-span-5">
                        <h4 class="text-base font-bold text-gray-800 mb-4">Invoice details</h4>
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Invoice No. <a href="#" class="text-blue-600 hover:text-blue-800 ml-1">IN000000#@00</a></span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Date</span>
                                    <input class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm datetimepicker" type="text" name="date" value="{{ date('d-m-Y') }}">
                                </div>
                                <div>
                                    <span class="block text-sm text-gray-500 mb-1">Due Date</span>
                                    <input class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm datetimepicker @error('due_date') border-red-500 @enderror" type="text" name="due_date" placeholder="Select" value="{{ old('due_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings (Tax, Recurring) -->
                    <div class="lg:col-span-3">
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-full">
                            <div class="space-y-3 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="enableTax" name="enable_tax" value="Enable tax" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Enable tax</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="chkYes" name="recurring_incoice" value="Recurring Invoice" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Recurring Invoice</span>
                                </label>
                            </div>
                            
                            <div id="show-invoices" class="hidden">
                                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-blue-200">
                                    <div>
                                        <select class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm select" id="by_month" name="by_month">
                                            <option selected disabled>By month</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" type="text" name="month" placeholder="Enter Months">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <strong class="block text-sm font-medium text-gray-700 mb-2">Invoice From</strong>
                        <textarea rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="invoice_from">StarCode Kh
#61, Preah Monivong Blvd., Penh, Cambodia.</textarea>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-700 mb-2">Invoice To</strong>
                        <textarea rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="invoice_to">StarCode Kh
#28, Mao Tse Tung Blvd., Penh, Cambodia.</textarea>
                    </div>
                </div>

                <!-- Item Details Table -->
                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Item Details</h4>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left add-table-items" id="invoice-add-table">
                            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3">Items</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3 w-24">Quantity</th>
                                    <th class="px-4 py-3 w-32">Price</th>
                                    <th class="px-4 py-3 w-32">Amount</th>
                                    <th class="px-4 py-3 w-24">Discount</th>
                                    <th class="px-4 py-3 w-24 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="add-row">
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('items.*') border-red-500 @enderror" name="items[]" value="{{ old('items.0') }}"></td>
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('category.*') border-red-500 @enderror" name="category[]" value="{{ old('category.0') }}"></td>
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('quantity.*') border-red-500 @enderror" name="quantity[]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="{{ old('quantity.0') }}"></td>
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm price @error('price.*') border-red-500 @enderror" name="price[]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="{{ old('price.0') }}"></td>
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm amount @error('amount.*') border-red-500 @enderror" name="amount[]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="{{ old('amount.0') }}"></td>
                                    <td class="p-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm discount @error('discount.*') border-red-500 @enderror" name="discount[]" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" value="{{ old('discount.0') }}"></td>
                                    <td class="p-2 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a class="add-btn text-blue-600 hover:text-blue-800 cursor-pointer p-1"><i class="fas fa-plus-circle"></i></a>
                                            <a class="copy-btn text-gray-500 hover:text-gray-700 cursor-pointer p-1"><i class="far fa-copy"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bottom Section: Additional Fields & Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-6 border-t border-gray-100">
                    
                    <!-- Additional Fields (Left) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div>
                            <h4 class="text-base font-bold text-gray-800 mb-3">More Fields</h4>
                            <div id="btn-add-bank-details" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-0">Payment Details</p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#bank_details" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Add Bank Details
                                </a>
                            </div>
                            <div id="btn-remove-bank-details" class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-100 hidden mt-3">
                                <p class="text-sm font-medium text-red-700 mb-0">Payment Details</p>
                                <a class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition text-sm font-medium flex items-center gap-2 cursor-pointer">
                                    <i class="far fa-trash-alt"></i> Remove Bank Details
                                </a>
                            </div>
                        </div>

                        <div id="bank-details" class="bg-gray-50 p-5 rounded-xl border border-gray-200 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="auto_account_holder_name" name="account_holder_name" placeholder="Add Name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank name</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="auto_bank_name" name="bank_name" placeholder="Add Bank name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="auto_ifsc_code" name="ifsc_code" placeholder="IFSC Code">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                    <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="auto_account_number" name="account_number" placeholder="Account Number">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Accordion for terms and notes -->
                        <div class="space-y-3" id="accordion">
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <p class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-0">
                                        <i class="fas fa-plus-circle text-blue-600"></i> Add Terms & Conditions
                                    </p>
                                </div>
                                <div id="collapseTwo" class="collapse bg-white p-4 border-t border-gray-200" data-bs-parent="#accordion">
                                    <textarea class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" rows="3" name="add_terms_and_conditions"></textarea>
                                </div>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    <p class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-0">
                                        <i class="fas fa-plus-circle text-blue-600"></i> Add Notes
                                    </p>
                                </div>
                                <div id="collapseThree" class="collapse bg-white p-4 border-t border-gray-200" data-bs-parent="#accordion">
                                    <textarea class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" rows="3" name="add_notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary & Sign (Right) -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h4 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Summary</h4>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Taxable Amount</span>
                                    <span class="font-medium text-gray-800">$21</span>
                                    <input type="hidden" name="taxable_amount" id="taxable_amount" value="21">
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600">Round Off</span>
                                        <input type="checkbox" name="round_off" id="status_1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="54">
                                    </div>
                                    <span class="font-medium text-gray-800">$54</span>
                                </div>
                            </div>

                            <div class="links-info-one space-y-2 mb-3"></div>
                            
                            <a class="add-links text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-4 cursor-pointer">
                                <i class="fas fa-plus-circle"></i> Additional Charges
                            </a>
                            
                            <div class="links-info-discount space-y-2 mb-3"></div>
                            
                            <div class="links-cont-discount mb-4 pb-4 border-b border-gray-200">
                                <a class="add-links-one text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-plus-circle"></i> Add more Discount
                                </a>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2">
                                <h4 class="text-base font-bold text-gray-800">Total Amount</h4>
                                <h4 class="text-xl font-bold text-blue-600" id="total_amount">$<span class="total_amount">00</span></h4>
                                <input type="hidden" id="total_amounts" name="total_amount">
                            </div>
                        </div>

                        <!-- Upload Sign -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Sign</label>
                                <input type="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" name="upload_sign" multiple>
                            </div>
                            <div>
                                <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name_of_the_signatuaory') border-red-500 @enderror" name="name_of_the_signatuaory" placeholder="Name of the Signatuaory">
                            </div>
                            <div class="flex justify-end pt-2">
                                <button class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium w-full sm:w-auto shadow-sm" type="submit">
                                    Save Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- Preview Modal -->
<div class="modal fade" id="invoices_preview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="bg-white rounded-xl overflow-hidden">
                    <div class="p-8">
                        <!-- Invoice Header -->
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <img src="{{ URL::to('assets/img/logo.png') }}" alt="logo" class="h-10">
                            </div>
                            <div class="text-right">
                                <h2 class="text-3xl font-bold text-blue-600 mb-1">Invoice</h2>
                                <p class="text-gray-500 text-sm">Invoice Number : In983248782</p>
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-8 relative overflow-hidden">
                            <img src="{{ URL::to('assets/img/invoice-circle1.png') }}" alt="" class="absolute top-0 right-0 opacity-10">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                                <div>
                                    <strong class="text-gray-500 text-xs uppercase tracking-wider mb-2 block">Billed to</strong>
                                    <h6 class="text-gray-800 font-bold mb-2">Customer Name</h6>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        9087484288 <br>
                                        Address line 1, <br>
                                        Address line 2 <br>
                                        Zip code ,City - Country
                                    </p>
                                </div>
                                <div>
                                    <strong class="text-gray-500 text-xs uppercase tracking-wider mb-2 block">Invoice From</strong>
                                    <h6 class="text-gray-800 font-bold mb-2">Company Name</h6>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        9087484288 <br>
                                        Address line 1, <br>
                                        Address line 2 <br>
                                        Zip code ,City - Country
                                    </p>
                                </div>
                                <div>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p class="flex justify-between"><span class="font-medium text-gray-700">Issue Date:</span> <span>27 Jul 2022</span></p>
                                        <p class="flex justify-between"><span class="font-medium text-gray-700">Due Date:</span> <span>27 Aug 2022</span></p>
                                        <p class="flex justify-between"><span class="font-medium text-gray-700">Due Amount:</span> <span class="font-bold text-gray-800">$ 1,54,22</span></p>
                                        <p class="flex justify-between"><span class="font-medium text-gray-700">Recurring:</span> <span>15 Months</span></p>
                                        <p class="flex justify-between"><span class="font-medium text-gray-700">PO Number:</span> <span>54515454</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Table -->
                        <div class="mb-8 overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3">Category</th>
                                        <th class="px-4 py-3">Rate/Item</th>
                                        <th class="px-4 py-3">Quantity</th>
                                        <th class="px-4 py-3">Discount (%)</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="px-4 py-3 text-gray-800">Dell Laptop</td>
                                        <td class="px-4 py-3 text-gray-600">Laptop</td>
                                        <td class="px-4 py-3 text-gray-600">$1,110</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">2</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">2%</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-800">$400</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-gray-800">HP Laptop</td>
                                        <td class="px-4 py-3 text-gray-600">Laptop</td>
                                        <td class="px-4 py-3 text-gray-600">$1,500</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">3</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">6%</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-800">$3,000</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-gray-800">Apple Ipad</td>
                                        <td class="px-4 py-3 text-gray-600">Ipad</td>
                                        <td class="px-4 py-3 text-gray-600">$11,500</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">1</td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">10%</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-800">$11,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer calculations -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                            <div>
                                <h4 class="text-base font-bold text-gray-800 mb-3 pb-2 border-b border-gray-100">Payment Details</h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mb-0 flex items-center gap-2">
                                        <i class="far fa-credit-card text-gray-400"></i>
                                        Debit Card XXXXXXXXXXXX-2541 HDFC Bank
                                    </p>
                                </div>
                            </div>
                            <div>
                                <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                                    <div class="space-y-3 text-sm mb-4 pb-4 border-b border-blue-200">
                                        <p class="flex justify-between text-gray-600"><span>Taxable</span> <span class="font-medium text-gray-800">$6,660.00</span></p>
                                        <p class="flex justify-between text-gray-600"><span>Additional Charges</span> <span class="font-medium text-gray-800">$6,660.00</span></p>
                                        <p class="flex justify-between text-gray-600"><span>Discount</span> <span class="font-medium text-gray-800">$3,300.00</span></p>
                                        <p class="flex justify-between text-gray-800 font-medium"><span>Sub total</span> <span>$3,300.00</span></p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-lg font-bold text-gray-800">Total Amount</h4>
                                        <span class="text-2xl font-bold text-blue-600">$143,300.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Signature -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-2 space-y-6">
                                <div>
                                    <h6 class="text-sm font-bold text-gray-800 mb-1">Notes:</h6>
                                    <p class="text-sm text-gray-600">Enter customer notes or any other details</p>
                                </div>
                                <div>
                                    <h6 class="text-sm font-bold text-gray-800 mb-1">Terms and Conditions:</h6>
                                    <p class="text-sm text-gray-600">Enter customer notes or any other details</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="inline-block">
                                    <img class="h-16 mb-2 object-contain ml-auto" src="{{ URL::to('assets/img/signature.png') }}" alt="sign">
                                    <span class="block text-sm font-medium text-gray-800 border-t border-gray-200 pt-2">{{ Session::get('name') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 border-t border-gray-100 flex justify-end">
                <button type="button" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bank Details Modal -->
<div class="modal fade" id="bank_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="modal-header p-6 border-b border-gray-100 flex justify-between items-center">
                <h4 class="text-xl font-bold text-gray-800 mb-0">Add Bank Details</h4>
                <button type="button" class="text-gray-400 hover:text-gray-600" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                        <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="account_holder_name" placeholder="Add Name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank name</label>
                        <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="bank_name" placeholder="Add Bank name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                        <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="ifsc_code" placeholder="IFSC Code">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="account_number" placeholder="Account Number">
                    </div>
                </div>
            </div>
            <div class="modal-footer p-6 border-t border-gray-100 flex gap-3 justify-end">
                <button type="button" data-bs-dismiss="modal" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Cancel</button>
                <button type="button" id="save-item" data-bs-dismiss="modal" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Save Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_invoices_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Invoice Details</h3>
                <p class="text-gray-500 mb-6">Are you sure want to delete?</p>
                <div class="flex gap-3 justify-center">
                    <a href="javascript:void(0);" data-bs-dismiss="modal" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</a>
                    <a href="javascript:void(0);" data-bs-dismiss="modal" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Save Draft Modal -->
<div class="modal fade" id="save_invocies_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-save"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Save Invoice Details</h3>
                <p class="text-gray-500 mb-6">Are you sure want to save?</p>
                <div class="flex gap-3 justify-center">
                    <a href="#" data-bs-dismiss="modal" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Save</a>
                    <a href="#" data-bs-dismiss="modal" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    {{-- show hide [Bank Details]--}}
    <script>
        $('#bank-details').hide();
        $('#btn-remove-bank-details').hide();
        $(function() {
            $("#save-item").click(function() {
                if (!isNaN($("#account_holder_name").val())) {
                    $('#bank-details').hide();
                } else {
                    $('#bank-details').show();
                    $('#btn-add-bank-details').hide();
                    $('#btn-remove-bank-details').removeClass('hidden').show();
                    var account_holder_name = $('#account_holder_name').val();
                    var bank_name = $('#bank_name').val();
                    var ifsc_code = $('#ifsc_code').val();
                    var account_number = $('#account_number').val();

                    $('#auto_account_holder_name').val(account_holder_name);
                    $('#auto_bank_name').val(bank_name);
                    $('#auto_ifsc_code').val(ifsc_code);
                    $('#auto_account_number').val(account_number);
                }
            });
            $("#btn-remove-bank-details").click(function() {
                $('#bank-details').hide();
                $('#btn-add-bank-details').show();
                $('#btn-remove-bank-details').hide();
            });
        });
    </script>

    {{-- show hide [Recurring Invoice]--}}
    <script>
        $(function() {
            $("input[name='recurring_incoice']").click(function() {
                if ($("#chkYes").is(":checked")) {
                    $("#show-invoices").removeClass('hidden').show();
                } else {
                    $("#show-invoices").hide();
                }
            });
        });
    </script>

    {{-- add rows and remove [Item Details]--}}
    <script>
        $(".add-table-items").on('click', '.remove-btn', function() {
            $(this).closest('.add-row').remove();
            return false;
        });
        
        $(document).on("click", ".add-btn", function() {
            var experiencecontent =
            '<tr class="add-row">' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error("items.*") border-red-500 @enderror" name="items[]">' + '</td>' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="category[]">' + '</td>' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" name="quantity[]">' + '</td>' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm price" name="price[]">' + '</td>' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm amount" name="amount[]">' + '</td>' +
                '<td class="p-2">' + '<input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm discount" name="discount[]">' + '</td>' +
                '<td class="p-2 text-right">' +
                    '<div class="flex items-center justify-end gap-2">' +
                        '<a class="add-btn text-blue-600 hover:text-blue-800 cursor-pointer p-1"><i class="fas fa-plus-circle"></i></a> ' +
                        '<a class="copy-btn text-gray-500 hover:text-gray-700 cursor-pointer p-1"><i class="far fa-copy"></i></a>' +
                        '<a class="remove-btn text-red-500 hover:text-red-700 cursor-pointer p-1"><i class="far fa-trash-alt"></i></a>' +
                    '</div>' +
                '</td>' +
            '</tr>';
            $(".add-table-items").append(experiencecontent);
            return false;
        });
    </script>

    <script>
        $('#invoice-add-table tbody').on("keyup",".price",function()
        {
            var parent = $(this).closest('tr');
            var price  = parseFloat($(parent).find('.price').val());
            $(parent).find('.price').val(price);
            GrandTotal();
        });

        function GrandTotal() {
            var sum = 0;
            $('.price').each(function() {
                sum += Number($(this).val());
            });
            $(document).on("change keyup blur", ".discount", function() 
            {
                var discount = parseFloat($('.discount').val());
                var calculatedDiscount = (sum * discount) / 100;
                var totalAmount = sum - calculatedDiscount;
                if (!isNaN(totalAmount)) {
                    document.querySelector('.total_amount').innerText = totalAmount;
                    $('#total_amounts').val(totalAmount);
                }
            }); 
        };
    </script>

    {{-- Summary --}}
    <script>
        $(document).on("click", ".add-links", function() {
            var experiencecontent = 
            '<div class="links-cont flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-200 mt-2">' +
                '<div class="service-amount flex items-center justify-between w-full">' +
                    '<a href="#" class="service-trash text-red-500 hover:text-red-700 flex items-center gap-1 text-sm">' +
                        '<i class="fas fa-minus-circle"></i> Service Charge' +
                    '</a> ' +
                    '<span class="font-medium text-gray-700">$ 4</span><input name="service_charge[]" value="4" hidden>' +
                '</div>' +
            '</div>';
            $(".links-info-one").append(experiencecontent);
            return false;
        });

        $(".links-info-discount").on('click', '.service-trash-one', function() {
            $(this).closest('.links-cont-discount').remove();
            return false;
        });

        $(document).on("click", ".add-links-one", function() {
            var experiencecontent =
            '<div class="links-cont-discount flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-200 mt-2">' +
                '<div class="service-amount flex items-center justify-between w-full">' +
                    '<a href="#" class="service-trash-one text-red-500 hover:text-red-700 flex items-center gap-1 text-sm">' +
                        '<i class="fas fa-minus-circle"></i> Offer new' +
                    '</a>' +
                    '<span class="font-medium text-gray-700">$ 4 %</span><input name="offer_new[]" value="4" hidden>' +
                '</div>' +
            '</div>';
            $(".links-info-discount").append(experiencecontent);
            return false;
        });

        $(document).on("click", ".add-links", function() {
            var experiencecontent = 
            '<div class="row form-row links-cont">' +
                '<div class="form-group d-flex">' +
                    '<button class="btn social-icon">' +
                        '<i class="feather-github"></i>' +
                    '</button>' +
                    '<input type="text" class="form-control" placeholder="Social Link">' +
                    '<div>' +
                        '<a href="#" class="btn trash">' +
                            '<i class="feather-trash-2"></i>' +
                        '</a>' +
                    '</div>' +
                '</div>' +
            '</div>';
            $(".settings-form").append(experiencecontent);
            return false;
        });
    </script>
@endsection
@endsection
