@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('User Profile: :name', ['name' => $user->name]) }}</span>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to Users') }}</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('Personal Information') }}</h5>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Name') }}:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $user->name }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Matric No') }}:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $user->matric_no }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Email') }}:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Role') }}:</label>
                                <div class="col-sm-8">
                                    @if($user->role == 'supervisor')
                                        <span class="badge bg-primary fs-6">{{ ucfirst($user->role) }}</span>
                                    @elseif($user->role == 'admin')
                                        <span class="badge bg-danger fs-6">{{ ucfirst($user->role) }}</span>
                                    @else
                                        <span class="badge bg-secondary fs-6">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Workplace') }}:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $user->workplace ?: 'Not specified' }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label fw-bold">{{ __('Joined') }}:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $user->created_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>{{ __('Activity Summary') }}</h5>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h3 class="card-title text-primary">{{ $user->log_entries_count }}</h3>
                                            <p class="card-text">{{ __('Log Entries') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h3 class="card-title text-success">{{ $user->project_entries_count }}</h3>
                                        <p class="card-text">{{ __('Project Entries') }}</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($recentEntries->count() > 0)
                    <div class="mt-4">
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
                                        <td>{{ $entry->date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($entry->activity, 50) }}</td>
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
                    @endif
                    
                    @if($recentProjectEntries->count() > 0)
                    <div class="mt-4">
                        <h5>{{ __('Recent Project Entries') }}</h5>
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
                                    @foreach($recentProjectEntries as $projectEntry)
                                    <tr>
                                        <td>{{ $projectEntry->date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($projectEntry->activity, 50) }}</td>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection