@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">{{ __('Delete Log Entry') }}</div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>{{ __('Warning!') }}</strong> {{ __('This action cannot be undone. Are you sure you want to delete this log entry?') }}
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $logEntry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ Str::limit($logEntry->activity, 100) }}</p>
                        </div>
                    </div>

                    @if($logEntry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ Str::limit($logEntry->comment, 100) }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div>
                            <form action="{{ route('log-entries.destroy', $logEntry) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('{{ __('Are you absolutely sure you want to delete this entry?') }}')">
                                    {{ __('Yes, Delete Entry') }}
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('log-entries.show', $logEntry) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection