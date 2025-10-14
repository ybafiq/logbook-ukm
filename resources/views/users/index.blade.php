@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        {{ __('Users Directory') }}
                        @if(auth()->user()->isAdmin())
                            ({{ $users->total() }} {{ Str::plural('user', $users->total()) }})
                        @endif
                    </span>
                    <div class="d-flex gap-2">
                        @can('create', App\Models\User::class)
                            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">{{ __('Add User') }}</a>
                        @endcan
                        @can('viewTrashed', App\Models\User::class)
                            <a href="{{ route('users.trashed') }}" class="btn btn-secondary btn-sm">{{ __('Deleted Users') }}</a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($users->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Matric No') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Workplace') }}</th>
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
                                        <td>{{ $user->log_entries_count }}</td>
                                        <td>{{ $user->project_entries_count }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @can('view', $user)
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                @endcan
                                                @can('update', $user)
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                                @endcan
                                                @can('delete', $user)
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{ $users->links() }}
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No users found.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
