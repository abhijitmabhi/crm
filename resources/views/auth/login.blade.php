@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="lli-card-header lli-card-title text-center">Login</div>

                    <div class="card-body pb-0">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('auth.email') }}</label>

                                <div>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-form-label">{{ __('auth.password') }}</label>

                                <div>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                               checked hidden>

                                        {{--                                    <label class="form-check-label" for="remember">--}}
                                        {{--                                        {{ __('auth.remember') }}--}}
                                        {{--                                    </label>--}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex flex-column align-items-center mt-5">
                                <button type="submit" class="btn btn-primary col-md-6">
                                    {{ __('auth.login') }}
                                </button>
                            </div>

                            <div class="form-group d-flex flex-column align-items-center">
                                <a href="{{ route('password.request') }}">
                                    {{ __('auth.forgotpw') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
