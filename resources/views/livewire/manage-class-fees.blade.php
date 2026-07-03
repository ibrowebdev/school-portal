<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Settings -->
    <div class="space-y-6">
        
        <!-- Manage Fee Types -->
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Manage Fee Types</h3>
            @if (session()->has('type_message'))
                <div class="mb-4 p-2 bg-green-100 text-green-700 rounded text-sm">{{ session('type_message') }}</div>
            @endif
            
            <form wire:submit.prevent="createFeeType" class="flex gap-2 mb-4">
                <input type="text" wire:model.defer="newFeeTypeName" placeholder="E.g. Uniform" class="block w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Add</button>
            </form>
            @error('newFeeTypeName') <span class="text-xs text-red-500 -mt-2 mb-2 block">{{ $message }}</span> @enderror

            <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                @foreach($feesTypes as $type)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $type->fees_type }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Class Selection Filters -->
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Configuration</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Academic Session</label>
                    <select wire:model.live="selectedSession" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Session --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->academic_session }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Term</label>
                    <select wire:model.live="selectedTerm" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Term --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}">{{ $term->term }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">School Class</label>
                    <select wire:model.live="selectedClass" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Class Fees List -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6 min-h-[400px]">
            @if($selectedClass && $selectedSession && $selectedTerm)
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Class Billables</h3>
                    <div class="text-sm text-gray-500">
                        Total: <span class="font-bold text-gray-900">${{ number_format(collect($classFees)->sum('amount'), 2) }}</span>
                    </div>
                </div>

                @if (session()->has('fee_message'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-200">{{ session('fee_message') }}</div>
                @endif
                @if (session()->has('fee_error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-200">{{ session('fee_error') }}</div>
                @endif

                <!-- Add Fee Form -->
                <form wire:submit.prevent="addClassFee" class="bg-gray-50 p-4 rounded-lg mb-6 flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Fee Type</label>
                        <select wire:model.defer="addFeeTypeId" class="block w-full text-sm border-gray-300 rounded-md">
                            <option value="">Select...</option>
                            @foreach($feesTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->fees_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" step="0.01" wire:model.defer="addAmount" class="block w-full text-sm border-gray-300 rounded-md" placeholder="0.00">
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition h-[38px]">
                            <i class="fas fa-plus mr-1"></i> Attach
                        </button>
                    </div>
                </form>

                <!-- List of assigned fees -->
                @if(count($classFees) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($classFees as $fee)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $fee->feesType->fees_type }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">${{ number_format($fee->amount, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <button wire:click="removeClassFee({{ $fee->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-3 text-gray-300"></i>
                        <p>No billables assigned to this class for the selected term.</p>
                    </div>
                @endif
            @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-20">
                    <i class="fas fa-cogs text-5xl mb-4 text-gray-300"></i>
                    <p class="text-sm">Select Session, Term, and Class on the left to configure fees.</p>
                </div>
            @endif
        </div>
    </div>

</div>
