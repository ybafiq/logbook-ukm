@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Create Log Entry') }}</div>

                <div class="card-body">
                    <form action="{{ route('log-entries.store') }}" method="post">
                        @csrf
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">{{ __('Date') }}</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="activity" class="form-label">{{ __('Activity') }}</label>
                            <textarea name="activity" id="activity" class="form-control" rows="4" required>{{ old('activity') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">{{ __('Comment (Optional)') }}</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                        </div>
                        
                        <!-- Weekly Reflection Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_reflection" 
                                           {{ old('weekly_reflection_content') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_reflection">
                                        {{ __('Include Weekly Reflection') }}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="reflection_fields" style="display: {{ old('weekly_reflection_content') ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="reflection_week_start" class="form-label">{{ __('Week Starting Date') }}</label>
                                    <input type="date" name="reflection_week_start" id="reflection_week_start" 
                                           class="form-control" value="{{ old('reflection_week_start') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="weekly_reflection_content" class="form-label">{{ __('Weekly Reflection Content') }}</label>
                                    <textarea name="weekly_reflection_content" id="weekly_reflection_content" 
                                              class="form-control" rows="5" 
                                              placeholder="Reflect on your learning progress, challenges faced, and insights gained this week...">{{ old('weekly_reflection_content') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Create Entry') }}</button>
                            <a href="{{ route('log-entries.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
