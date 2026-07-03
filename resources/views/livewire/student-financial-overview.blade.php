<div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <i class="fas fa-file-invoice-dollar text-blue-500 mr-2"></i> Financial Overview
        </h3>
        @if($activeSession && $activeTerm)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ $activeSession->academic_session }} - {{ $activeTerm->term }}
            </span>
        @endif
    </div>

    @if($activeSession && $activeTerm && $student->studentProfile?->school_class_id)
        <div class="p-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="border border-gray-200 rounded-lg p-4 text-center bg-gray-50">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Billed</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">${{ number_format($totalBilled, 2) }}</p>
                </div>
                <div class="border border-green-200 rounded-lg p-4 text-center bg-green-50">
                    <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Total Paid</p>
                    <p class="mt-2 text-2xl font-bold text-green-700">${{ number_format($totalPaid, 2) }}</p>
                </div>
                <div class="border {{ $outstanding > 0 ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg p-4 text-center">
                    <p class="text-xs font-medium {{ $outstanding > 0 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-wide">Outstanding Balance</p>
                    <p class="mt-2 text-2xl font-bold {{ $outstanding > 0 ? 'text-red-700' : 'text-gray-900' }}">${{ number_format($outstanding, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bill Breakdown -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Itemized Bill</h4>
                    @if(count($billables) > 0)
                        <ul class="divide-y divide-gray-100 border border-gray-100 rounded-lg">
                            @foreach($billables as $item)
                                <li class="px-4 py-3 flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ $item->feesType->fees_type }}</span>
                                    <span class="font-medium text-gray-900">${{ number_format($item->amount, 2) }}</span>
                                </li>
                            @endforeach
                            <li class="px-4 py-3 flex justify-between items-center text-sm bg-gray-50 rounded-b-lg border-t border-gray-200">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-gray-900">${{ number_format($totalBilled, 2) }}</span>
                            </li>
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-lg">No fees assigned for this term.</p>
                    @endif
                </div>

                <!-- Payment History -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Payment History</h4>
                    @if(count($paymentHistory) > 0)
                        <ul class="divide-y divide-gray-100 border border-gray-100 rounded-lg max-h-60 overflow-y-auto">
                            @foreach($paymentHistory as $payment)
                                <li class="px-4 py-3 flex justify-between items-center text-sm">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $payment->payment_method }} {{ $payment->reference_number ? '(#'.$payment->reference_number.')' : '' }}</p>
                                    </div>
                                    <span class="font-bold text-green-600">+${{ number_format($payment->amount_paid, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-lg">No payments recorded for this term.</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="p-12 text-center text-gray-400">
            <i class="fas fa-exclamation-circle text-4xl mb-3 text-gray-300"></i>
            <p class="text-sm">Cannot load financial data. Ensure the student is assigned to a class and an active term/session is set.</p>
        </div>
    @endif
</div>
