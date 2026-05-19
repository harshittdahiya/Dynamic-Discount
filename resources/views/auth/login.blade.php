@extends('layouts.frontend')

@section('content')
@php
    $openRegister = ($initialMode ?? 'login') === 'register'
        || old('name')
        || $errors->has('name')
        || $errors->has('password_confirmation');
@endphp

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="auth-shell reveal">
                <div class="auth-shell-copy d-none d-lg-flex">
                    <div>
                        <p class="auth-kicker mb-3">Fresh Deals, Better Cart Value</p>
                        <h1 class="display-6 fw-bold text-white mb-3">Your Next Favorite Discount Is Waiting</h1>
                        <p class="text-white-50 mb-0">Sign in or create your account to unlock exclusive offers, faster checkout, and the latest savings from DiscountStore.</p>
                    </div>
                </div>

                <div class="auth-stage {{ $openRegister ? 'show-register' : '' }}" id="authStage">
                    <div class="auth-face auth-face-login">
                        <div class="auth-card">
                            <h2 class="fw-bold mb-2">{{ __('Login') }}</h2>
                            <p class="text-muted mb-4">Continue with your account credentials.</p>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="you@example.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a class="small fw-semibold text-decoration-none" href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                                    @endif
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Login') }}</button>
                                </div>
                            </form>

                            <p class="text-muted text-center mt-4 mb-0">
                                New here?
                                <button type="button" class="btn btn-link p-0 fw-semibold text-decoration-none align-baseline" data-flip="register">Create account</button>
                            </p>
                        </div>
                    </div>

                    <div class="auth-face auth-face-register">
                        <div class="auth-card">
                            <h2 class="fw-bold mb-2">{{ __('Register') }}</h2>
                            <p class="text-muted mb-4">Create your account in under a minute.</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your full name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="register-email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                                    <input id="register-email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="register-password" class="form-label fw-semibold">{{ __('Password') }}</label>
                                    <input id="register-password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="At least 8 characters">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password">
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Register') }}</button>
                                </div>
                            </form>

                            <p class="text-muted text-center mt-4 mb-0">
                                Already have an account?
                                <button type="button" class="btn btn-link p-0 fw-semibold text-decoration-none align-baseline" data-flip="login">Sign in</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-shell {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 26px 60px rgba(16, 35, 63, 0.2);
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(5px);
    }

    .auth-shell-copy {
        background: linear-gradient(140deg, #0f62fe 0%, #11b39c 60%, #0f62fe 100%);
        padding: 3rem;
        min-height: 260px;
        align-items: center;
    }

    .auth-kicker {
        display: inline-block;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        letter-spacing: 1px;
        font-size: 0.78rem;
        text-transform: uppercase;
        font-weight: 700;
    }

    .auth-stage {
        position: relative;
        perspective: 1400px;
        min-height: 640px;
        background: transparent;
    }

    .auth-face {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        transform-style: preserve-3d;
        transition: transform 700ms cubic-bezier(0.22, 1, 0.36, 1), opacity 500ms ease;
        padding: 2rem;
        display: flex;
        align-items: center;
    }

    .auth-face-login {
        transform: rotateY(0deg);
        opacity: 1;
    }

    .auth-face-register {
        transform: rotateY(180deg);
        opacity: 0;
    }

    .auth-stage.show-register .auth-face-login {
        transform: rotateY(-180deg);
        opacity: 0;
    }

    .auth-stage.show-register .auth-face-register {
        transform: rotateY(0deg);
        opacity: 1;
    }

    .auth-card {
        width: 100%;
        background: #fff;
        border: 1px solid rgba(16, 35, 63, 0.12);
        border-radius: 1.4rem;
        box-shadow: 0 12px 30px rgba(16, 35, 63, 0.1);
        padding: 1.5rem;
    }

    .form-control-lg {
        border-radius: 14px;
        border-color: rgba(16, 35, 63, 0.17);
    }
    .form-control-lg:focus {
        border-color: rgba(15, 98, 254, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(15, 98, 254, 0.15);
    }

    @media (min-width: 992px) {
        .auth-shell {
            grid-template-columns: 5fr 7fr;
        }

        .auth-stage {
            min-height: 700px;
        }

        .auth-card {
            padding: 2rem;
        }
    }

    @media (max-width: 991px) {
        .auth-stage {
            min-height: 720px;
        }

        .auth-face {
            padding: 1.25rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stage = document.getElementById('authStage');

        if (!stage) {
            return;
        }

        const setMode = (mode) => {
            stage.classList.toggle('show-register', mode === 'register');
        };

        document.querySelectorAll('[data-flip]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const target = trigger.getAttribute('data-flip');
                setMode(target);
            });
        });

        document.querySelectorAll('[data-auth-toggle]').forEach((trigger) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                const target = trigger.getAttribute('data-auth-toggle');
                setMode(target);
            });
        });
    });
</script>
@endsection
