@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src="{{ auth()->user()->getProfilePictureUrl() }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle" 
                                 width="60" height="60"
                                 style="object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                        <div>
                            <h4 class="mb-0">{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h4>
                            <small class="text-muted">{{ __('Your Personal Dashboard') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif  

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center border-primary">
                                <div class="card-body">
                                    <h3 class="card-title text-primary">{{ $stats['total_entries'] }}</h3>
                                    <p class="card-text">{{ __('Log Entries') }}</p>
                                    <a href="{{ route('log-entries.index') }}" class="btn btn-primary btn-sm">{{ __('View All') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-success">
                                <div class="card-body">
                                    <h3 class="card-title text-success">{{ $stats['total_project_entries'] }}</h3>
                                    <p class="card-text">{{ __('Project Entries') }}</p>
                                    <a href="{{ route('project-entries.index') }}" class="btn btn-success btn-sm">{{ __('View All') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-info">
                                <div class="card-body">
                                    <h3 class="card-title text-info">{{ $stats['total_reflections'] }}</h3>
                                    <p class="card-text">{{ __('Weekly Reflections') }}</p>
                                    <small class="text-muted">{{ __('Integrated with entries') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-warning">
                                <div class="card-body">
                                    <h3 class="card-title text-warning">{{ $stats['approved_entries'] + $stats['approved_project_entries'] + $stats['signed_reflections'] }}</h3>
                                    <p class="card-text">{{ __('Total Approved') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Entry Distribution + Contribution Grid -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(240, 248, 255, 0.95)); backdrop-filter: blur(10px);">
                                <div class="card-header" style="background: linear-gradient(90deg, var(--ukm-blue), var(--ukm-red)); color: white; border: none; border-radius: 15px 15px 0 0;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chart-pie me-2" style="color: var(--ukm-yellow);"></i>
                                        <h5 class="mb-0">{{ __('Entry Distribution') }}</h5>
                                    </div>
                                    <small class="text-light opacity-75">{{ __('Your activity breakdown') }}</small>
                                </div>
                                <div class="card-body text-center">
                                    <div class="chart-container" style="position: relative; height: 350px; margin-bottom: 20px;">
                                        <canvas id="entryChart" width="400" height="400" style="max-width: 100%; max-height: 100%;"></canvas>
                                        <div class="chart-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                        </div>
                                    </div>
                                    <div class="chart-stats d-flex justify-content-around mt-3" style="background: linear-gradient(135deg, rgba(248, 250, 252, 0.9), rgba(255, 255, 255, 0.95)); border-radius: 10px; padding: 15px; margin-top: 10px; box-shadow: none;">
                                        <div class="stat-item text-center">
                                            <div class="stat-number" style="font-size: 1.5rem; font-weight: bold; color: var(--ukm-blue);">
                                                {{ $stats['total_entries'] }}
                                            </div>
                                            <div class="stat-label" style="font-size: 0.8rem; color: #6c757d;">
                                                {{ __('Log Entries') }}
                                            </div>
                                        </div>
                                        <div class="stat-item text-center">
                                            <div class="stat-number" style="font-size: 1.5rem; font-weight: bold; color: var(--ukm-red);">
                                                {{ $stats['total_project_entries'] }}
                                            </div>
                                            <div class="stat-label" style="font-size: 0.8rem; color: #6c757d;">
                                                {{ __('Project Entries') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="contrib-card" style="border-radius:15px; background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(245,249,255,0.95)); padding:16px; height:100%; box-shadow: 0 8px 20px rgba(0,0,0,0.06); backdrop-filter: blur(6px);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="mb-0">{{ __('Daily Activity') }}</h6>
                                        <small class="text-muted">{{ __('Last 30 days') }}</small>
                                    </div>
                                    <div class="text-muted">{{ __('Total:') }} {{ array_sum($dailyCounts->toArray()) }}</div>
                                </div>
                                <div style="margin-top:8px;">
                                    <canvas id="dailyLineChart" height="140" style="width:100%; max-width:100%;"></canvas>
                                </div>
                                <div class="contrib-legend" style="display:flex; gap:8px; align-items:center; justify-content:center; margin-top:8px; color:#6c757d; font-size:0.9rem;">
                                    <span>{{ __('Less') }}</span>
                                    <span class="box contrib-0" style="width:14px;height:14px;border-radius:3px;display:inline-block;background:#ebedf0;"></span>
                                    <span class="box contrib-1" style="width:14px;height:14px;border-radius:3px;display:inline-block;background:rgba(27,54,93,0.12);"></span>
                                    <span class="box contrib-2" style="width:14px;height:14px;border-radius:3px;display:inline-block;background:rgba(27,54,93,0.25);"></span>
                                    <span class="box contrib-3" style="width:14px;height:14px;border-radius:3px;display:inline-block;background:rgba(27,54,93,0.45);"></span>
                                    <span class="box contrib-4" style="width:14px;height:14px;border-radius:3px;display:inline-block;background:rgba(27,54,93,0.7);"></span>
                                    <span>{{ __('More') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Contribution grid is server-rendered as 52 weekly columns. --}}

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Quick Actions') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('log-entries.create') }}" class="btn btn-outline-primary">{{ __('Add Log Entry') }}</a>
                                        <a href="{{ route('project-entries.create') }}" class="btn btn-outline-success">{{ __('Add Project Entry') }}</a>
                                        @if(auth()->user()->isStudent())
                                            <a href="{{ route('users.showExport') }}" class="btn btn-outline-danger">{{ __('Export PDF') }}</a>
                                        @endif
                                        <a href="{{ route('users.profile') }}" class="btn btn-outline-secondary">{{ __('Edit Profile') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity: Log and Project Entries side-by-side -->
                    @if($recentEntries->count() > 0 || $recentProjectEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            @if($recentEntries->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Recent Log Entries') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Activity') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentEntries as $entry)
                                                <tr>
                                                    <td>{{ $entry->date->format('M d') }}</td>
                                                    <td>{{ Str::limit($entry->activity, 40) }}</td>
                                                    <td>
                                                        @if($entry->supervisor_approved)
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-body text-center text-muted py-4">{{ __('No recent log entries') }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            @if($recentProjectEntries->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Recent Project Entries') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Activity') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentProjectEntries as $projectEntry)
                                                <tr>
                                                    <td>{{ $projectEntry->date->format('M d') }}</td>
                                                    <td>{{ Str::limit($projectEntry->activity, 40) }}</td>
                                                    <td>
                                                        @if($projectEntry->supervisor_approved)
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-body text-center text-muted py-4">{{ __('No recent project entries') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($recentReflections->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Recent Weekly Reflections') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Content') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentReflections as $reflection)
                                                <tr>
                                                    <td>
                                                        @if($reflection->type === 'log')
                                                            <span class="badge bg-primary">Log Entry</span>
                                                        @else
                                                            <span class="badge bg-success">Project</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ Str::limit($reflection->content, 40) }}
                                                        @if($reflection->type === 'project' && $reflection->activity)
                                                            <br><small class="text-muted">{{ $reflection->activity }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($reflection->signed)
                                                            <span class="badge bg-success">Signed</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($recentEntries->count() == 0 && $recentProjectEntries->count() == 0 && $recentReflections->count() == 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="alert alert-info">
                                    <i class="fas fa-rocket fa-3x text-info mb-3"></i>
                                    <h5>{{ __('Get Started!') }}</h5>
                                    <p class="mb-4">{{ __('Welcome to your logbook system. Start by creating your first log entry, project entry, or weekly reflection.') }}</p>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <a href="{{ route('log-entries.create') }}" class="btn btn-primary">{{ __('Create Log Entry') }}</a>
                                        <a href="{{ route('project-entries.create') }}" class="btn btn-success">{{ __('Create Project Entry') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Enhanced Chart Styling */
.chart-container {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.99), rgba(248, 250, 252, 0.97));
}

.chart-stats {
    border-radius: 10px;
    padding: 15px;
    margin-top: 10px;
    box-shadow: none;
}

.stat-item {
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.stat-number {
    transition: all 0.18s ease;
}

.stat-number:hover {
    transform: scale(1.03);
}

/* Chart animation enhancements */
/* Removed hover glow for cleaner chart UI */
.chart-container:hover {
    animation: none !important;
}

/* Custom legend styling */
.chartjs-legend {
    background: transparent !important;
    border-radius: 8px !important;
    padding: 8px !important;
    box-shadow: none !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-container {
        height: 300px !important;
    }

    .total-entries {
        font-size: 1.25rem !important;
    }

    .stat-number {
        font-size: 1.1rem !important;
    }
}
</style>

<style>
/* Contribution grid styling to match chart card */
.contrib-card {
    border-radius: 15px;
}
.contrib-columns {
    display: flex;
    gap: 6px;
    align-items: flex-start;
    padding: 6px 4px;
}
.week-col {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.contrib-cell {
    width: 14px;
    height: 14px;
    border-radius: 3px;
    transition: transform 120ms ease, box-shadow 120ms ease;
}
.contrib-cell:hover {
    transform: scale(1.08);
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
.contrib-legend .box { box-shadow: none; }

@media (max-width: 768px) {
    .contrib-columns { overflow-x: auto; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    const canvas = document.getElementById('entryChart');
    if (!canvas) {
        console.error('Canvas element not found');
        return;
    }
    const ctx = canvas.getContext('2d');

    console.log('Chart.js loaded:', typeof Chart);
    console.log('Canvas element:', ctx);
    console.log('Chart data:', [{{ $stats['total_entries'] }}, {{ $stats['total_project_entries'] }}]);
    
    // Check if we have valid data
    const chartData = [{{ $stats['total_entries'] }}, {{ $stats['total_project_entries'] }}];
    if (chartData.every(val => val === 0)) {
        console.warn('All chart data values are 0, chart may appear empty');
    }

    const entryChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Log Entries") }}', '{{ __("Project Entries") }}'],
            datasets: [{
                data: [{{ $stats['total_entries'] }}, {{ $stats['total_project_entries'] }}],
                backgroundColor: [
                    'rgba(27,54,93,0.86)',
                    'rgba(228,27,19,0.86)'
                ],
                borderColor: [
                    '#ffffff',
                    '#ffffff'
                ],
                borderWidth: 2,
                hoverBorderWidth: 3,
                hoverBorderColor: ['rgba(255,209,0,0.95)','rgba(255,209,0,0.95)'],
                hoverBackgroundColor: ['rgba(27,70,110,0.95)','rgba(255,60,30,0.95)']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold',
                            family: 'Nunito, sans-serif'
                        },
                        color: '#495057',
                        generateLabels: function(chart) {
                            const data = chart.data;
                            return data.labels.map((label, i) => ({
                                text: label,
                                fillStyle: i === 0 ? '#1B365D' : '#E41B13',
                                strokeStyle: i === 0 ? '#1B365D' : '#E41B13',
                                lineWidth: 2,
                                hidden: false,
                                index: i
                            }));
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(27, 54, 93, 0.9)',
                    titleColor: '#FFD100',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyColor: '#ffffff',
                    bodyFont: {
                        size: 13
                    },
                    borderColor: '#FFD100',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return [
                                'Count: ' + value,
                                'Percentage: ' + percentage + '%'
                            ];
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 900,
                easing: 'easeOutCubic'
            },
            hover: {
                mode: 'nearest',
                intersect: true
            }
        },
        plugins: [{
            id: 'customCenterText',
            beforeDraw: function(chart) {
                const { ctx, chartArea: { left, right, top, bottom, width, height } } = chart;
                ctx.save();

                    // reduced glow for cleaner look
                    ctx.shadowColor = 'rgba(27,54,93,0.06)';
                    ctx.shadowBlur = 4;

                    // Draw center text
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.font = '600 20px Nunito, sans-serif';
                    ctx.fillStyle = '#1B365D';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(total.toString(), width / 2, height / 2 - 4);

                    ctx.font = '12px Nunito, sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('{{ __("Total") }}', width / 2, height / 2 + 16);

                ctx.restore();
            }
        }]
    });
    
    console.log('Chart created successfully:', entryChart);

    // --- Daily line chart (last 30 days) ---
    try {
        const dailyCounts = @json($dailyCounts->toArray());
        const dlLabels = Object.keys(dailyCounts);
        const dlValues = Object.values(dailyCounts);

        const dailyCanvas = document.getElementById('dailyLineChart');
        if (dailyCanvas) {
            const dlCtx = dailyCanvas.getContext('2d');
            const gradient = dlCtx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(27,54,93,0.12)');
            gradient.addColorStop(1, 'rgba(27,54,93,0.02)');

            const dailyChart = new Chart(dailyCanvas, {
                type: 'line',
                data: {
                    labels: dlLabels,
                    datasets: [{
                        label: '{{ __('Entries per day') }}',
                        data: dlValues,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: 'rgba(27,54,93,0.75)',
                        pointBackgroundColor: 'rgba(27,54,93,0.85)',
                        tension: 0.28,
                        pointRadius: 2,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8, color: '#7a8594', font: { size: 11 } },
                            grid: { color: 'rgba(15,23,42,0.03)' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#7a8594' },
                            grid: { color: 'rgba(15,23,42,0.04)' }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,0.9)',
                            titleColor: '#FFD100',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(ctx) { return ctx.parsed.y + ' {{ __('entries') }}'; }
                            }
                        }
                    }
                }
            });

            // Polling to refresh the daily counts every 20s and update chart if changed
            let lastSnapshot = JSON.stringify(dlValues);
            const pollUrl = '{{ route('home.dailyCounts') }}';
            setInterval(async () => {
                try {
                    const res = await fetch(pollUrl, { credentials: 'same-origin' });
                    if (!res.ok) return;
                    const json = await res.json();
                    const newVals = Object.values(json);
                    const newSnapshot = JSON.stringify(newVals);
                    if (newSnapshot !== lastSnapshot) {
                        dailyChart.data.labels = Object.keys(json);
                        dailyChart.data.datasets[0].data = newVals;
                        dailyChart.update();
                        lastSnapshot = newSnapshot;
                    }
                } catch (e) {
                    console.debug('Polling error', e);
                }
            }, 20000);
        }
    } catch (e) { console.error('Daily chart init error', e); }
});
</script>

