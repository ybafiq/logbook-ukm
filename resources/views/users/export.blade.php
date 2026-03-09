@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Export Logbook to PDF') }}</h4>
                    <p class="mb-0 mt-1" style="font-size: 1rem; font-weight: normal; color: #6c757d;">
                        {{ __('Generate a PDF report of your logbook entries') }}
                    </p>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('You can export all your entries or filter by entry type and date range. Choose what to include in your PDF logbook.') }}
                    </div>

                    <form id="export-form" action="{{ route('users.exportLogbook') }}" method="GET">
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
                                            <div> <br> </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="include_reflection" id="include_reflection" value="1"
                                                            {{ request()->boolean('include_reflection') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="include_reflection">
                                                            {{ __('Include Supervisor Sign') }}
                                                        </label>
                                                    </div>
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

                        <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('users.profile') }}" class="btn btn-secondary">{{ __('Back to Profile') }}</a>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" onclick="clearDates()">
            {{ __('Clear Filters') }}
        </button>

        <button type="submit" class="btn btn-danger" id="generate-btn">
            <i class="fas fa-file-pdf"></i> {{ __('Generate PDF') }}
        </button>
    </div>
</div>

<!-- Merge PDFs Section Card -->
<div class="card mb-4">
    <div class="card-header">
        <h6>{{ __('Merge PDFs') }}</h6>
    </div>
    <div class="card-body">
        <p>{{ __('Drag & drop PDF files here, reorder them, preview merged PDF, and download.') }}</p>

        <!-- Drag & Drop Zone -->
        <div id="pdf-drop-zone" class="border border-dashed p-3 text-center mb-3" style="min-height:120px; cursor:pointer;">
            <p id="drop-zone-text">{{ __('Drop PDF files here or click to select') }}</p>
            <input type="file" id="merge-files" name="files[]" accept="application/pdf" multiple style="display:none">
        </div>

        <!-- File list -->
        <ul id="pdf-file-list" class="list-group mb-3"></ul>

        <div class="d-flex gap-2">
            <button type="button" id="preview-btn" class="btn btn-outline-primary">
                <i class="fas fa-eye"></i> {{ __('Preview Merged PDF') }}
            </button>
            <button type="button" id="download-btn" class="btn btn-outline-success">
                <i class="fas fa-download"></i> {{ __('Download Merged PDF') }}
            </button>
        </div>
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

<!-- Hidden file input & small helper form for merging -->
<input type="file" id="merge-files" name="files[]" accept="application/pdf" multiple style="display:none">

<script>
function clearDates() {
    document.getElementById('start_date').value = '';
    document.getElementById('end_date').value = '';
}

// Update filter info when entry type changes
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('pdf-drop-zone');
    const fileInput = document.getElementById('merge-files');
    const fileListContainer = document.getElementById('pdf-file-list');
    const previewBtn = document.getElementById('preview-btn');
    const downloadBtn = document.getElementById('download-btn');

    let selectedFiles = [];

    // Click zone to open file picker
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('bg-light'); });
    dropZone.addEventListener('dragleave', e => { e.preventDefault(); dropZone.classList.remove('bg-light'); });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('bg-light');
        const files = Array.from(e.dataTransfer.files).filter(f => f.type === 'application/pdf');
        addFiles(files);
    });

    fileInput.addEventListener('change', e => addFiles(Array.from(e.target.files)));

    function addFiles(files) {
        selectedFiles = selectedFiles.concat(files);
        renderFileList();
    }

    function renderFileList() {
        fileListContainer.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                <span>${file.name}</span>
                <div>
                    <button class="btn btn-sm btn-outline-danger remove-file"><i class="fas fa-times"></i></button>
                </div>
            `;
            fileListContainer.appendChild(li);

            li.querySelector('.remove-file').addEventListener('click', () => {
                selectedFiles.splice(idx, 1);
                renderFileList();
            });
        });

        previewBtn.disabled = selectedFiles.length < 2;
        downloadBtn.disabled = selectedFiles.length < 2;
        document.getElementById('drop-zone-text').textContent = selectedFiles.length 
            ? `${selectedFiles.length} file(s) selected` 
            : '{{ __("Drop PDF files here or click to select") }}';
    }

    // Preview Merged PDF
    previewBtn.addEventListener('click', async (event) => {
        event.preventDefault();
        if (selectedFiles.length < 2) return alert('Select at least 2 PDFs.');
        const formData = new FormData();
        selectedFiles.forEach(f => formData.append('files[]', f));

        try {
            const response = await fetch("{{ route('pdf.merge.preview') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            if (!response.ok) return alert('Failed to generate preview.');

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

            let previewFrame = document.getElementById('pdfPreviewFrame');
            if (!previewFrame) {
                const modalDiv = document.createElement('div');
                modalDiv.innerHTML = `
                    <div class="modal fade" id="pdfPreviewModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('PDF Preview') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="pdfPreviewFrame" src="${url}" width="100%" height="700px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>`;
                document.body.appendChild(modalDiv);
                previewFrame = document.getElementById('pdfPreviewFrame');
            } else {
                previewFrame.src = url;
            }

            const modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
            modal.show();

        } catch (err) {
            console.error(err);
            alert('{{ __("An error occurred while generating preview.") }}');
        }
    });

    // Download merged PDF
    downloadBtn.addEventListener('click', async (event) => {
        event.preventDefault();
        if (selectedFiles.length < 2) return alert('Select at least 2 PDFs.');
        const formData = new FormData();
        selectedFiles.forEach(f => formData.append('files[]', f));

        try {
            const response = await fetch("{{ route('pdf.merge') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            if (!response.ok) return alert('Failed to merge PDFs.');
            const blob = await response.blob();
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'merged.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();

        } catch (err) {
            console.error(err);
            alert('{{ __("An error occurred while downloading merged PDF.") }}');
        }
    });
});
</script>



@endsection
