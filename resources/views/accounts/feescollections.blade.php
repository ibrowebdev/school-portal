@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Fees Collections</h3>
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">Fees Collections</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="#" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition flex items-center gap-2">
                <i class="fas fa-download"></i> Download
            </a>
            <a href="{{ route('fees.create') }}" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition shadow-sm">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Gender</th>
                        <th class="px-6 py-4">Fees Type</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Paid Date</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($feesInformation as $key => $value)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">ST-{{ $value->student_id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full object-cover border border-gray-200" src="{{ URL::to('/images/'. $value->avatar) }}" alt="{{ $value->student_name }}">
                                    <span class="font-medium text-gray-800">{{ $value->student_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $value->gender }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $value->fees_type }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">${{ $value->fees_amount }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $value->paid_date }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Paid</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
