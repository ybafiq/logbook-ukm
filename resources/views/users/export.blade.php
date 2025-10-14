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
                        {{ __('You can export all your entries or filter by entry type and date range. Choose what to include in your PDF logbook.') }}
                    </div>

                    <form action="{{ route('users.exportLogbook') }}" method="GET">
                        <!-- Entry Type Filter -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('Entry Types to Include') }}</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="entry_type" id="entry_type_all" value="all" 
                                                           {{ request('entry_type', 'all') == 'all' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="entry_type_all">
                                                        <strong>{{ __('All Entries') }}</strong>
                                                        <br><small class="text-muted">{{ __('Include both log and project entries') }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="entry_type" id="entry_type_log" value="log" 
                                                           {{ request('entry_type') == 'log' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="entry_type_log">
                                                        <strong>{{ __('Log Entries Only') }}</strong>
                                                        <br><small class="text-muted">{{ __('Include only daily log entries') }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="entry_type" id="entry_type_project" value="project" 
                                                           {{ request('entry_type') == 'project' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="entry_type_project">
                                                        <strong>{{ __('Project Entries Only') }}</strong>
                                                        <br><small class="text-muted">{{ __('Include only project-related entries') }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Filter -->
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
                                        <h6 class="card-title">{{ __('Available Entries') }}</h6>
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="bg-primary text-white rounded p-2">
                                                    <h5 id="log-count">{{ auth()->user()->logEntries()->count() }}</h5>
                                                    <small>{{ __('Log Entries') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="bg-success text-white rounded p-2">
                                                    <h5 id="project-count">{{ auth()->user()->projectEntries()->count() }}</h5>
                                                    <small>{{ __('Project Entries') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="bg-info text-white rounded p-2">
                                                    <h5 id="reflection-count">{{ auth()->user()->logEntries()->whereNotNull('weekly_reflection_content')->count() + auth()->user()->projectEntries()->whereNotNull('weekly_reflection_content')->count() }}</h5>
                                                    <small>{{ __('Reflections') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="alert alert-light mb-0" id="filter-info">
                                                <small class="text-muted">
                                                    <i class="fas fa-filter"></i> 
                                                    <span id="filter-text">{{ __('All entry types will be included in the PDF') }}</span>
                                                </small>
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
                        <li>{{ __('Choose between all entries, log entries only, or project entries only') }}</li>
                        <li>{{ __('All entries will be sorted by date in chronological order') }}</li>
                        <li>{{ __('Use date filters to export specific periods (e.g., monthly reports)') }}</li>
                        <li>{{ __('The PDF includes your student information and space for supervisor signatures') }}</li>
                        <li>{{ __('The filename will indicate the entry type and include your matric number') }}</li>
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

// Update filter info when entry type changes
document.addEventListener('DOMContentLoaded', function() {
    const entryTypeRadios = document.querySelectorAll('input[name="entry_type"]');
    const filterText = document.getElementById('filter-text');
    
    function updateFilterInfo() {
        const selectedType = document.querySelector('input[name="entry_type"]:checked').value;
        
        switch(selectedType) {
            case 'all':
                filterText.textContent = '{{ __('All entry types will be included in the PDF') }}';
                break;
            case 'log':
                filterText.textContent = '{{ __('Only log entries will be included in the PDF') }}';
                break;
            case 'project':
                filterText.textContent = '{{ __('Only project entries will be included in the PDF') }}';
                break;
        }
    }
    
    // Add event listeners to radio buttons
    entryTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateFilterInfo);
    });
    
    // Set initial filter text
    updateFilterInfo();
});
</script>
@endsection