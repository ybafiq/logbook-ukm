@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Project Entry Details') }}</span>
                    <div class="d-flex gap-1">
                        @if(!$stbc4966Entry->supervisor_approved)
                            <a href="{{ route('STBC4966.edit', $stbc4966Entry) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('STBC4966.delete', $stbc4966Entry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        @endif
                        <a href="{{ route('STBC4966.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4966Entry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ nl2br(e($stbc4966Entry->activity)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stbc4966Entry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ nl2br(e($stbc4966Entry->comment)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($stbc4966Entry->image_path)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Image') }}:</label>
                        <div class="col-sm-9">
                            <img src="{{ asset('storage/' . $stbc4966Entry->image_path) }}" alt="Entry image" class="img-fluid img-thumbnail" style="max-height: 300px;">
                        </div>
                    </div>
                    @endif

                    @if($stbc4966Entry->weekly_reflection_content)
                        <hr>
                        <h5 class="mb-3">{{ __('Suggestion for improvement and planning for
                            the upcoming week') }}</h5>
                        
                        @if($stbc4966Entry->reflection_week_start)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $stbc4966Entry->reflection_week_start->format('F d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Improvement') }}:</label>
                            <div class="col-sm-9">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ nl2br(e($stbc4966Entry->weekly_reflection_content)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Improvement Status') }}:</label>
                            <div class="col-sm-9">
                                @if($stbc4966Entry->reflection_supervisor_signed)
                                    <span class="badge bg-success">{{ __('Signed by Supervisor') }}</span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Signed by {{ $stbc4966Entry->reflectionSigner->name }} on {{ $stbc4966Entry->reflection_signed_at->format('F d, Y g:i A') }}
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
                            @if($stbc4966Entry->supervisor_approved)
                                <span class="badge bg-success fs-6">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-warning fs-6">{{ __('Pending Approval') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($stbc4966Entry->supervisor_approved && $stbc4966Entry->approver)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved By') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4966Entry->approver->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved At') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4966Entry->approved_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Created') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4966Entry->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($stbc4966Entry->updated_at != $stbc4966Entry->created_at)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Last Updated') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4966Entry->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Supervisor Actions Section --}}
                    @if(auth()->user()->isSupervisor() && !$stbc4966Entry->supervisor_approved)
                    <div class="border-top pt-3 mt-4">
                        <h5>{{ __('Supervisor Actions') }}</h5>
                        <form action="{{ route('supervisor.approveProjectEntry', $stbc4966Entry) }}" method="POST" class="d-inline">
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