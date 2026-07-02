@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <x-page-header title="Student Report Card" parent="Results" :parentRoute="route('results.index')" noMargin="true" />
        
        <div class="flex gap-2">
            <a href="{{ route('results.report.pdf', ['student' => $student, 'academic_session_id' => $sessionId, 'term_id' => $termId]) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm font-medium text-sm flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- Term Selector Form --}}
    <x-card noPadding="true" class="mb-4">
        <div class="p-4 bg-gray-50 border-b border-gray-100 rounded-t-xl">
            <form action="{{ route('results.report', $student) }}" method="GET" class="flex flex-wrap gap-4 items-end">
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
            </form>
        </div>
    </x-card>

    {{-- Report Card Visual --}}
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 w-full max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-8 border-b-2 border-gray-800 pb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2 uppercase tracking-wide">PreSkool International</h1>
            <p class="text-gray-600 mb-4">123 Education Lane, Learning City</p>
            <h2 class="text-2xl font-bold text-blue-800 uppercase bg-blue-50 inline-block px-6 py-2 rounded-full border border-blue-200">Terminal Report Card</h2>
        </div>

        {{-- Student Info Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 text-sm">
            <div class="col-span-2 md:col-span-3">
                <div class="grid grid-cols-3 gap-2">
                    <div class="font-bold text-gray-600">Student Name:</div>
                    <div class="col-span-2 font-bold text-gray-900 border-b border-gray-300">{{ $student->name }}</div>
                    
                    <div class="font-bold text-gray-600">Admission No:</div>
                    <div class="col-span-2 font-bold text-gray-900 border-b border-gray-300">{{ $student->studentProfile->admission_id }}</div>
                    
                    <div class="font-bold text-gray-600">Class:</div>
                    <div class="col-span-2 font-bold text-gray-900 border-b border-gray-300">{{ $student->studentProfile->schoolClass?->name }} {{ $student->studentProfile->classSection?->name }}</div>
                </div>
            </div>
            <div class="flex justify-end">
                <div class="w-32 h-32 border-2 border-gray-300 rounded overflow-hidden p-1 shadow-sm bg-white">
                    <img src="{{ $student->avatar ? asset($student->avatar) : asset('photo_defaults.jpg') }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-sm bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-center gap-2">
                <span class="font-bold text-gray-600">Academic Session:</span>
                <span class="font-bold text-gray-900">{{ $sessions->firstWhere('id', $sessionId)?->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-bold text-gray-600">Term:</span>
                <span class="font-bold text-gray-900">{{ $terms->firstWhere('id', $termId)?->name }}</span>
            </div>
        </div>

        {{-- Grades Table --}}
        <table class="w-full text-sm border-collapse border border-gray-800 mb-8">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-800 px-4 py-2 text-left uppercase">Subjects</th>
                    <th class="border border-gray-800 px-4 py-2 text-center uppercase w-20">CA<br><span class="text-xs font-normal">(40)</span></th>
                    <th class="border border-gray-800 px-4 py-2 text-center uppercase w-20">Exam<br><span class="text-xs font-normal">(60)</span></th>
                    <th class="border border-gray-800 px-4 py-2 text-center uppercase w-24">Total<br><span class="text-xs font-normal">(100)</span></th>
                    <th class="border border-gray-800 px-4 py-2 text-center uppercase w-20">Grade</th>
                    <th class="border border-gray-800 px-4 py-2 text-left uppercase w-48">Remark</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalScore = 0; 
                    $maxScore = count($results) * 100;
                @endphp
                @forelse($results as $result)
                    @php $totalScore += $result->total_score; @endphp
                    <tr>
                        <td class="border border-gray-800 px-4 py-2 font-bold">{{ $result->subject->name }}</td>
                        <td class="border border-gray-800 px-4 py-2 text-center">{{ (float)$result->ca_score }}</td>
                        <td class="border border-gray-800 px-4 py-2 text-center">{{ (float)$result->exam_score }}</td>
                        <td class="border border-gray-800 px-4 py-2 text-center font-bold">{{ (float)$result->total_score }}</td>
                        <td class="border border-gray-800 px-4 py-2 text-center font-bold text-blue-800">{{ $result->grade }}</td>
                        <td class="border border-gray-800 px-4 py-2 text-gray-700 italic">{{ $result->remark }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-800 px-4 py-12 text-center text-gray-500 italic">No results found for this term.</td>
                    </tr>
                @endforelse
            </tbody>
            @if(count($results) > 0)
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td colspan="3" class="border border-gray-800 px-4 py-3 text-right uppercase">Total Score / Average:</td>
                    <td class="border border-gray-800 px-4 py-3 text-center text-lg text-blue-800">{{ $totalScore }} / {{ $maxScore }}</td>
                    <td colspan="2" class="border border-gray-800 px-4 py-3 text-left">
                        {{ number_format(($totalScore / $maxScore) * 100, 2) }}% Average
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>

        {{-- Grading Key & Signatures --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h4 class="font-bold text-gray-800 mb-2 uppercase border-b border-gray-300 pb-1">Grading Key</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                    @foreach(\App\Models\GradeSetting::orderByDesc('min_score')->get() as $gs)
                        <div class="flex justify-between">
                            <span class="font-bold">{{ $gs->grade }} ({{ $gs->remark }}):</span>
                            <span>{{ $gs->min_score }} - {{ $gs->max_score }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex flex-col justify-end space-y-6">
                <div>
                    <div class="border-b border-gray-800 pb-2"></div>
                    <div class="text-center font-bold text-gray-600 mt-1 uppercase text-sm">Class Teacher's Signature</div>
                </div>
                <div>
                    <div class="border-b border-gray-800 pb-2"></div>
                    <div class="text-center font-bold text-gray-600 mt-1 uppercase text-sm">Principal's Signature</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
