<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- Left Column: Search & Settings -->
    <div class="lg:col-span-4 space-y-6">
        
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Session</label>
                    <select wire:model.live="selectedSession" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Session --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->academic_session }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Term</label>
                    <select wire:model.live="selectedTerm" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Term --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}">{{ $term->term }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Search Student</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchStudent" class="block w-full pl-10 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search by name or email...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>

            @if(count($students) > 0)
                <ul class="mt-4 border border-gray-200 rounded-md divide-y divide-gray-200 max-h-60 overflow-y-auto">
                    @foreach($students as $student)
                        <li wire:click="selectStudent({{ $student->id }})" class="cursor-pointer hover:bg-gray-50 p-3 flex items-center transition">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                {{ substr($student->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->email }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    <!-- Right Column: Financial Overview & Payment Form -->
    <div class="lg:col-span-8">
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6 min-h-[500px]">
            @if($selectedStudent && $selectedSession && $selectedTerm)
                
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                        {{ substr($selectedStudent->first_name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</h2>
                        <p class="text-sm text-gray-500">
                            Class: {{ $selectedStudent->studentProfile?->schoolClass?->class_name ?? 'Not Assigned' }} | 
                            Session: {{ $sessions->find($selectedSession)->academic_session }} | 
                            Term: {{ $terms->find($selectedTerm)->term }}
                        </p>
                    </div>
                </div>

                @if (session()->has('payment_success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-200">
                        {{ session('payment_success') }}
                    </div>
                @endif

                <!-- Financial Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500 font-medium">Total Billed</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalBilled, 2) }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-sm text-green-600 font-medium">Total Paid</p>
                        <p class="text-2xl font-bold text-green-700">${{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="{{ $outstanding > 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} p-4 rounded-lg border">
                        <p class="text-sm {{ $outstanding > 0 ? 'text-red-600' : 'text-gray-500' }} font-medium">Outstanding Balance</p>
                        <p class="text-2xl font-bold {{ $outstanding > 0 ? 'text-red-700' : 'text-gray-900' }}">${{ number_format($outstanding, 2) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Billables Breakdown -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4 border-b pb-2">Bill Breakdown</h4>
                        @if(count($billables) > 0)
                            <ul class="space-y-3">
                                @foreach($billables as $item)
                                    <li class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $item->feesType->fees_type }}</span>
                                        <span class="font-medium">${{ number_format($item->amount, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">No billables assigned to this class.</p>
                        @endif

                        <!-- Payment History -->
                        <h4 class="text-md font-medium text-gray-900 mt-8 mb-4 border-b pb-2">Payment History</h4>
                        @if(count($paymentHistory) > 0)
                            <ul class="space-y-3 max-h-40 overflow-y-auto">
                                @foreach($paymentHistory as $payment)
                                    <li class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded">
                                        <div>
                                            <span class="text-gray-600">{{ $payment->payment_date->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-400 block">{{ $payment->payment_method }} {{ $payment->reference_number ? '(#'.$payment->reference_number.')' : '' }}</span>
                                        </div>
                                        <span class="font-medium text-green-600">+${{ number_format($payment->amount_paid, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">No payments recorded yet.</p>
                        @endif
                    </div>

                    <!-- Record Payment Form -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <h4 class="text-md font-medium text-gray-900 mb-4 border-b pb-2">Record New Payment</h4>
                        
                        <form wire:submit.prevent="recordPayment" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount Paid ($)</label>
                                <input type="number" step="0.01" wire:model.defer="payAmount" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('payAmount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <input type="date" wire:model.defer="payDate" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('payDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select wire:model.defer="payMethod" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option>Cash</option>
                                    <option>Bank Transfer</option>
                                    <option>Credit Card</option>
                                    <option>Cheque</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reference / Receipt No. (Optional)</label>
                                <input type="text" wire:model.defer="payReference" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Record Payment
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-32">
                    <i class="fas fa-user-graduate text-5xl mb-4 text-gray-300"></i>
                    <p class="text-sm">Select Session, Term, and search for a student to view/record payments.</p>
                </div>
            @endif
        </div>
    </div>

</div>
