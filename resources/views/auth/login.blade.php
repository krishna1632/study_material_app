<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        main {
            height: 100vh;
        }

        .login {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 32rem;
        }

        .form-section {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-image: url('/assets/image/login.jpg');
            background-size: cover;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>

<body>
    <main>
        <section class="login">
            <div class="login-container">
                <div class="form-section">
                    <div class="text-center mb-4">
                        <img src="/assets/image/Ramanujan_College_Logo.jpg" alt="Logo" style="height: 100px;">
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-primary">Email</label>
                            <input id="email" type="email" name="email" class="form-control"
                                value="{{ old('email') }}" required autofocus placeholder="Enter registered email">
                            @if ($errors->has('email'))
                                <div class="text-danger fw-bold mt-2">
                                    {{ __('These credentials do not match our records.') }}
                                </div>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-primary">Password</label>
                            <input id="password" type="password" name="password" class="form-control" required
                                placeholder="Enter your password">
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label text-secondary">
                                Remember me
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Login</button>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-danger">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
