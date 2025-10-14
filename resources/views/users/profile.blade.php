@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('My Profile') }}</span>
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('users.showExport') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> {{ __('Export Logbook') }}
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('users.updateProfile') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Profile Picture Section -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">{{ __('Profile Picture') }}</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ $user->getProfilePictureUrl() }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle" 
                                         width="80" height="80"
                                         style="object-fit: cover; border: 3px solid #dee2e6;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="profile_picture" id="profile_picture" 
                                           class="form-control @error('profile_picture') is-invalid @enderror" 
                                           accept="image/*">
                                    <small class="form-text text-muted">
                                        {{ __('Upload a new profile picture (JPG, PNG, GIF - Max: 2MB). Leave empty to keep current picture.') }}
                                    </small>
                                    @error('profile_picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="name">{{ __('Full Name') }}</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="matric_no">{{ __('Matric Number') }}</label>
                            <input type="text" name="matric_no" class="form-control" 
                                   value="{{ old('matric_no', $user->matric_no) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="workplace">{{ __('Workplace') }}</label>
                            <input type="text" name="workplace" class="form-control" 
                                   value="{{ old('workplace', $user->workplace) }}" 
                                   placeholder="{{ __('Enter your workplace or internship location') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">{{ __('Current Role') }}</label>
                            <p class="form-control-plaintext">
                                @if($user->role == 'supervisor')
                                    <span class="badge bg-primary fs-6">{{ ucfirst($user->role) }}</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge bg-danger fs-6">{{ ucfirst($user->role) }}</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($user->role) }}</span>
                                @endif
                            </p>
                            <small class="form-text text-muted">{{ __('Contact an administrator to change your role') }}</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection