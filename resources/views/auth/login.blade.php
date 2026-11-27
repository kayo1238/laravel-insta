@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1 class="text-center honk-logo display-1 mb-3">Insta</h1>
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body sns-card p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-4">

                            <div class="col">
                                <input id="email" type="email" class="form-control sns-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">

                            <div class="col">
                                <input id="password" type="password" class="form-control sns-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col">
                                <button type="submit" class="btn btn-lg w-100">
                                    {{ __('Login') }}
                                </button>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
