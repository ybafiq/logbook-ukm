<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Logbook Export - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0;
            color: #7f8c8d;
            font-size: 16px;
            font-weight: normal;
        }
        .student-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 5px;
            border: none;
        }
        .student-info .label {
            font-weight: bold;
            width: 120px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #bdc3c7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
            vertical-align: top;
        }
        .date-col { width: 80px; }
        .status-col { width: 70px; }
        .approved-col { width: 90px; }
        .activity-col { width: auto; }
        .comment-col { width: 120px; }
        .status-approved {
            color: #27ae60;
            font-weight: bold;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-signed {
            color: #27ae60;
            font-weight: bold;
        }
        .no-entries {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
        .break-page {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LOGBOOK REPORT</h1>
        <h2>{{ $user->name }} - {{ $user->matric_no }}</h2>
        @if($startDate || $endDate)
            <p>
                Period: 
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
                @elseif($startDate)
                    From {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }}
                @elseif($endDate)
                    Until {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
                @endif
            </p>
        @else
            <p>Complete Logbook</p>
        @endif
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td class="label">Student Name:</td>
                <td>{{ $user->name }}</td>
                <td class="label">Matric No:</td>
                <td>{{ $user->matric_no }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $user->email }}</td>
                <td class="label">Workplace:</td>
                <td>{{ $user->workplace ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Generated At:</td>
                <td colspan="3">{{ $generatedAt }}</td>
            </tr>
        </table>
    </div>

    <!-- Log Entries Section -->
    <div class="section">
        <h3>Log Entries ({{ $logEntries->count() }} entries)</h3>
        @if($logEntries->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th class="date-col">Date</th>
                        <th class="activity-col">Activity</th>
                        <th class="comment-col">Comment</th>
                        <th class="status-col">Status</th>
                        <th class="approved-col">Approved By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logEntries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('M d, Y') }}</td>
                        <td>{{ $entry->activity }}</td>
                        <td>{{ $entry->comment ?: 'N/A' }}</td>
                        <td class="{{ $entry->supervisor_approved ? 'status-approved' : 'status-pending' }}">
                            {{ $entry->supervisor_approved ? 'Approved' : 'Pending' }}
                        </td>
                        <td>{{ $entry->approver->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-entries">No log entries found for the selected period.</div>
        @endif
    </div>

    <!-- Project Entries Section -->
    <div class="section">
        <h3>Project Entries ({{ $projectEntries->count() }} entries)</h3>
        @if($projectEntries->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th class="date-col">Date</th>
                        <th class="activity-col">Activity</th>
                        <th class="comment-col">Comment</th>
                        <th class="status-col">Status</th>
                        <th class="approved-col">Approved By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectEntries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('M d, Y') }}</td>
                        <td>{{ $entry->activity }}</td>
                        <td>{{ $entry->comment ?: 'N/A' }}</td>
                        <td class="{{ $entry->supervisor_approved ? 'status-approved' : 'status-pending' }}">
                            {{ $entry->supervisor_approved ? 'Approved' : 'Pending' }}
                        </td>
                        <td>{{ $entry->approver->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-entries">No project entries found for the selected period.</div>
        @endif
    </div>

    <!-- Weekly Reflections Section -->
    @if($weeklyReflections->count() > 0)
    <div class="section break-page">
        <h3>Weekly Reflections ({{ $weeklyReflections->count() }} reflections)</h3>
        @foreach($weeklyReflections as $reflection)
        <div style="margin-bottom: 20px; page-break-inside: avoid;">
            <table style="margin-bottom: 10px;">
                <tr>
                    <td class="label" style="background-color: #f8f9fa; font-weight: bold; width: 100px;">Week Start:</td>
                    <td style="background-color: #f8f9fa;">{{ $reflection->week_start->format('F d, Y') }}</td>
                    <td class="label" style="background-color: #f8f9fa; font-weight: bold; width: 80px;">Status:</td>
                    <td style="background-color: #f8f9fa;" class="{{ $reflection->supervisor_signed ? 'status-signed' : 'status-pending' }}">
                        {{ $reflection->supervisor_signed ? 'Signed' : 'Pending' }}
                    </td>
                </tr>
            </table>
            <div style="border: 1px solid #ddd; padding: 10px; background-color: #fdfdfd;">
                <strong>Content:</strong><br>
                <div style="white-space: pre-wrap; margin-top: 5px;">{{ $reflection->content }}</div>
            </div>
            @if($reflection->supervisor_signed && $reflection->signer)
            <p style="margin-top: 5px; font-size: 10px; color: #666;">
                Signed by: {{ $reflection->signer->name }} on {{ $reflection->signed_at->format('F d, Y g:i A') }}
            </p>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p>This document was generated automatically from the UKM Logbook System on {{ $generatedAt }}</p>
        <p>For verification purposes, please contact the system administrator.</p>
    </div>
</body>
</html>