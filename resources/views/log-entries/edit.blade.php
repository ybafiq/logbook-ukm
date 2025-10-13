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
                        
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Update Entry') }}</button>
                            <a href="{{ route('log-entries.show', $logEntry) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection