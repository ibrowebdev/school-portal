@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="List Users" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('home')],
        ['label' => 'List Users', 'url' => '#']
    ]" />

    <!-- Search Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Search by ID ...">
            </div>
            <div>
                <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Search by Name ...">
            </div>
            <div>
                <input type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Search by Phone ...">
            </div>
            <div>
                <button type="button" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Search</button>
            </div>
        </div>
    </div>

    <!-- Users Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h5 class="font-bold text-gray-800">Users List</h5>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap" id="UsersList">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">Profile</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone Number</th>
                        <th class="px-4 py-3">Date Join</th>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- delete modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 transition-opacity" id="delete" tabindex="-1" aria-hidden="true">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 overflow-hidden relative">
        <div class="p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete User</h3>
            <p class="text-gray-600 mb-6">Are you sure want to delete?</p>
            
            <div id="form-errors-container" class="hidden mb-4 p-3 bg-red-50 text-red-600 rounded-lg border border-red-100 text-left">
                <ul id="form-errors-list" class="list-disc list-inside text-sm"></ul>
            </div>
            
            <form action="{{ route('users.destroy', $record->id ?? $user->id ?? 0) }}" method="POST" class="x-submit" data-then="reload">
                @csrf
                @method('DELETE')
                <input type="hidden" name="user_id" class="e_user_id" value="">
                <input type="hidden" name="avatar" class="e_avatar" value="">
                
                <div class="grid grid-cols-2 gap-4">
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-sm">Delete</button>
                    <button type="button" data-bs-dismiss="modal" class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    {{-- Tailwind modal trigger fix --}}
    <script>
        $(document).on('click', '[data-bs-toggle="modal"]', function(e) {
            e.preventDefault();
            var target = $(this).attr('data-bs-target');
            $(target).removeClass('hidden').addClass('flex');
        });
        $(document).on('click', '[data-bs-dismiss="modal"]', function(e) {
            $(this).closest('.fixed').addClass('hidden').removeClass('flex');
        });
    </script>
    
    {{-- delete js --}}
    <script>
        $(document).on('click','.delete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_user_id').val(_this.find('.user_id').data('user_id'));
            $('.e_avatar').val(_this.find('.avatar').data('avatar'));
            $('#delete').removeClass('hidden').addClass('flex');
        });
    </script>

    {{-- get user all js --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#UsersList').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                ajax: {
                    url:"{{ route('get-users-data') }}",
                },
                columns: [
                    { data: 'user_id', name: 'user_id' },
                    { data: 'avatar', name: 'avatar' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'join_date', name: 'join_date' },
                    { data: 'position', name: 'position' },
                    { data: 'status', name: 'status' },
                    { data: 'modify', name: 'modify' },
                ],
                // Add simple tailwind classes to datatable elements if possible via DT config, 
                // or let the existing CSS handle it (we've provided w-full text-sm on the table).
            });
        });
    </script>
@endsection
@endsection
