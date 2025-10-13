@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Pending Weekly Reflections') }} ({{ $reflections->total() }} {{ Str::plural('reflection', $reflections->total()) }})</span>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn btn-secondary btn-sm">{{ __('Back to Dashboard') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($reflections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Student') }}</th>
                                        <th>{{ __('Week Start') }}</th>
                                        <th>{{ __('Content') }}</th>
                                        <th>{{ __('Submitted') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reflections as $reflection)
                                    <tr>
                                        <td>{{ $reflection->student->name }}</td>
                                        <td>{{ $reflection->week_start->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($reflection->content, 80) }}</td>
                                        <td>{{ $reflection->created_at->format('M d, Y g:i A') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('reflections.show', $reflection) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                                <form action="{{ route('supervisor.signReflection', $reflection) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('{{ __('Are you sure you want to sign this reflection?') }}')">
                                                        {{ __('Sign') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $reflections->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>{{ __('All Reflections Signed!') }}</h5>
                                <p class="mb-0">{{ __('There are no pending reflections at this time.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection