@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Academic Sessions" parent="Academic" :parentRoute="route('academic-sessions.index')" />

    <x-card noPadding="true">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Sessions</h3>
            <a href="{{ route('academic-sessions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium text-sm flex items-center gap-2 w-fit">
                <i class="fas fa-plus"></i> Add Session
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Session Name</th>
                        <th class="px-6 py-4">Start Date</th>
                        <th class="px-6 py-4">End Date</th>
                        <th class="px-6 py-4">Terms</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sessions as $key => $session)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $session->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $session->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $session->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('academic-sessions.terms.index', $session) }}" class="text-blue-600 hover:underline">
                                    {{ $session->terms_count }} {{ Str::plural('term', $session->terms_count) }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($session->is_current)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Current</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('academic-sessions.terms.index', $session) }}" class="w-8 h-8 rounded bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition" title="Manage Terms">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                    <a href="{{ route('academic-sessions.edit', $session) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition session_delete" data-id="{{ $session->id }}" data-bs-toggle="modal" data-bs-target="#deleteSession">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                                No academic sessions found. Create your first session to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- Delete Modal --}}
<div class="modal custom-modal fade" id="deleteSession" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-lg">
            <div class="modal-body p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Session</h3>
                <p class="text-gray-500">This will also delete all associated terms. Are you sure?</p>
                <form id="deleteSessionForm" method="POST" class="x-submit mt-4" data-then="reload">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-center gap-4">
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</button>
                        <a href="#" data-bs-dismiss="modal" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
    $(document).on('click', '.session_delete', function() {
        var id = $(this).data('id');
        $('#deleteSessionForm').attr('action', '/academic-sessions/' + id);
    });
</script>
@endsection
@endsection
