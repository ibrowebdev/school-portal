@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="School Classes" parent="Academic" :parentRoute="route('school-classes.index')" />

    <x-card noPadding="true">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Classes</h3>
            <a href="{{ route('school-classes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium text-sm flex items-center gap-2 w-fit">
                <i class="fas fa-plus"></i> Add Class
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Class Name</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4">Sections</th>
                        <th class="px-6 py-4">Subjects</th>
                        <th class="px-6 py-4">Students</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($classes as $key => $schoolClass)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $schoolClass->name }}</td>
                            <td class="px-6 py-4 text-gray-600 capitalize">{{ $schoolClass->level ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-800 bg-blue-100 rounded-full">{{ $schoolClass->sections_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-purple-800 bg-purple-100 rounded-full">{{ $schoolClass->subjects_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 rounded-full">{{ $schoolClass->student_profiles_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('school-classes.show', $schoolClass) }}" class="w-8 h-8 rounded bg-gray-50 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition" title="View & Map">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('school-classes.edit', $schoolClass) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition class_delete" data-id="{{ $schoolClass->id }}" data-bs-toggle="modal" data-bs-target="#deleteClass" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-school text-4xl mb-3 block"></i>
                                No classes found. Add classes to get started.
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
    $(document).on('click', '.class_delete', function() {
        var id = $(this).data('id');
        $('#deleteClassForm').attr('action', '/school-classes/' + id);
    });
</script>
@endsection
@endsection
