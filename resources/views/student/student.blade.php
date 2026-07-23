@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Students" parent="Student" :parentRoute="route('students.index')" />

    <!-- Search Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search ID</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by ID...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Name</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by Name...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Phone</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by Phone...">
            </div>
            <div>
                <button type="button" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <x-card noPadding="true">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Students List</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.index') }}" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center shadow-sm hover:bg-blue-700 transition">
                    <i class="fa fa-list"></i>
                </a>
                <a href="{{ route('students.grid') }}" class="w-10 h-10 border border-gray-200 text-gray-500 rounded-lg flex items-center justify-center hover:bg-gray-50 transition">
                    <i class="fa fa-th"></i>
                </a>
                <a href="#" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition flex items-center gap-2 text-sm font-medium">
                    <i class="fas fa-download"></i> Download
                </a>
                <a href="{{ route('students.create') }}" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Class</th>
                        <th class="px-6 py-4">DOB</th>
                        <th class="px-6 py-4">Parent Name</th>
                        <th class="px-6 py-4">Mobile Number</th>
                        <th class="px-6 py-4">Address</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($studentList as $list)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium id">{{ $loop->iteration }}</td>
                            <td hidden class="actual_id">{{ $list->id }}</td>
                            <td hidden class="avatar">{{ $list->upload }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="student-details.html" class="shrink-0">
                                        <img class="w-8 h-8 rounded-full object-cover border border-gray-200" src="{{ asset($list->upload) }}" alt="">
                                    </a>
                                    <a href="student-details.html" class="font-medium text-gray-800 hover:text-blue-600 transition">{{ $list->first_name }} {{ $list->last_name }}</a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $list->studentProfile?->schoolClass?->name }} {{ $list->studentProfile?->classSection?->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $list->date_of_birth }}</td>
                            <td class="px-6 py-4 text-gray-600">{{$list->studentProfile->parent?->name}}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $list->phone_number }}</td>
                            <td class="px-6 py-4 text-gray-600">{{$list->studentProfile->address}}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ url('student/edit/'.$list->id) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition student_delete" data-bs-toggle="modal" data-bs-target="#studentUser">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-100 p-6">
          {{ $studentList->links() }}
        </div>
    </x-card>
</div>


@section('script')
<script>
    $(document).on('click','.student_delete',function()
    {
        var _this = $(this).parents('tr');
        $('.e_id').val(_this.find('.actual_id').text());
        $('.e_avatar').val(_this.find('.avatar').text());
    });
</script>
@endsection
@endsection
