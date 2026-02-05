@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Project Entry Details') }}</span>
                    <div class="d-flex gap-1">
                        @if(!$projectEntry->supervisor_approved)
                            <a href="{{ route('project-entries.edit', $projectEntry) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('project-entries.delete', $projectEntry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        @endif
                        <a href="{{ route('project-entries.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ nl2br(e($projectEntry->activity)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($projectEntry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ nl2br(e($projectEntry->comment)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($projectEntry->weekly_reflection_content)
                        <hr>
                        <h5 class="mb-3">{{ __('Suggestion for improvement and planning for
                            the upcoming week') }}</h5>
                        
                        @if($projectEntry->reflection_week_start)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $projectEntry->reflection_week_start->format('F d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Improvement') }}:</label>
                            <div class="col-sm-9">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ nl2br(e($projectEntry->weekly_reflection_content)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Improvement Status') }}:</label>
                            <div class="col-sm-9">
                                @if($projectEntry->reflection_supervisor_signed)
                                    <span class="badge bg-success">{{ __('Signed by Supervisor') }}</span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Signed by {{ $projectEntry->reflectionSigner->name }} on {{ $projectEntry->reflection_signed_at->format('F d, Y g:i A') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="badge bg-warning">{{ __('Pending Supervisor Signature') }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Status') }}:</label>
                        <div class="col-sm-9">
                            @if($projectEntry->supervisor_approved)
                                <span class="badge bg-success fs-6">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-warning fs-6">{{ __('Pending Approval') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($projectEntry->supervisor_approved && $projectEntry->approver)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved By') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->approver->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved At') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->approved_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Created') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($projectEntry->updated_at != $projectEntry->created_at)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Last Updated') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Supervisor Actions Section --}}
                    @if(auth()->user()->isSupervisor() && !$projectEntry->supervisor_approved)
                    <div class="border-top pt-3 mt-4">
                        <h5>{{ __('Supervisor Actions') }}</h5>
                        <form action="{{ route('supervisor.approveProjectEntry', $projectEntry) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('{{ __('Are you sure you want to approve this project entry?') }}')">
                                {{ __('Approve Project Entry') }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection