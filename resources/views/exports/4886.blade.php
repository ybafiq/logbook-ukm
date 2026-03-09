<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>STBC4886 - {{ $user->name }}</title>
    <style>
        /* Reuse styling from exports/logbook.blade.php to keep layout consistent */
        @page { margin: 2.5cm 2cm 2.5cm 2cm; }
        body { font-family: 'Times New Roman', serif; font-size: 11px; line-height: 1.4; color: #000; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 13px; font-weight: bold; margin: 0; }
        .header h2 { font-size: 12px; font-weight: bold; margin: 3px 0; }
        .student-info { margin-bottom: 15px; font-size: 11px; line-height: 1.8; }
        .logbook-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 10px; margin-bottom: 25px; }
        .logbook-table th, .logbook-table td { border: 0.5px solid #000; padding: 6px; vertical-align: top; font-size: 11px; }
        .date-col { width: 15%; text-align: center; }
        .activity-col { width: 50%; }
        .comment-col { width: 35%; }
        .reflection-section, .supervisor-section { border: 1px solid #000; margin-bottom: 20px; page-break-inside: avoid; }
        .reflection-header, .supervisor-header { padding: 6px 8px; font-size: 11px; font-weight: bold; border-bottom: 1px solid #000; }
        .reflection-content { padding: 12px; min-height: 100px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STBC4886 Templat Buku Log / Student’s Logbook Template</h1>
        <h2>(Custom 4886 Output)</h2>
        <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
    </div>

    <div class="student-info">
        <div class="info-line">Nama Pelajar / <i>Name of Student:</i>
            <span class="underline">{{ $user->name ?? ' ' }}</span>
        </div>
        <div class="info-line">No. Pendaftaran / <i>Matric No.:</i>
            <span class="underline">{{ $user->matric_no ?? ' ' }}</span>
        </div>
        <div class="info-line">Tempat kerja / <i>Working place:</i>
            <span class="underline">{{ $user->workplace ?? ' ' }}</span>
        </div>
    </div>

    <table class="logbook-table">
        <thead>
            <tr>
                <th class="date-col">Tarikh / Date</th>
                <th class="activity-col">Aktiviti / Activity</th>
                <th class="comment-col">Komen / Comment</th>
            </tr>
        </thead>
        <tbody>
            @php
                $rows = [];
                if(!empty($logEntries)) { foreach($logEntries as $e) $rows[] = $e; }
                if(!empty($projectEntries)) { foreach($projectEntries as $e) $rows[] = $e; }
                usort($rows, function($a,$b){ return strtotime($a->date) <=> strtotime($b->date); });
            @endphp

            @forelse($rows as $entry)
                <tr>
                    <td class="date-col">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                    <td class="activity-col">{{ $entry->activity }}</td>
                    <td class="comment-col">{{ $entry->comment ?? '' }}</td>
                </tr>
            @empty
                @for($i=0;$i<8;$i++)
                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                @endfor
            @endforelse
        </tbody>
    </table>

    <div class="reflection-section">
        <!-- Summary of achievements section (Ringkasan pencapaian) -->
        <div class="reflection-header">Ringkasan pencapaian / Summary of achievements:</div>
        <div class="reflection-content">
            @if(!empty($weeklyReflectionsContents))
                {{-- Use the first part of weekly reflections as a placeholder for summary if available --}}
                {{ Str::limit(ltrim($weeklyReflectionsContents), 800) }}
            @else
                <br><br><br><br><br><br>
            @endif
        </div>

        <!-- Weekly reflection section -->
        <div class="reflection-header">Refleksi Mingguan / Weekly reflection:</div>
        <div class="reflection-content">
            @if(!empty($weeklyReflectionsContents))
                {{ ltrim($weeklyReflectionsContents) }}
            @else
                <br><br><br><br><br>
            @endif
        </div>
    </div>

    @if($includeReflection)
    <div class="supervisor-section">
        <div class="supervisor-header">Cop dan tandatangan penyelia industri (setiap 3 minggu) / Industry supervisor's cop and signature (every 3 weeks):</div>
        <div style="padding:12px; min-height:80px;">
            <!-- leave space for signature -->
            <br><br><br>
        </div>
    </div>
    @endif

</body>
</html>
