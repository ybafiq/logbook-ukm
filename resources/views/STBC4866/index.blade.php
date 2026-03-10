@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Log Entries') }}</span>
                    <a href="{{ route('STBC4866.create') }}" class="btn btn-primary">{{ __('Add New Entry') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($STBC4866Entries->count() > 0)
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
                                    @foreach ($STBC4866Entries as $stbc4866Entry)
                                        <tr>
                                            <td>{{ $stbc4866Entry->date->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($stbc4866Entry->activity, 50) }}</td>
                                            <td>{{ Str::limit($stbc4866Entry->comment, 30) ?: 'N/A' }}</td>
                                            <td>
                                                @if($stbc4866Entry->supervisor_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($stbc4866Entry->weekly_reflection_content)
                                                    @if($stbc4866Entry->reflection_supervisor_signed)
                                                        <span class="badge bg-success" title="Reflection signed">✓ Signed</span>
                                                    @else
                                                        <span class="badge bg-info" title="Reflection present but not signed">📝 Present</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $stbc4866Entry->approver->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @can('view', $stbc4866Entry)
                                                        <a href="{{ route('STBC4866.show', $stbc4866Entry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                    @endcan
                                                    @can('update', $stbc4866Entry)
                                                        <a href="{{ route('STBC4866.edit', $stbc4866Entry) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                                    @endcan
                                                    @can('delete', $stbc4866Entry)
                                                        <a href="{{ route('STBC4866.delete', $stbc4866Entry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $STBC4866Entries->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No log entries found.') }}</p>
                            <a href="{{ route('STBC4866.create') }}" class="btn btn-primary">{{ __('Create your first entry') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection