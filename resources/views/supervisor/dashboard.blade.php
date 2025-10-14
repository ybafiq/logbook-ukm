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
                            <h4 class="mb-0">{{ __('Supervisor Dashboard') }}</h4>
                            <small class="text-muted">{{ __('Welcome, :name', ['name' => auth()->user()->name]) }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center border-warning">
                                <div class="card-body">
                                    <h3 class="card-title text-warning">{{ $stats['pending_entries'] }}</h3>
                                    <p class="card-text mb-3">{{ __('Pending Log Entries') }}</p>
                                    <a href="{{ route('supervisor.pendingEntries') }}" class="btn btn-warning btn-sm">{{ __('Review') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center border-success">
                                <div class="card-body">
                                    <h3 class="card-title text-success">{{ $stats['pending_project_entries'] }}</h3>
                                    <p class="card-text mb-3">{{ __('Pending Projects') }}</p>
                                    <a href="{{ route('supervisor.pendingProjectEntries') }}" class="btn btn-success btn-sm">{{ __('Review') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center border-primary">
                                <div class="card-body">
                                    <h3 class="card-title text-primary">{{ $stats['total_students'] }}</h3>
                                    <p class="card-text mb-3">{{ __('Total Students') }}</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">{{ __('View All') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Pending Entries -->
                    @if($pendingEntries->count() > 0 || $pendingProjectEntries->count() > 0)
                    <div class="row mb-4">
                        @if($pendingEntries->count() > 0)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Recent Pending Log Entries') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Student') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Activity') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendingEntries->take(5) as $entry)
                                                <tr>
                                                    <td>{{ $entry->student->name }}</td>
                                                    <td>{{ $entry->date->format('M d') }}</td>
                                                    <td>{{ Str::limit($entry->activity, 40) }}</td>
                                                    <td>
                                                        <form action="{{ route('supervisor.approveEntry', $entry) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('{{ __('Approve this entry?') }}')">
                                                                {{ __('Approve') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ __('No Pending Log Entries') }}</h6>
                                    <p class="card-text text-muted">{{ __('All log entries are up to date!') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($pendingProjectEntries->count() > 0)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Recent Pending Project Entries') }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Student') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Activity') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendingProjectEntries->take(5) as $entry)
                                                <tr>
                                                    <td>{{ $entry->student->name }}</td>
                                                    <td>{{ $entry->date->format('M d') }}</td>
                                                    <td>{{ Str::limit($entry->activity, 40) }}</td>
                                                    <td>
                                                        <form action="{{ route('supervisor.approveProjectEntry', $entry) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('{{ __('Approve this project entry?') }}')">
                                                                {{ __('Approve') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ __('No Pending Project Entries') }}</h6>
                                    <p class="card-text text-muted">{{ __('All project entries are up to date!') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>{{ __('All Caught Up!') }}</h5>
                                    <p class="mb-0">{{ __('There are no pending items requiring your attention at this time.') }}</p>
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