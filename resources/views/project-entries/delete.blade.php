@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">{{ __('Delete Project Entry') }}</div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>{{ __('Warning!') }}</strong> {{ __('This action cannot be undone. Are you sure you want to delete this project entry?') }}
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Date') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Activity') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $projectEntry->activity }}</p>
                        </div>
                    </div>

                    @if($projectEntry->comment)
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Comment') }}:</label>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div style="white-space: pre-wrap;">{{ $projectEntry->comment }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div>
                            <form action="{{ route('project-entries.destroy', $projectEntry) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('{{ __('Are you absolutely sure you want to delete this project entry?') }}')">
                                    {{ __('Yes, Delete Project Entry') }}
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('project-entries.show', $projectEntry) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection