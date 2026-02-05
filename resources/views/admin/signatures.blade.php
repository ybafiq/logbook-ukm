@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-signature"></i> {{ __('Manage Supervisor Signatures') }}</span>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">{{ __('Back to Users') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#log-signatures" type="button">
                                {{ __('Log Entry Signatures') }} 
                                <span class="badge bg-primary">{{ $logEntriesWithSignatures->total() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#project-signatures" type="button">
                                {{ __('Project Entry Signatures') }} 
                                <span class="badge bg-primary">{{ $projectEntriesWithSignatures->total() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Log Entries Tab -->
                        <div class="tab-pane fade show active" id="log-signatures">
                            @if($logEntriesWithSignatures->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Student') }}</th>
                                                <th>{{ __('Entry Date') }}</th>
                                                <th>{{ __('Supervisor') }}</th>
                                                <th>{{ __('Approved Date') }}</th>
                                                <th>{{ __('Signature') }}</th>
                                                <th>{{ __('Comment') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logEntriesWithSignatures as $entry)
                                            <tr>
                                                <td>{{ $entry->student->name }}</td>
                                                <td>{{ $entry->date->format('M d, Y') }}</td>
                                                <td>{{ $entry->approver->name ?? 'N/A' }}</td>
                                                <td>{{ $entry->approved_at ? $entry->approved_at->format('M d, Y g:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($entry->supervisor_signature)
                                                        <button type="button" class="btn btn-sm btn-info" onclick="viewSignature('{{ asset('storage/' . $entry->supervisor_signature) }}')">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($entry->supervisor_comment, 30) ?: 'N/A' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.deleteLogSignature', $entry) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('{{ __('Are you sure you want to delete this signature?') }}')">
                                                            <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    {{ $logEntriesWithSignatures->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> {{ __('No log entry signatures found.') }}
                                </div>
                            @endif
                        </div>

                        <!-- Project Entries Tab -->
                        <div class="tab-pane fade" id="project-signatures">
                            @if($projectEntriesWithSignatures->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Student') }}</th>
                                                <th>{{ __('Entry Date') }}</th>
                                                <th>{{ __('Supervisor') }}</th>
                                                <th>{{ __('Approved Date') }}</th>
                                                <th>{{ __('Signature') }}</th>
                                                <th>{{ __('Comment') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projectEntriesWithSignatures as $entry)
                                            <tr>
                                                <td>{{ $entry->student->name }}</td>
                                                <td>{{ $entry->date->format('M d, Y') }}</td>
                                                <td>{{ $entry->approver->name ?? 'N/A' }}</td>
                                                <td>{{ $entry->approved_at ? $entry->approved_at->format('M d, Y g:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($entry->supervisor_signature)
                                                        <button type="button" class="btn btn-sm btn-info" onclick="viewSignature('{{ asset('storage/' . $entry->supervisor_signature) }}')">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($entry->supervisor_comment, 30) ?: 'N/A' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.deleteProjectSignature', $entry) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('{{ __('Are you sure you want to delete this signature?') }}')">
                                                            <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    {{ $projectEntriesWithSignatures->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> {{ __('No project entry signatures found.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Signature View Modal -->
<div class="modal fade" id="signatureViewModal" tabindex="-1" aria-labelledby="signatureViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureViewModalLabel">{{ __('Supervisor Signature') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="signatureImage" src="" alt="Signature" class="img-fluid" style="max-height: 400px; border: 1px solid #ddd;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewSignature(imageUrl) {
        document.getElementById('signatureImage').src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('signatureViewModal'));
        modal.show();
    }
</script>
@endsection
