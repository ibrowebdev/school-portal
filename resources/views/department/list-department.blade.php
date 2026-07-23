@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Departments" parent="Dashboard" :parentRoute="url('home')" />

    <!-- Search Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search ID</label>
                <input type="text" id="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by ID...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Name</label>
                <input type="text" id="department_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by Name...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Year</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search by Year...">
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
            <h3 class="text-lg font-bold text-gray-800">Departments</h3>
            <div class="flex items-center gap-2">
                <a href="#" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition flex items-center gap-2 text-sm font-medium">
                    <i class="fas fa-download"></i> Download
                </a>
                <a href="{{ route('departments.create') }}" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left" id="dataList">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">HOD</th>
                        <th class="px-6 py-4">Started Year</th>
                        <th class="px-6 py-4">No of Students</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </x-card>
</div>



@section('script')
    {{-- get data all js --}}
    <script type="text/javascript">
        $(document).ready(function() {
        $('#dataList').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                ajax: {
                    url:"{{ route('departments.data-list') }}",
                },
                columns: [
                    {
                        data: 'department_id',
                        name: 'department_id',
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                    },
                    {
                        data: 'head_of_department',
                        name: 'head_of_department',
                    },
                    {
                        data: 'department_start_date',
                        name: 'department_start_date',
                    },
                    {
                        data: 'no_of_students',
                        name: 'no_of_students',
                    },
                    {
                        data: 'modify',
                        name: 'modify',
                    },
                ]
            });
        });
    </script>

    {{-- delete js --}}
<script>
    $(document).on('click','.delete',function()
    {
        var _this = $(this).parents('tr');
        $('.e_department_id').val(_this.find('.department_id').data('department_id'));
    });
</script>
@endsection
@endsection
