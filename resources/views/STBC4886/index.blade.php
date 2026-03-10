@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Log Entries') }}</span>
                    <a href="{{ route('STBC4886.create') }}" class="btn btn-primary">{{ __('Add New Entry') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($logEntries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Activity') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Reflection') }}</th>
                                        <th>{{ __('Approved By') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logEntries as $stbc4886Entry)
                                        <tr>
                                            <td>{{ $stbc4886Entry->date->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($stbc4886Entry->activity, 50) }}</td>
                                            <td>{{ Str::limit($stbc4886Entry->comment, 30) ?: 'N/A' }}</td>
                                            <td>
                                                @if($stbc4886Entry->supervisor_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($stbc4886Entry->weekly_reflection_content)
                                                    @if($stbc4886Entry->reflection_supervisor_signed)
                                                        <span class="badge bg-success" title="Reflection signed">✓ Signed</span>
                                                    @else
                                                        <span class="badge bg-info" title="Reflection present but not signed">📝 Present</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $stbc4886Entry->approver->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @can('view', $stbc4886Entry)
                                                        <a href="{{ route('STBC4886.show', $stbc4886Entry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                    @endcan
                                                    @can('update', $stbc4886Entry)
                                                        <a href="{{ route('STBC4886.edit', $stbc4886Entry) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                                    @endcan
                                                    @can('delete', $stbc4886Entry)
                                                        <a href="{{ route('STBC4886.delete', $stbc4886Entry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $logEntries->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No log entries found.') }}</p>
                            <a href="{{ route('STBC4886.create') }}" class="btn btn-primary">{{ __('Create your first entry') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection