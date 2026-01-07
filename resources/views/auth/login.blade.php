@extends('layouts.app')

@section('content')

<div class="card-container">
    <div class="card login-card">
        <div class="card-header text-center fw-bold">
            {{ __('Logbook System') }}
            <p class="mb-0 mt-1" style="font-size: 0.7rem; font-weight: normal; color: #6c757d;">
                {{ __('Please login using your official credentials.') }}
            </p>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <div class="input-group input-group-lg"> 
                        
                        <input id="email" 
                            type="text" 
                            class="form-control form-control-lg @error('email') is-invalid @enderror border-start-0"
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Email" 
                            required 
                            autofocus
                            style="font-size: 0.9rem;">
                    </div>
                    
                    @error('email')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group input-group-lg">
                        
                        <input id="password" 
                            type="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror border-start-0"
                            name="password"
                            placeholder="Password"
                            required
                            autofocus
                            style="font-size: 0.9rem;">
                    </div>

                    @error('password')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Login') }}
                </button>

                <div> <br></div>

                <div class="d-flex justify-content-between align-items-center mb-2">

                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
