@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">{{ __('Delete Weekly Reflection') }}</div>

                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>{{ __('Warning!') }}</strong> {{ __('This action cannot be undone. Are you sure you want to delete this weekly reflection?') }}
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Week Starting') }}:</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $reflection->week_start->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">{{ __('Content Preview') }}:</label>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div style="white-space: pre-wrap;">{{ Str::limit($reflection->content, 200) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reflection->supervisor_signed)
                    <div class="alert alert-info">
                        <strong>{{ __('Note:') }}</strong> {{ __('This reflection has been signed by a supervisor.') }}
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div>
                            <form action="{{ route('reflections.destroy', $reflection) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('{{ __('Are you absolutely sure you want to delete this reflection?') }}')">
                                    {{ __('Yes, Delete Reflection') }}
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('reflections.show', $reflection) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection