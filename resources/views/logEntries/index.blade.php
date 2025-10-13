@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Log Entries') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
            
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Activity') }}</th>
                                <th>{{ __('Comment') }}</th>
                                <th>{{ __('Suppervisor Approved') }}</th>
                                <th>{{ __('Approved by') }}</th>
                                <th>{{ __('Approve at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logEntries as $logEntry)
                                <tr>
                                    <td>{{ $logEntry->name }}</td>
                                    <td>{{ $logEntry->date }}</td>
                                    <td>{{ $logEntry->activity }}</td>
                                    <td>{{ $logEntry->comment }}</td>
                                    <td>{{ $logEntry->suppervisor_approved }}</td>
                                    <td>{{ $logEntry->approved_by }}</td>
                                    <td>{{ $logEntry->approved_at }}</td>
                                    <td>{{ $logEntry->user->name }} - {{ $logEntry->user->email }}</td>
                                    <td>
                                        @can('view', $logEntry)
                                        <a href="{{ route('log-entries.show', $logEntry) }}" class="btn btn-primary">{{ __('View') }}</a>
                                        @endcan
                                        @can('update', $logEntry)
                                        <a href="{{ route('log-entries.edit', $logEntry) }}" class="btn btn-success">{{ __('Edit') }}</a>
                                        @endcan
                                        @can('delete', $logEntry)
                                        <a href="{{ route('log-entries.delete', $logEntry) }}" class="btn btn-danger">{{ __('Delete') }}</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-header">{{ __('Deleted Inventories Index') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deletedInventories as $inventory)
                                <tr>
                                    <td>{{ $inventory->name }}</td>
                                    <td>{{ $inventory->description }}</td>
                                    <td>{{ $inventory->quantity }}</td>
                                    <td>{{ $inventory->user->name }} - {{ $inventory->user->email }}</td>
                                    <td>
                                        <a href="{{ route('inventories.restore', $inventory) }}" class="btn btn-primary">{{ __('Restore') }}</a>
                                        <a href="{{ route('inventories.forceDelete', $inventory) }}" class="btn btn-success">{{ __('Force Delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection