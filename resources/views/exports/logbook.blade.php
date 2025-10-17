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
        font-size: 11px;
        line-height: 1.8;
        }

        .student-info .info-line {
            margin-bottom: 8px;
        }

        .student-info .underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 65%; /* adjust width as needed */
            margin-left: 5px;
        }

        table.logbook-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .logbook-table th, .logbook-table td {
            border: 0.5px solid #000;
            padding: 6px;
            vertical-align: top;
            font-size: 11px;
            word-break: break-word;
            white-space: normal;
        }
        .logbook-table th {
            text-align: center;
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
        @if($entryType === 'project')
            <h1>STBC4966 Bioinformatik Dalam Industri</h1>
            <h2>Templat Buku Log / Student’s Logbook Template</h2>
            <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
        @elseif($entryType === 'log')
            <h1>STBC4866 Latihan Tempat Kerja Bioinformatik</h1>
            <h2>Templat Buku Log / Student’s Logbook Template</h2>
            <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
        @else
            <h1>STBC4866 & STBC4966 Logbook dan Projek Bioinformatik</h1>
            <h2>Templat Gabungan Buku Log & Projek / Combined Log & Project Template</h2>
            <p>(Untuk disemak oleh penyelia industri pada setiap tiga minggu / To be checked by industrial supervisor every 3 weeks)</p>
        @endif
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <div class="info-line">
            Nama Pelajar / <i>Name of Student:</i>
            <span class="underline">{{ $user->name ?? ' ' }}</span>
        </div>
        <div class="info-line">
            No. Pendaftaran / <i>Matric No.:</i>
            <span class="underline">{{ $user->matric_no ?? ' ' }}</span>
        </div>
        <div class="info-line">
            Tempat kerja / <i>Working place:</i>
            <span class="underline">{{ $user->workplace ?? ' ' }}</span>
        </div>
</div>
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
            @if($entryType === 'log')
                @forelse($logEntries->sortBy('date') as $entry)
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
            @else($entryType === 'project')
                @forelse($projectEntries->sortBy('date') as $entry)
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
            @endif
        </tbody>
    </table>

    <!-- Weekly Reflection -->
    <div class="reflection-section">
        <div class="reflection-header">Refleksi Mingguan / Weekly Reflection:</div>
        <div class="reflection-content">
        @if(!empty($weeklyReflectionsContents))
            <div style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;">
                {{ ltrim ($weeklyReflectionsContents) }}
            </div>
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
