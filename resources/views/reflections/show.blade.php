@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Weekly Reflection Details') }}</span>
                    <div>
                        @if(!$reflection->supervisor_signed)
                            <a href="{{ route('reflections.edit', $reflection) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('reflections.delete', $reflection) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                        @endif
                        <a href="{{ route('reflections.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->week_start->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Status') }}:</label>
                        <div class="col-sm-9">
                            @if($reflection->supervisor_signed)
                                <span class="badge bg-success fs-6">{{ __('Signed by Supervisor') }}</span>
                            @else
                                <span class="badge bg-warning fs-6">{{ __('Pending Supervisor Signature') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($reflection->supervisor_signed && $reflection->signer)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Signed By') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->signer->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Signed At') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->signed_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Reflection Content') }}:</label>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div style="white-space: pre-wrap; line-height: 1.6;">{{ $reflection->content }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Created') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($reflection->updated_at != $reflection->created_at)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Last Updated') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Supervisor Actions Section --}}
                    @if(auth()->user()->isSupervisor() && !$reflection->supervisor_signed)
                    <div class="border-top pt-3 mt-4">
                        <h5>{{ __('Supervisor Actions') }}</h5>
                        <form action="{{ route('supervisor.signReflection', $reflection) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('{{ __('Are you sure you want to sign this reflection?') }}')">
                                {{ __('Sign Reflection') }}
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