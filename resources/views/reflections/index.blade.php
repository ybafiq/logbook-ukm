@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Weekly Reflections') }}</span>
                    <a href="{{ route('reflections.create') }}" class="btn btn-primary">{{ __('Add New Reflection') }}</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($reflections->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Week Start') }}</th>
                                    <th>{{ __('Content') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Signed By') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reflections as $reflection)
                                    <tr>
                                        <td>{{ $reflection->week_start->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($reflection->content, 80) }}</td>
                                        <td>
                                            @if($reflection->supervisor_signed)
                                                <span class="badge bg-success">Signed</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $reflection->signer->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('reflections.show', $reflection) }}" class="btn btn-sm btn-primary">{{ __('View') }}</a>
                                            @if(!$reflection->supervisor_signed)
                                                <a href="{{ route('reflections.edit', $reflection) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>
                                                <a href="{{ route('reflections.delete', $reflection) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{ $reflections->links() }}
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('No weekly reflections found.') }}</p>
                            <a href="{{ route('reflections.create') }}" class="btn btn-primary">{{ __('Create your first reflection') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection