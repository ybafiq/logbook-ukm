@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Project Entries') }}</span>
                    <a href="{{ route('STBC4966.create') }}" class="btn btn-primary">{{ __('Add New Project Entry') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($projectEntries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Activity') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Improvement') }}</th>
                                        <th>{{ __('Approved By') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectEntries as $stbc4966Entry)
                                        <tr>
                                            <td>{{ $stbc4966Entry->date->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($stbc4966Entry->activity, 60) }}</td>
                                            <td>{{ Str::limit($stbc4966Entry->comment, 40) ?: 'N/A' }}</td>
                                            <td>
                                                @if($stbc4966Entry->supervisor_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($stbc4966Entry->weekly_reflection_content)
                                                    @if($stbc4966Entry->reflection_supervisor_signed)
                                                        <span class="badge bg-success" title="Reflection signed">✓ Signed</span>
                                                    @else
                                                        <span class="badge bg-info" title="Reflection present but not signed">📝 Present</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $stbc4966Entry->approver->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('STBC4966.show', $stbc4966Entry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                    @if(!$stbc4966Entry->supervisor_approved)
                                                        <a href="{{ route('STBC4966.edit', $stbc4966Entry) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                                        <a href="{{ route('STBC4966.delete', $stbc4966Entry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $projectEntries->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No project entries found.') }}</p>
                            <a href="{{ route('STBC4966.create') }}" class="btn btn-primary">{{ __('Create your first project entry') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
