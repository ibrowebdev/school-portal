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

{{-- model delete --}}
<div class="modal custom-modal fade" id="delete" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="modal-body p-6 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Department</h3>
                    <p class="text-gray-500">Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div id="form-errors-container" class="hidden alert alert-danger" style="display: none;">
                        <ul id="form-errors-list" class="mb-0"></ul>
                    </div>
                    <form action="{{ route('departments.destroy', $record->id ?? $department->id ?? 0) }}" method="POST" class="x-submit" data-then="reload">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="department_id" class="e_department_id" value="">
                        <div class="flex items-center justify-center gap-4">
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium w-full sm:w-auto">Delete</button>
                            <a href="#" data-bs-dismiss="modal" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium w-full sm:w-auto cursor-pointer">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
