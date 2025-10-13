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
                            <h5>{{ __('Quick Actions') }}</h5>
                            <div class="btn-group" role="group">
                                <a href="{{ route('log-entries.create') }}" class="btn btn-outline-primary">{{ __('Add Log Entry') }}</a>
                                <a href="{{ route('project-entries.create') }}" class="btn btn-outline-success">{{ __('Add Project Entry') }}</a>
                                <a href="{{ route('reflections.create') }}" class="btn btn-outline-info">{{ __('Add Weekly Reflection') }}</a>
                                <a href="{{ route('users.profile') }}" class="btn btn-outline-secondary">{{ __('Edit Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @if($recentEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Recent Log Entries') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
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
                    @endif

                    @if($recentProjectEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Recent Project Entries') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Project Title') }}</th>
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
                    @endif

                    @if($recentReflections->count() > 0)
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('Recent Weekly Reflections') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
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
                    @endif

                    @if($recentEntries->count() == 0 && $recentProjectEntries->count() == 0 && $recentReflections->count() == 0)
                    <div class="text-center py-4">
                        <div class="alert alert-info">
                            <h5>{{ __('Get Started!') }}</h5>
                            <p>{{ __('Welcome to your logbook system. Start by creating your first log entry, project entry, or weekly reflection.') }}</p>
                            <div class="btn-group" role="group">
                                <a href="{{ route('log-entries.create') }}" class="btn btn-primary">{{ __('Create Log Entry') }}</a>
                                <a href="{{ route('project-entries.create') }}" class="btn btn-success">{{ __('Create Project Entry') }}</a>
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
