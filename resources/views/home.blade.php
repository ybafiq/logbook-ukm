@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Welcome, :name!', ['name' => auth()->user()->name]) }}</h4>
                    <small class="text-muted">{{ __('Your Personal Dashboard') }}</small>
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
                                    <a href="{{ route('reflections.index') }}" class="btn btn-info btn-sm">{{ __('View All') }}</a>
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
                                        <a href="{{ route('reflections.create') }}" class="btn btn-outline-info">{{ __('Add Weekly Reflection') }}</a>
                                        <a href="{{ route('users.profile') }}" class="btn btn-outline-secondary">{{ __('Edit Profile') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if($recentEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    @endif

                    @if($recentProjectEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
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
                                                    <th>{{ __('Week Start') }}</th>
                                                    <th>{{ __('Content') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentReflections as $reflection)
                                                <tr>
                                                    <td>{{ $reflection->week_start->format('M d') }}</td>
                                                    <td>{{ Str::limit($reflection->content, 40) }}</td>
                                                    <td>
                                                        @if($reflection->supervisor_signed)
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
