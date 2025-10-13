@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Users Directory') }}</div>

                <div class="card-body">
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
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
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
