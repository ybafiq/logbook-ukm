@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
@endsection
