<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Logbook - {{ $user->name }}</title>
    <style>
        /* === PAGE LAYOUT === */
        @page {
            margin: 2.5cm 2cm 2.5cm 2cm; /* Top, Right, Bottom, Left */
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            word-wrap: break-word;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
        }
        .header h2 {
            font-size: 12px;
            font-weight: bold;
            margin: 3px 0;
        }
        .header p {
            font-size: 10px;
            font-style: italic;
            margin: 2px 0;
        }

        /* === STUDENT INFO === */
        .student-info {
            margin-bottom: 15px;
            font-size: 11px;
            line-height: 1.8;
        }
        .info-line {
            margin-bottom: 6px;
        }
        .underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 65%;
            margin-left: 5px;
        }

        /* === TABLE === */
        table.logbook-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 10px;
            margin-bottom: 25px;
        }
        .logbook-table th,
        .logbook-table td {
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

        /* Adjust column widths — matches the official UKM template proportions */
        .date-col {
            width: 15%;
            text-align: center;
        }
        .activity-col {
            width: 50%;
        }
        .comment-col {
            width: 35%;
        }

        /* === REFLECTION & SUPERVISOR SECTIONS === */
        .reflection-section,
        .supervisor-section {
            border: 1px solid #000;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .reflection-header,
        .supervisor-header {
            padding: 6px 8px;
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .reflection-content {
            padding: 12px;
            min-height: 100px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .supervisor-content {
            padding: 12px;
        }

        /* === FOOTER === */
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
        .footer-text {
            font-weight: bold;
            text-align: left;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
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

    <!-- STUDENT INFO -->
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

    <!-- LOGBOOK TABLE -->
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
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    @endfor
                @endforelse
            @elseif($entryType === 'project')
                @forelse($projectEntries->sortBy('date') as $entry)
                    <tr>
                        <td class="date-col">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                        <td class="activity-col">{{ $entry->activity }}</td>
                        <td class="comment-col">{{ $entry->comment ?? '' }}</td>
                    </tr>
                @empty
                    @for($i = 0; $i < 8; $i++)
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    @endfor
                @endforelse
            @endif
        </tbody>
    </table>

    <!-- REFLECTION SECTION -->
    <div class="reflection-section">
        <div class="reflection-header">
            @if($entryType === 'project')
                Cadangan penambahbaikan dan perancangan / Suggestion for improvement and planning for the upcoming week:
            @else
                Refleksi Mingguan / Weekly Reflection:
            @endif
        </div>
        <div class="reflection-content">
            @if(!empty($weeklyReflectionsContents))
                <div style="white-space: pre-wrap; font-size: 12px; line-height: 1.6;">
                    {{ ltrim($weeklyReflectionsContents) }}
                </div>
            @else
                <br><br><br><br><br><br><br>
            @endif
        </div>
    </div>

    @if($includeReflection)
    <!-- SUPERVISOR SECTION -->
    <div class="supervisor-section">
        <div class="supervisor-header">
            Cop dan tandatangan penyelia industri (setiap 3 minggu) /
            Industry supervisor's cop and signature (every 3 weeks):
        </div>
        <div class="supervisor-content">
            @php
                // Get the most recent approved entry with signature
                $signedEntry = null;
                if ($entryType === 'log' || $entryType === 'all') {
                    $signedEntry = $logEntries->where('supervisor_approved', true)
                                              ->whereNotNull('supervisor_signature')
                                              ->sortByDesc('approved_at')
                                              ->first();
                }
                if (!$signedEntry && ($entryType === 'project' || $entryType === 'all')) {
                    $signedEntry = $projectEntries->where('supervisor_approved', true)
                                                  ->whereNotNull('supervisor_signature')
                                                  ->sortByDesc('approved_at')
                                                  ->first();
                }
            @endphp
            
            @if($signedEntry && $signedEntry->supervisor_signature)
                <!-- Display signature -->
                <div style="text-align: center; margin: 20px 0;">
                    <img src="{{ public_path('storage/' . $signedEntry->supervisor_signature) }}" 
                         alt="Supervisor Signature" 
                         style="max-width: 300px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                </div>
                
                <div class="footer-fields">
                    <div class="footer-field">
                        <strong>Tarikh / Date:</strong> 
                        {{ $signedEntry->approved_at ? $signedEntry->approved_at->format('d/m/Y') : '' }}
                    </div>
                    <div class="footer-field">
                        <strong>Komen / Comments:</strong>
                        <div style="margin-top: 5px; min-height: 50px; white-space: pre-wrap;">
                            {{ $signedEntry->supervisor_comment ?? '' }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty signature section if not signed -->
                <br><br><br><br><br>
                <div class="footer-fields">
                    <div class="footer-field">
                        <strong>Tarikh / Date:</strong>
                    </div>
                    <div class="footer-field">
                        <strong>Komen / Comments:</strong>
                        <br><br><br><br><br>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="footer-text">
        Untuk kegunaan Pelajar UKM.
    </div>
    @endif

</body>
</html>
