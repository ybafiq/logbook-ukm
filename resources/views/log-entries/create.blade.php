@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Log Entries') }}</div>

                <form action="{{ route('log-entries.store') }}" method="post">
                    @csrf
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="form-group mb-3">
                        <label for="date">{{ __('Date') }}</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="activity">{{ __('Activity') }}</label>
                        <textarea name="activity" class="form-control" rows="4" required>{{ old('activity') }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="comment">{{ __('Comment (Optional)') }}</label>
                        <textarea name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">{{ __('Create Entry') }}</button>
                        <a href="{{ route('log-entries.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
