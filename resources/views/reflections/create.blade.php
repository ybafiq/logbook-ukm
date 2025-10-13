@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Weekly Reflection') }}</div>

                <div class="card-body">
                    <form action="{{ route('reflections.store') }}" method="post">
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
                        
                        <div class="form-group mb-3">
                            <label for="week_start">{{ __('Week Starting Date') }}</label>
                            <input type="date" name="week_start" class="form-control" 
                                   value="{{ old('week_start', now()->startOfWeek()->format('Y-m-d')) }}" required>
                            <small class="form-text text-muted">{{ __('Select the Monday of the week you are reflecting on') }}</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="content">{{ __('Reflection Content') }}</label>
                            <textarea name="content" class="form-control" rows="8" required 
                                      placeholder="{{ __('Write your weekly reflection here. Reflect on what you learned, challenges faced, achievements, and goals for the next week...') }}">{{ old('content') }}</textarea>
                            <small class="form-text text-muted">{{ __('Minimum 10 characters required') }}</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Save Reflection') }}</button>
                            <a href="{{ route('reflections.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-adjust textarea height
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="content"]');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
@endsection