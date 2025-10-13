@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Pending Log Entries') }} ({{ $entries->total() }} {{ Str::plural('entry', $entries->total()) }})</span>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn btn-secondary btn-sm">{{ __('Back to Dashboard') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($entries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Student') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Activity') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Submitted') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->student->name }}</td>
                                        <td>{{ $entry->date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($entry->activity, 60) }}</td>
                                        <td>{{ Str::limit($entry->comment, 30) ?: 'N/A' }}</td>
                                        <td>{{ $entry->created_at->format('M d, Y g:i A') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('log-entries.show', $entry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                <form action="{{ route('supervisor.approveEntry', $entry) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('{{ __('Are you sure you want to approve this log entry?') }}')">
                                                        {{ __('Approve') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $entries->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>{{ __('All Log Entries Approved!') }}</h5>
                                <p class="mb-0">{{ __('There are no pending log entries at this time.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection