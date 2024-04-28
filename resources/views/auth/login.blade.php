<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Smart Lock Voltage Monitoring</title>
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/auth.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/files/logo.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/files/logo.png')}}" type="image/png">
    <style type="text/css">
        #auth #auth-left .auth-logo {
            margin-bottom:20px !important;
        }
        .logo-login {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            width: 100px !important;
            height: 100px !important;
        }
        .auth-logo {
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="auth">
          <form method="POST" action="{{ route('login') }}">
                        @csrf

<div class="row h-100">
    <div class="col-lg-5 col-12 mx-auto">
        <div id="auth-left">
            <div class="auth-logo">

            <img src="{{asset('assets/files/logo.png')}}" class="logo-login" alt="Logo">
        <h4>Monitoring Smart Lock Voltage | Login</h4>
            </div><!-- 
            <h4 class="auth-title">.</h4> -->
        
                <div class="form-group position-relative has-icon-left mb-4">

                     <input id="username" type="text" class="form-control form-control-xl @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>

                      @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                     <input id="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" name="password" required placeholder="password" autocomplete="current-password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                      @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-1" type="submit">Log in</button>
       
        </div>
    </div>
</div>

</form>

    </div>
</body>

</html>
