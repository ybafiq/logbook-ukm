@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Log Entry Details') }}</span>
                    <div class="d-flex gap-1">
                        @can('update', $logEntry)
                            <a href="{{ route('log-entries.edit', $logEntry) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                        @endcan
                        @can('delete', $logEntry)
                            <a href="{{ route('log-entries.delete', $logEntry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        @endcan
                        <a href="{{ route('log-entries.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->activity }}</p>
                        </div>
                    </div>

                    @if($logEntry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->comment }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Status') }}:</label>
                        <div class="col-sm-9">
                            @if($logEntry->supervisor_approved)
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-warning">{{ __('Pending Approval') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($logEntry->supervisor_approved && $logEntry->approver)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved By') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->approver->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved At') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->approved_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Created') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($logEntry->updated_at != $logEntry->created_at)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Last Updated') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($logEntry->weekly_reflection_content)
                        <hr>
                        <h5 class="mb-3">{{ __('Weekly Reflection') }}</h5>
                        
                        @if($logEntry->reflection_week_start)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $logEntry->reflection_week_start->format('F d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Reflection Content') }}:</label>
                            <div class="col-sm-9">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ nl2br(e($logEntry->weekly_reflection_content)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Reflection Status') }}:</label>
                            <div class="col-sm-9">
                                @if($logEntry->reflection_supervisor_signed)
                                    <span class="badge bg-success">{{ __('Signed by Supervisor') }}</span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Signed by {{ $logEntry->reflectionSigner->name }} on {{ $logEntry->reflection_signed_at->format('F d, Y g:i A') }}
                                        </small>
                                    </div>
                                @else
                                    <span class="badge bg-warning">{{ __('Pending Supervisor Signature') }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection