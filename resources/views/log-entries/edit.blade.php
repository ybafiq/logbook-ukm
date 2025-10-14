@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Log Entry') }}</div>

                <div class="card-body">
                    <form action="{{ route('log-entries.update', $logEntry) }}" method="post">
                        @csrf
                        @method('POST')
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="form-group mb-3">
                            <label for="date">{{ __('Date') }}</label>
                            <input type="date" name="date" class="form-control" 
                                   value="{{ old('date', $logEntry->date->format('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="activity">{{ __('Activity') }}</label>
                            <textarea name="activity" class="form-control" rows="4" required>{{ old('activity', $logEntry->activity) }}</textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="comment">{{ __('Comment (Optional)') }}</label>
                            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $logEntry->comment) }}</textarea>
                        </div>
                        
                        <!-- Weekly Reflection Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_reflection" 
                                           {{ old('weekly_reflection_content', $logEntry->weekly_reflection_content) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_reflection">
                                        {{ __('Include Weekly Reflection') }}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="reflection_fields" 
                                 style="display: {{ old('weekly_reflection_content', $logEntry->weekly_reflection_content) ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="reflection_week_start" class="form-label">{{ __('Week Starting Date') }}</label>
                                    <input type="date" name="reflection_week_start" id="reflection_week_start" 
                                           class="form-control" 
                                           value="{{ old('reflection_week_start', $logEntry->reflection_week_start ? $logEntry->reflection_week_start->format('Y-m-d') : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="weekly_reflection_content" class="form-label">{{ __('Weekly Reflection Content') }}</label>
                                    <textarea name="weekly_reflection_content" id="weekly_reflection_content" 
                                              class="form-control" rows="5" 
                                              placeholder="Reflect on your learning progress, challenges faced, and insights gained this week...">{{ old('weekly_reflection_content', $logEntry->weekly_reflection_content) }}</textarea>
                                </div>
                                @if($logEntry->reflection_supervisor_signed)
                                    <div class="alert alert-info">
                                        <i class="fas fa-check-circle"></i>
                                        Reflection signed by {{ $logEntry->reflectionSigner->name }} on {{ $logEntry->reflection_signed_at->format('M d, Y \a\t H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Update Entry') }}</button>
                            <a href="{{ route('log-entries.show', $logEntry) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reflectionCheckbox = document.getElementById('include_reflection');
    const reflectionFields = document.getElementById('reflection_fields');
    
    reflectionCheckbox.addEventListener('change', function() {
        if (this.checked) {
            reflectionFields.style.display = 'block';
        } else {
            reflectionFields.style.display = 'none';
            // Clear the fields when hiding
            document.getElementById('reflection_week_start').value = '';
            document.getElementById('weekly_reflection_content').value = '';
        }
    });
});
</script>
@endsection
