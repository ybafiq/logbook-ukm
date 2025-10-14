<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Logbook - {{ $user->name }}</title>
    <style>
        @page { margin: 25px 20px; }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            word-wrap: break-word;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1, .header h2 {
            margin: 0;
            line-height: 1.3;
        }
        .header h1 {
            font-size: 13px;
            font-weight: bold;
        }
        .header h2 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }
        .header p {
            font-size: 10px;
            font-style: italic;
            margin: 3px 0;
        }

        .student-info {
            margin-bottom: 15px;
        }
        .student-info .row {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }

        table.logbook-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .logbook-table th, .logbook-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            font-size: 11px;
            word-break: break-word;
            white-space: normal;
        }
        .logbook-table th {
            text-align: center;
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .date-col { width: 18%; text-align: center; }
        .activity-col { width: 47%; }
        .comment-col { width: 35%; }

        .reflection-section, .supervisor-section {
            border: 1px solid #000;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .reflection-header, .supervisor-header {
            background-color: #f0f0f0;
            padding: 6px 8px;
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .reflection-content {
            padding: 10px;
            min-height: 100px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .supervisor-content {
            padding: 10px;
        }
        .signature-space {
            height: 60px;
            border: 1px dashed #999;
            margin: 10px 0;
        }
        .footer-fields {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .footer-field {
            width: 48%;
        }
        .footer-field strong {
            display: block;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .footer-field .underline {
            border-bottom: 1px solid #000;
            height: 20px;
        }
        .footer-text {
            font-weight: bold;
            text-align: left;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>STBC4866 Latihan Tempat Kerja Bioinformatik</h1>
        <h2>Templat Buku Log / Student’s Logbook Template</h2>
        <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <div class="row">Nama Pelajar / Name of Student: {{ $user->name }}</div>
        <div class="row">No. Pendaftaran / Matric No.: {{ $user->matric_no }}</div>
        <div class="row">Tempat kerja / Working place: {{ $user->workplace }}</div>
    </div>

    <!-- Logbook Table -->
    <table class="logbook-table">
        <thead>
            <tr>
                <th class="date-col">Tarikh / Date</th>
                <th class="activity-col">Aktiviti / Activity</th>
                <th class="comment-col">Komen / Comment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logEntries as $entry)
                <tr>
                    <td class="date-col">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                    <td class="activity-col">{{ $entry->activity }}</td>
                    <td class="comment-col">{{ $entry->comment ?? '' }}</td>
                </tr>
            @empty
                @for($i = 0; $i < 8; $i++)
                <tr>
                    <td class="date-col">&nbsp;</td>
                    <td class="activity-col">&nbsp;</td>
                    <td class="comment-col">&nbsp;</td>
                </tr>
                @endfor
            @endforelse
        </tbody>
    </table>

    <!-- Weekly Reflection -->
    <div class="reflection-section">
        <div class="reflection-header">Refleksi Mingguan / Weekly Reflection:</div>
        <div class="reflection-content">
            @if(isset($weeklyReflection) && $weeklyReflection->content)
                <p>{{ $weeklyReflection->content }}</p>
            @else
                <br><br><br><br><br><br><br>
            @endif
        </div>
    </div>

    <!-- Supervisor Section -->
    <div class="supervisor-section">
        <div class="supervisor-header">
            Cop dan tandatangan penyelia industri (setiap 3 minggu) /
            Industry supervisor’s cop and signature (every 3 weeks):
        </div>
        <div class="supervisor-content">
            <div class="signature-space">&nbsp;</div>
            <div class="footer-fields">
                <div class="footer-field">
                    <strong>Tarikh / Date:</strong>
                    
                </div>
                <div class="footer-field">
                    <strong>Komen / Comments:</strong>
                    <div class="signature-space">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-text">
        Untuk kegunaan Pelajar UKM.
    </div>

</body>
</html>
