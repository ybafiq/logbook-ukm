@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Export Logbook to PDF') }}</h4>
                    <small class="text-muted">{{ __('Generate a PDF report of your logbook entries') }}</small>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('You can export all your entries or filter by date range. The PDF will include your log entries, project entries, and weekly reflections.') }}
                    </div>

                    <form action="{{ route('users.exportLogbook') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">{{ __('Start Date (Optional)') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                                <small class="text-muted">{{ __('Leave blank to include all entries from the beginning') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">{{ __('End Date (Optional)') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                                <small class="text-muted">{{ __('Leave blank to include all entries until today') }}</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ __('Export Summary') }}</h6>
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="bg-primary text-white rounded p-2">
                                                    <h5>{{ auth()->user()->logEntries()->count() }}</h5>
                                                    <small>{{ __('Total Log Entries') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="bg-success text-white rounded p-2">
                                                    <h5>{{ auth()->user()->projectEntries()->count() }}</h5>
                                                    <small>{{ __('Total Project Entries') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="bg-info text-white rounded p-2">
                                                    <h5>{{ auth()->user()->weeklyReflections()->count() }}</h5>
                                                    <small>{{ __('Total Reflections') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.profile') }}" class="btn btn-secondary">{{ __('Back to Profile') }}</a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="clearDates()">
                                    {{ __('Clear Filters') }}
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> {{ __('Generate PDF') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Export Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6>{{ __('Export Tips') }}</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>{{ __('The PDF will be automatically downloaded when generated') }}</li>
                        <li>{{ __('All entries will be sorted by date in descending order') }}</li>
                        <li>{{ __('The PDF includes your student information, approval status, and supervisor signatures') }}</li>
                        <li>{{ __('Use date filters to export specific periods (e.g., monthly reports)') }}</li>
                        <li>{{ __('The generated file will be named with your matric number and timestamp') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearDates() {
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
}
</script>
@endsection