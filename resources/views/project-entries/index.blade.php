@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Project Entries') }}</span>
                    <a href="{{ route('project-entries.create') }}" class="btn btn-primary">{{ __('Add New Project Entry') }}</a>
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
                                        <th>{{ __('Approved By') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectEntries as $projectEntry)
                                        <tr>
                                            <td>{{ $projectEntry->date->format('M d, Y') }}</td>
                                            <td>{{ Str::limit($projectEntry->activity, 80) }}</td>
                                            <td>{{ Str::limit($projectEntry->comment, 30) ?: 'N/A' }}</td>
                                            <td>
                                                @if($projectEntry->supervisor_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $projectEntry->approver->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('project-entries.show', $projectEntry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                    @if(!$projectEntry->supervisor_approved)
                                                        <a href="{{ route('project-entries.edit', $projectEntry) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                                        <a href="{{ route('project-entries.delete', $projectEntry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $projectEntries->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No project entries found.') }}</p>
                            <a href="{{ route('project-entries.create') }}" class="btn btn-primary">{{ __('Create your first project entry') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection