@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Log Entries') }}</span>
                    <a href="{{ route('log-entries.create') }}" class="btn btn-primary">{{ __('Add New Entry') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($logEntries->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Activity') }}</th>
                                    <th>{{ __('Comment') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Approved By') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logEntries as $logEntry)
                                    <tr>
                                        <td>{{ $logEntry->date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($logEntry->activity, 50) }}</td>
                                        <td>{{ Str::limit($logEntry->comment, 30) ?: 'N/A' }}</td>
                                        <td>
                                            @if($logEntry->supervisor_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $logEntry->approver->name ?? 'N/A' }}</td>
                                        <td>
                                            @can('view', $logEntry)
                                                <a href="{{ route('log-entries.show', $logEntry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                            @endcan
                                            @can('update', $logEntry)
                                                <a href="{{ route('log-entries.edit', $logEntry) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('delete', $logEntry)
                                                <a href="{{ route('log-entries.delete', $logEntry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{ $logEntries->links() }}
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No log entries found.') }}</p>
                            <a href="{{ route('log-entries.create') }}" class="btn btn-primary">{{ __('Create your first entry') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection