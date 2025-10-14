<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Logbook - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }
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
        .student-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .student-info .row {
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .logbook-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            background-color: #f0f0f0;
        }
        .logbook-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            font-size: 11px;
            height: 30px;
        }
        .date-col { width: 15%; text-align: center; }
        .activity-col { width: 45%; }
        .comment-col { width: 40%; }
        .reflection-section {
            border: 1px solid #000;
            margin-bottom: 20px;
        }
        .reflection-header {
            background-color: #f0f0f0;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .reflection-content {
            padding: 15px;
            min-height: 100px;
        }
        .supervisor-section {
            border: 1px solid #000;
            margin-bottom: 20px;
            padding: 15px;
        }
        .supervisor-header {
            font-weight: bold;
            margin-bottom: 15px;
        }
        .signature-space {
            height: 80px;
            margin-bottom: 15px;
        }
        .footer-fields {
            display: flex;
            justify-content: space-between;
        }
        .footer-field {
            width: 48%;
        }
        .footer-field strong {
            font-weight: bold;
        }
        .footer-field .underline {
            border-bottom: 1px solid #000;
            margin-top: 5px;
            height: 20px;
        }
        .footer-text {
            font-weight: bold;
            margin-top: 20px;
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
                {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'All dates' }} - 
                {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Present' }}
            </p>
        @endif
    </div>

    <div class="student-info">
        <div class="row">
            Nama Pelajar/Name of Student: {{ $user->name }}
        </div>
        <div class="row">
            No. Pendaftaran/Matric No.: {{ $user->matric_no }}
        </div>
        <div class="row">
            Tempat kerja/Working place: {{ $user->workplace ?: '' }}
        </div>
    </div>

    <!-- Main Logbook Table -->
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
                // Combine and sort all entries by date
                $allEntries = collect();
                
                // Add log entries if they exist
                if ($logEntries && $logEntries->count() > 0) {
                    foreach($logEntries as $entry) {
                        $allEntries->push([
                            'date' => $entry->date,
                            'activity' => $entry->activity,
                            'comment' => $entry->comment ?: '',
                            'type' => 'log'
                        ]);
                    }
                }
                
                // Add project entries if they exist
                if ($projectEntries && $projectEntries->count() > 0) {
                    foreach($projectEntries as $entry) {
                        $allEntries->push([
                            'date' => $entry->date,
                            'activity' => $entry->activity,
                            'comment' => $entry->comment ?: '',
                            'type' => 'project'
                        ]);
                    }
                }
                
                // Sort by date
                $allEntries = $allEntries->sortBy('date');
                $totalEntries = $allEntries->count();
                
                // Determine entry type label for display
                $entryTypeLabel = '';
                if (isset($entryType)) {
                    switch($entryType) {
                        case 'log':
                            $entryTypeLabel = ' (Log Entries Only)';
                            break;
                        case 'project':
                            $entryTypeLabel = ' (Project Entries Only)';
                            break;
                        case 'all':
                        default:
                            $entryTypeLabel = ' (All Entries)';
                            break;
                    }
                }
            @endphp
            
            @if($totalEntries > 0)
                @foreach($allEntries as $entry)
                <tr>
                    <td class="date-col">{{ $entry['date']->format('d/m/Y') }}</td>
                    <td class="activity-col">{{ $entry['activity'] }}</td>
                    <td class="comment-col">{{ $entry['comment'] }}</td>
                </tr>
                @endforeach
                
                @php
                    // Fill remaining rows to make consistent format (minimum 8 rows)
                    $remainingRows = max(0, 8 - $totalEntries);
                @endphp
                
                @for($i = 0; $i < $remainingRows; $i++)
                <tr>
                    <td class="date-col">&nbsp;</td>
                    <td class="activity-col">&nbsp;</td>
                    <td class="comment-col">&nbsp;</td>
                </tr>
                @endfor
            @else
                <!-- Show message when no entries found due to filtering -->
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px; font-style: italic; color: #666;">
                        @if(isset($entryType) && $entryType !== 'all')
                            No {{ $entryType === 'log' ? 'log' : 'project' }} entries found for the selected criteria.
                        @else
                            No entries found for the selected date range.
                        @endif
                    </td>
                </tr>
                @for($i = 0; $i < 7; $i++)
                <tr>
                    <td class="date-col">&nbsp;</td>
                    <td class="activity-col">&nbsp;</td>
                    <td class="comment-col">&nbsp;</td>
                </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <!-- Weekly Reflection Section -->
    <div class="reflection-section">
        <div class="reflection-header">
            Refleksi Mingguan / Weekly reflection:
        </div>
        <div class="reflection-content">
            @php
                $reflections = collect();
                // Add log entry reflections if log entries exist
                if ($logEntries && $logEntries->count() > 0) {
                    foreach($logEntries->whereNotNull('weekly_reflection_content') as $entry) {
                        $reflections->push($entry->weekly_reflection_content);
                    }
                }
                // Add project entry reflections if project entries exist
                if ($projectEntries && $projectEntries->count() > 0) {
                    foreach($projectEntries->whereNotNull('weekly_reflection_content') as $entry) {
                        $reflections->push($entry->weekly_reflection_content);
                    }
                }
            @endphp
            
            @if($reflections->count() > 0)
                @foreach($reflections->unique() as $reflection)
                    <p style="margin-bottom: 10px; line-height: 1.4;">{{ $reflection }}</p>
                @endforeach
            @else
                <!-- Empty space for manual completion -->
                &nbsp;
            @endif
        </div>
    </div>

    <!-- Supervisor Section -->
    <div class="supervisor-section">
        <div class="supervisor-header">
            Cap dan tandatangan penyelia industri (setiap 3 minggu) / Industry supervisor's cap and signature (every 3 weeks):
        </div>
        <div class="signature-space">
            <!-- Empty space for signature -->
            &nbsp;
        </div>
        <div class="footer-fields">
            <div class="footer-field">
                <strong>Tarikh / Date:</strong>
                <div class="underline"></div>
            </div>
            <div class="footer-field">
                <strong>Komen / Comments:</strong>
                <div class="underline"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-text">
        Untuk kegunaan Pelajar UKM.
    </div>

</body>
</html>