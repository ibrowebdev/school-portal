@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Results: {{ $child->name }}</h2>
            <div class="text-gray-500">{{ $child->studentProfile->schoolClass?->name }} • {{ $child->studentProfile->admission_id }}</div>
        </div>
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline font-medium"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    {{-- Filter Form --}}
    <x-card noPadding="true" class="mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-100 rounded-t-xl">
            <form action="{{ route('parent.child-results', $child) }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Academic Session</label>
                    <select name="academic_session_id" class="px-3 py-1.5 border border-gray-300 rounded text-sm" onchange="this.form.submit()">
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $sessionId == $session->id ? 'selected' : '' }}>{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Term</label>
                    <select name="term_id" class="px-3 py-1.5 border border-gray-300 rounded text-sm" onchange="this.form.submit()">
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ $termId == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(count($results) > 0)
                    <div class="ml-auto">
                        <a href="{{ route('results.report.pdf', ['student' => $child, 'academic_session_id' => $sessionId, 'term_id' => $termId]) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm font-medium text-sm flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </x-card>

    <x-card noPadding="true">
        @if(count($results) == 0)
            <div class="p-16 text-center text-gray-400">
                <i class="fas fa-clipboard-list text-5xl mb-4 block text-gray-300"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No Results Found</h3>
                <p>There are no results published for the selected term yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800 text-white font-medium border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 uppercase tracking-wider">Subjects</th>
                            <th class="px-6 py-4 text-center w-24">CA (40)</th>
                            <th class="px-6 py-4 text-center w-24">Exam (60)</th>
                            <th class="px-6 py-4 text-center w-32 font-bold bg-gray-900">Total (100)</th>
                            <th class="px-6 py-4 text-center w-24">Grade</th>
                            <th class="px-6 py-4">Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php 
                            $totalScore = 0; 
                            $maxScore = count($results) * 100;
                        @endphp
                        @foreach($results as $result)
                            @php $totalScore += $result->total_score; @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $result->subject->name }}</td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600">{{ (float)$result->ca_score }}</td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600">{{ (float)$result->exam_score }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-800 bg-blue-50 border-l border-r border-blue-100">{{ (float)$result->total_score }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                        @if($result->grade == 'A') bg-green-100 text-green-800
                                        @elseif(in_array($result->grade, ['B', 'C'])) bg-blue-100 text-blue-800
                                        @elseif($result->grade == 'D') bg-yellow-100 text-yellow-800
                                        @elseif(in_array($result->grade, ['E', 'F'])) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ $result->grade ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 italic">{{ $result->remark }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right uppercase text-gray-600">Total Score / Average:</td>
                            <td class="px-6 py-4 text-center text-lg text-blue-800 bg-blue-50 border-l border-r border-blue-100">{{ $totalScore }} / {{ $maxScore }}</td>
                            <td colspan="2" class="px-6 py-4 text-left text-green-700">
                                {{ number_format(($totalScore / $maxScore) * 100, 2) }}% Average
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </x-card>
</div>
@endsection
