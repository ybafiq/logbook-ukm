@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Deleted Users') }} ({{ $users->total() }} {{ Str::plural('user', $users->total()) }})</span>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">{{ __('Back to Users') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ __('These users have been soft deleted. You can restore them or permanently delete them.') }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Matric No') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Workplace') }}</th>
                                        <th>{{ __('Deleted At') }}</th>
                                        <th>{{ __('Log Entries') }}</th>
                                        <th>{{ __('Project Entries') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->matric_no }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->role == 'supervisor')
                                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                                @elseif($user->role == 'admin')
                                                    <span class="badge bg-danger">{{ ucfirst($user->role) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->workplace ?: 'N/A' }}</td>
                                            <td>{{ $user->deleted_at->format('M d, Y g:i A') }}</td>
                                            <td>{{ $user->log_entries_count }}</td>
                                            <td>{{ $user->project_entries_count }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @can('restore', $user)
                                                        <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                    onclick="return confirm('{{ __('Are you sure you want to restore this user?') }}')">
                                                                {{ __('Restore') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                    @can('forceDelete', $user)
                                                        <form action="{{ route('users.force-destroy', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('{{ __('Are you sure you want to permanently delete this user? This action cannot be undone!') }}')">
                                                                {{ __('Delete Forever') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>{{ __('No Deleted Users') }}</h5>
                                <p class="mb-0">{{ __('There are no deleted users at this time.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection