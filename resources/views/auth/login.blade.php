<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10" style="padding-bottom: 50px;">
            <div class="card shadow">
                <div class="row">
                    <div class="col-md-7">
                        <div style="background-color: #e8ffe0;height:100%;padding-top:80px;">
                            <div class="justify-content-center" id="form-logo">
                                <img id="logo" src="{{ asset('images/logo.png') }}" alt="Logo">
                            </div>
                            <div style="padding-bottom:50px;">
                                <h2 id="wellcome">Selamat Datang</h2>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-5" style="padding:30px 30px 0px 30px;">
                        <h4 id="wellcome">Masuk</h4>
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <span id="messageLogin">{{ session()->get('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{ url('login') }}">
                            @csrf
                            <div class="form-group mt-4">
                                <label for="username" class="col-md-8 col-form-label text-md-right">Username / Email</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                 @enderror
                            </div>

                            <div class="form-groupmt-2">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Kata Sandi</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                       Ingat Saya
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4" id="btnLogin">
                                Masuk
                            </button>
                            <br><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
