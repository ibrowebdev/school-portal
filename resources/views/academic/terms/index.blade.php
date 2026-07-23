@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Terms — {{ $academicSession->name }}" parent="Academic Sessions" :parentRoute="route('academic-sessions.index')" />

    <x-card noPadding="true">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Terms for {{ $academicSession->name }}</h3>
            <a href="{{ route('academic-sessions.terms.create', $academicSession) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium text-sm flex items-center gap-2 w-fit">
                <i class="fas fa-plus"></i> Add Term
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Term Name</th>
                        <th class="px-6 py-4">Start Date</th>
                        <th class="px-6 py-4">End Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($terms as $key => $term)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $term->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $term->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $term->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                @if($term->is_current)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Current</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('terms.edit', $term) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition term_delete" data-id="{{ $term->id }}" data-bs-toggle="modal" data-bs-target="#deleteTerm">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-calendar text-4xl mb-3 block"></i>
                                No terms found. Add terms to this session.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

@section('script')
<script>
    $(document).on('click', '.term_delete', function() {
        var id = $(this).data('id');
        $('#deleteTermForm').attr('action', '/terms/' + id);
    });
</script>
@endsection
@endsection
