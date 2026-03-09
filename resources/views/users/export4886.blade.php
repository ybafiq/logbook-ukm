@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Generate STBC4886 PDF') }}</h4>
                    <p class="mb-0 mt-1 text-muted">{{ __('Use the filters below to select which entries to include in the STBC4886 output.') }}</p>
                </div>

                <div class="card-body">
                    <form id="export-4886-form" action="{{ route('users.export4886') }}" method="GET">
                        <input type="hidden" name="template" value="4886">

                        <div class="mb-3">
                            <label class="form-label">{{ __('Include Entry Sources') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_logs" id="include_logs" value="1" checked>
                                <label class="form-check-label" for="include_logs">{{ __('Daily Log Entries') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_projects" id="include_projects" value="1">
                                <label class="form-check-label" for="include_projects">{{ __('Project Entries') }}</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">{{ __('Start Date (Optional)') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">{{ __('End Date (Optional)') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_reflection" id="include_reflection" value="1">
                            <label class="form-check-label" for="include_reflection">{{ __('Include Supervisor Sign / Reflection Section') }}</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.profile') }}" class="btn btn-secondary">{{ __('Back to Profile') }}</a>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-file-pdf"></i> {{ __('Generate STBC4886 PDF') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
