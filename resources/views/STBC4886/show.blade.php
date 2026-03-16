@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Log Entry Details') }}</span>
                    <div class="d-flex gap-1">
                        @can('update', $stbc4886Entry)
                            <a href="{{ route('STBC4886.edit', $stbc4886Entry) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                        @endcan
                        @can('delete', $stbc4886Entry)
                            <a href="{{ route('STBC4886.delete', $stbc4886Entry) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        @endcan
                        <a href="{{ route('STBC4886.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->activity }}</p>
                        </div>
                    </div>

                    @if($stbc4886Entry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->comment }}</p>
                        </div>
                    </div>
                    @endif

                    @if($stbc4886Entry->image_path)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Image') }}:</label>
                        <div class="col-sm-9">
                            <img src="{{ asset('storage/' . $stbc4886Entry->image_path) }}" alt="Entry image" class="img-fluid img-thumbnail" style="max-height: 300px;">
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Status') }}:</label>
                        <div class="col-sm-9">
                            @if($stbc4886Entry->supervisor_approved)
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-warning">{{ __('Pending Approval') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($stbc4886Entry->supervisor_approved && $stbc4886Entry->approver)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved By') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->approver->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Approved At') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->approved_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Created') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($stbc4886Entry->updated_at != $stbc4886Entry->created_at)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Last Updated') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $stbc4886Entry->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($stbc4886Entry->weekly_reflection_content || $stbc4886Entry->weekly_summary_content)
                        <hr>
                        <h5 class="mb-3">{{ __('Weekly Reflection') }}</h5>
                        
                        @if($stbc4886Entry->reflection_week_start)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $stbc4886Entry->reflection_week_start->format('F d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($stbc4886Entry->weekly_summary_content)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Summary of Achievements') }}:</label>
                            <div class="col-sm-9">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ nl2br(e($stbc4886Entry->weekly_summary_content)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($stbc4886Entry->weekly_reflection_content)
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Reflection Content') }}:</label>
                            <div class="col-sm-9">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ nl2br(e($stbc4886Entry->weekly_reflection_content)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-bold">{{ __('Reflection Status') }}:</label>
                            <div class="col-sm-9">
                                @if($stbc4886Entry->reflection_supervisor_signed)
                                    <span class="badge bg-success">{{ __('Signed by Supervisor') }}</span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Signed by {{ $stbc4886Entry->reflectionSigner->name }} on {{ $stbc4886Entry->reflection_signed_at->format('F d, Y g:i A') }}
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