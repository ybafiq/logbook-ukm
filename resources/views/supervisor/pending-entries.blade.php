@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Pending Log Entries') }} ({{ $entries->total() }} {{ Str::plural('entry', $entries->total()) }})</span>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn btn-secondary btn-sm">{{ __('Back to Dashboard') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($entries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Student') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Activity') }}</th>
                                        <th>{{ __('Comment') }}</th>
                                        <th>{{ __('Submitted') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->student->name }}</td>
                                        <td>{{ $entry->date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($entry->activity, 60) }}</td>
                                        <td>{{ Str::limit($entry->comment, 30) ?: 'N/A' }}</td>
                                        <td>{{ $entry->created_at->format('M d, Y g:i A') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('log-entries.show', $entry) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                <button type="button" class="btn btn-success btn-sm" 
                                                        onclick="openSignatureModal({{ $entry->id }}, 'log')">
                                                    {{ __('Approve') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $entries->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>{{ __('All Log Entries Approved!') }}</h5>
                                <p class="mb-0">{{ __('There are no pending log entries at this time.') }}</p>
                            </div>
                        </div>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">{{ __('Approve Entry') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="signatureForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="supervisor_comment" class="form-label">{{ __('Comments (Optional)') }}</label>
                        <textarea class="form-control" id="supervisor_comment" name="supervisor_comment" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Signature (Optional - Add signature every 3 weeks)') }}</label>
                        <div class="border rounded" style="background: #f8f9fa;">
                            <canvas id="signaturePad" width="700" height="200" style="border: 1px solid #dee2e6; cursor: crosshair; background: white;"></canvas>
                        </div>
                        <small class="text-muted">{{ __('Draw your signature above') }}</small>
                        <input type="hidden" name="signature" id="signatureData">
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">{{ __('Clear Signature') }}</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Approve Entry') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let canvas = null;
    let ctx = null;
    let isDrawing = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Debug: Check if Bootstrap is loaded
        console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
        
        // Initialize canvas
        canvas = document.getElementById('signaturePad');
        if (!canvas) {
            console.error('Signature canvas not found');
            return;
        }
        console.log('Canvas initialized successfully');
        
        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Touch support
        canvas.addEventListener('touchstart', handleTouch, { passive: false });
        canvas.addEventListener('touchmove', handleTouch, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);
        
        // Form submission handler
        const form = document.getElementById('signatureForm');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
    });

    function startDrawing(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        ctx.beginPath();
        ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
    }

    function draw(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
        ctx.stroke();
    }

    function stopDrawing() {
        isDrawing = false;
    }

    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        if (!touch) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        
        if (e.type === 'touchstart') {
            isDrawing = true;
            ctx.beginPath();
            ctx.moveTo(x, y);
        } else if (e.type === 'touchmove' && isDrawing) {
            ctx.lineTo(x, y);
            ctx.stroke();
        } else if (e.type === 'touchend') {
            isDrawing = false;
        }
    }

    function clearSignature() {
        if (ctx && canvas) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    function openSignatureModal(entryId, type) {
        console.log('Opening signature modal for entry:', entryId, 'type:', type);
        
        // Clear previous signature and comment
        clearSignature();
        document.getElementById('supervisor_comment').value = '';
        
        // Set form action
        const form = document.getElementById('signatureForm');
        if (type === 'log') {
            form.action = `/supervisor/approve-entry/${entryId}`;
        } else {
            form.action = `/supervisor/approve-project-entry/${entryId}`;
        }
        
        console.log('Form action set to:', form.action);
        
        // Show modal
        const modalElement = document.getElementById('signatureModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }

    function handleFormSubmit(e) {
        console.log('Form submitting...');
        
        // Capture signature data
        const signatureData = canvas.toDataURL('image/png');
        document.getElementById('signatureData').value = signatureData;
        
        console.log('Signature captured - submitting form');
        // Form will submit normally
    }
</script>
@endsection
