@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Log Entries') }}</div>

                <form action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">{{ __('Date') }}</label>
                        <input type="text" name="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="activity">{{ __('Activity') }}</label>
                        <input type="text" name="activity" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="comment">{{ __('Comment') }}</label>
                        <input type="text" name="comment" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="supervisor_approved">{{ __('Suppervisor Approved') }}</label>
                        <input type="text" name="supervisor_approved" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="supervisor_by">{{ __('Approved by') }}</label>
                        <input type="text" name="supervisor_by" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="supervisor_at">{{ __('Approved at') }}</label>
                        <input type="text" name="supervisor_at" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection