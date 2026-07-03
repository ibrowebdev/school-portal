<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Card - {{ $student->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 0 0 5px 0; }
        .school-address { font-size: 12px; margin: 0 0 10px 0; }
        .report-title { font-size: 16px; font-weight: bold; text-transform: uppercase; padding: 5px 10px; border: 1px solid #000; display: inline-block; background-color: #f0f0f0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .info-label { font-weight: bold; width: 120px; }
        .info-value { border-bottom: 1px solid #ccc; font-weight: bold; }
        
        .photo-container { width: 100px; height: 100px; border: 1px solid #000; float: right; padding: 2px; }
        .photo-container img { width: 100%; height: 100%; object-fit: cover; }
        
        .meta-box { background-color: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; }
        .meta-box strong { margin-right: 20px; }

        .grades-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .grades-table th, .grades-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .grades-table th { background-color: #f0f0f0; text-transform: uppercase; text-align: center; }
        .grades-table .subject-col { text-align: left; font-weight: bold; }
        .grades-table .center { text-align: center; }
        .grades-table .total-col { text-align: center; font-weight: bold; }
        .grades-table .grade-col { text-align: center; font-weight: bold; color: #000; }
        
        .footer-grid { width: 100%; margin-top: 40px; }
        .grading-key { width: 45%; float: left; }
        .grading-key h4 { border-bottom: 1px solid #000; text-transform: uppercase; margin-top: 0; padding-bottom: 3px; }
        .key-row { margin-bottom: 3px; font-size: 10px; }
        
        .signatures { width: 45%; float: right; }
        .sig-line { border-bottom: 1px solid #000; margin-top: 30px; margin-bottom: 5px; }
        .sig-text { text-align: center; font-weight: bold; text-transform: uppercase; font-size: 10px; margin-bottom: 20px; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="school-name">{{ \App\Models\Setting::get('website_name', 'School Portal') }}</h1>
        <p class="school-address">123 Education Lane, Learning City</p>
        <div class="report-title">Terminal Report Card</div>
    </div>

    <table class="info-table">
        <tr>
            <td style="vertical-align: top;">
                <table style="width: 100%">
                    <tr>
                        <td class="info-label">Student Name:</td>
                        <td class="info-value">{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Admission No:</td>
                        <td class="info-value">{{ $student->studentProfile->admission_id }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Class:</td>
                        <td class="info-value">{{ $student->studentProfile->schoolClass?->name }} {{ $student->studentProfile->classSection?->name }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 120px; vertical-align: top;">
                <div class="photo-container">
                    <img src="{{ $student->avatar ? public_path($student->avatar) : public_path('photo_defaults.jpg') }}" alt="Photo">
                </div>
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <strong>Academic Session:</strong> {{ $session?->name }}
        <strong>Term:</strong> {{ $term?->name }}
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th class="subject-col">Subjects</th>
                <th style="width: 50px;">CA (40)</th>
                <th style="width: 50px;">Exam (60)</th>
                <th style="width: 60px;">Total (100)</th>
                <th style="width: 50px;">Grade</th>
                <th style="width: 150px; text-align: left;">Remark</th>
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
                    <td class="subject-col">{{ $result->subject->name }}</td>
                    <td class="center">{{ (float)$result->ca_score }}</td>
                    <td class="center">{{ (float)$result->exam_score }}</td>
                    <td class="total-col">{{ (float)$result->total_score }}</td>
                    <td class="grade-col">{{ $result->grade }}</td>
                    <td><i>{{ $result->remark }}</i></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No results found for this term.</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($results) > 0)
        <tfoot>
            <tr style="background-color: #f0f0f0;">
                <td colspan="3" style="text-align: right; font-weight: bold; text-transform: uppercase;">Total Score / Average:</td>
                <td class="total-col">{{ $totalScore }} / {{ $maxScore }}</td>
                <td colspan="2" style="font-weight: bold;">
                    {{ number_format(($totalScore / $maxScore) * 100, 2) }}% Average
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer-grid clearfix">
        <div class="grading-key">
            <h4>Grading Key</h4>
            @foreach(\App\Models\GradeSetting::orderByDesc('min_score')->get() as $gs)
                <div class="key-row">
                    <strong>{{ $gs->grade }} ({{ $gs->remark }}):</strong> {{ $gs->min_score }} - {{ $gs->max_score }}
                </div>
            @endforeach
        </div>
        
        <div class="signatures">
            <div class="sig-line"></div>
            <div class="sig-text">Class Teacher's Signature</div>
            
            <div class="sig-line" style="margin-top: 40px;"></div>
            <div class="sig-text">Principal's Signature</div>
        </div>
    </div>

</body>
</html>
