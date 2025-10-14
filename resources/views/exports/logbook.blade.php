<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Logbook - {{ $user->name }}</title>
    <style>
        /* ---------- General Page Setup ---------- */
        @page {
            margin: 25px 20px;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            word-wrap: break-word; /* ✅ ensures long words wrap */
        }

        /* ---------- Header ---------- */
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }
        .header h2 {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
            line-height: 1.2;
        }
        .header p {
            font-size: 10px;
            margin: 5px 0;
            line-height: 1.2;
            font-style: italic;
        }

        /* ---------- Student Info ---------- */
        .student-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .student-info .row {
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            white-space: normal; /* ✅ allow line wrapping */
            word-break: break-word;
        }

        /* ---------- Logbook Table ---------- */
        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* ✅ make columns fixed width */
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .logbook-table th, .logbook-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
            vertical-align: top;
            word-wrap: break-word;
            white-space: normal; /* ✅ wraps text */
            word-break: break-word;
        }
        .logbook-table th {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        /* ✅ Adjusted column widths for better proportion */
        .date-col { width: 18%; text-align: center; }
        .activity-col { width: 47%; }
        .comment-col { width: 35%; }

        /* ---------- Reflection Section ---------- */
        .reflection-section {
            border: 1px solid #000;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .reflection-header {
            background-color: #f0f0f0;
            padding: 6px 8px;
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .reflection-content {
            padding: 10px;
            min-height: 80px;
            white-space: pre-wrap; /* ✅ maintain line breaks */
            word-break: break-word;
        }

        /* ---------- Supervisor Section ---------- */
        .supervisor-section {
            border: 1px solid #000;
            margin-bottom: 20px;
            padding: 10px 15px;
            page-break-inside: avoid;
        }
        .supervisor-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signature-space {
            height: 60px;
            border: 1px dashed #999;
            margin-bottom: 10px;
        }

        /* ---------- Footer ---------- */
        .footer-fields {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .footer-field {
            width: 48%;
        }
        .footer-field strong {
            font-weight: bold;
        }
        .footer-field .underline {
            border-bottom: 1px solid #000;
            margin-top: 3px;
            height: 20px;
        }
        .footer-text {
            font-weight: bold;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>STBC4866 Latihan Tempat Kerja Bioinformatik</h1>
        <h2>Templat Buku Log / Student's Logbook Template{{ $entryTypeLabel ?? '' }}</h2>
        <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
        @if(isset($startDate) || isset($endDate))
            <p style="font-size: 10px; margin-top: 5px; color: #666;">
                <strong>Date Range:</strong>
                {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'All dates' }}
                -
                {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Present' }}
            </p>
        @endif
    </div>

    <div class="student-info">
        <div class="row">
            <strong>Nama Pelajar / Name of Student:</strong> {{ $user->name }}
        </div>
        <div class="row">
            <strong>No. Pendaftaran / Matric No.:</strong> {{ $user->matric_no }}
        </div>
        <div class="row">
            <strong>Tempat kerja / Working place:</strong> {{ $user->workplace ?: '' }}
        </div>
    </div>

    <!-- ---------- Main Logbook Table ---------- -->
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
                $allEntries = collect();
                if ($logEntries && $logEntries->count()) {
                    foreach($logEntries as $entry) {
                        $allEntries->push([
                            'date' => $entry->date,
                            'activity' => $entry->activity,
                            'comment' => $entry->comment ?? '',
                        ]);
                    }
                }
                if ($projectEntries && $projectEntries->count()) {
                    foreach($projectEntries as $entry) {
                        $allEntries->push([
                            'date' => $entry->date,
                            'activity' => $entry->activity,
                            'comment' => $entry->comment ?? '',
                        ]);
                    }
                }
                $allEntries = $allEntries->sortBy('date');
            @endphp

            @forelse($allEntries as $entry)
                <tr>
                    <td class="date-col">{{ $entry['date']->format('d/m/Y') }}</td>
                    <td class="activity-col">{{ $entry['activity'] }}</td>
                    <td class="comment-col">{{ $entry['comment'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding:20px; font-style:italic;">No entries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ---------- Weekly Reflection ---------- -->
    <div class="reflection-section">
        <div class="reflection-header">Refleksi Mingguan / Weekly Reflection:</div>
        <div class="reflection-content">
            @php
                $reflections = collect();
                if ($logEntries) {
                    foreach($logEntries->whereNotNull('weekly_reflection_content') as $entry) {
                        $reflections->push($entry->weekly_reflection_content);
                    }
                }
                if ($projectEntries) {
                    foreach($projectEntries->whereNotNull('weekly_reflection_content') as $entry) {
                        $reflections->push($entry->weekly_reflection_content);
                    }
                }
            @endphp
            @if($reflections->count())
                @foreach($reflections->unique() as $reflection)
                    <p>{{ $reflection }}</p>
                @endforeach
            @else
                <p style="color:#666;">&nbsp;</p>
            @endif
        </div>
    </div>

    <!-- ---------- Supervisor Section ---------- -->
    <div class="supervisor-section">
        <div class="supervisor-header">
            Cap dan tandatangan penyelia industri (setiap 3 minggu) /
            Industry supervisor's cap and signature (every 3 weeks):
        </div>
        <div class="signature-space">&nbsp;</div>
        <div class="footer-fields">
            <div class="footer-field">
                <strong>Tarikh / Date:</strong>
                <div class="signature-space">&nbsp;</div>
            </div>
            <div class="footer-field">
                <strong>Komen / Comments:</strong>
                <div class="signature-space">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="footer-text">
        Untuk kegunaan Pelajar UKM.
    </div>
</body>
</html>
