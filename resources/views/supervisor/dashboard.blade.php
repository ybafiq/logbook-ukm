@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Supervisor Dashboard') }}</h4>
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
                                    <p class="card-text">{{ __('Pending Log Entries') }}</p>
                                    <a href="{{ route('supervisor.pendingEntries') }}" class="btn btn-warning btn-sm">{{ __('Review') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-success">
                                <div class="card-body">
                                    <h3 class="card-title text-success">{{ $stats['pending_project_entries'] }}</h3>
                                    <p class="card-text">{{ __('Pending Projects') }}</p>
                                    <a href="#" class="btn btn-success btn-sm">{{ __('Review') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-info">
                                <div class="card-body">
                                    <h3 class="card-title text-info">{{ $stats['pending_reflections'] }}</h3>
                                    <p class="card-text">{{ __('Pending Reflections') }}</p>
                                    <a href="{{ route('supervisor.pendingReflections') }}" class="btn btn-info btn-sm">{{ __('Review') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-primary">
                                <div class="card-body">
                                    <h3 class="card-title text-primary">{{ $stats['total_students'] }}</h3>
                                    <p class="card-text">{{ __('Total Students') }}</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">{{ __('View All') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Pending Entries -->
                    @if($pendingEntries->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Recent Pending Log Entries') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
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
                    @endif

                    <!-- Recent Pending Reflections -->
                    @if($pendingReflections->count() > 0)
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('Recent Pending Weekly Reflections') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Student') }}</th>
                                            <th>{{ __('Week Start') }}</th>
                                            <th>{{ __('Content') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingReflections->take(5) as $reflection)
                                        <tr>
                                            <td>{{ $reflection->student->name }}</td>
                                            <td>{{ $reflection->week_start->format('M d') }}</td>
                                            <td>{{ Str::limit($reflection->content, 40) }}</td>
                                            <td>
                                                <a href="{{ route('reflections.show', $reflection) }}" class="btn btn-primary btn-sm">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingEntries->count() == 0 && $pendingReflections->count() == 0)
                    <div class="text-center py-4">
                        <div class="alert alert-success">
                            <h5>{{ __('All Caught Up!') }}</h5>
                            <p>{{ __('There are no pending items requiring your attention at this time.') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection