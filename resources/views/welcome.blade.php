<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        main {
            height: 100vh
        }

        .login {
            /* background-color:red; */
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;



        }

        .login_in {
            width: 32rem;
            height: 56%;

        }

        .b {

            padding: 0 20px;
        }

        .LOgin_in_in {}

        .pages_ {
            background-color: rgba(255, 255, 255, 0.9);
            /* background-image:linear-gradient(to left,rgba(255,255,0,0.8),blue,yellow); */
        }
    </style>
</head>

<body style=" background-image: url('/assets/image/login.jpg');  background-size: cover; height:100vh;">

    <main>
        <section class="login  ">
            <div class=" login_in  ">
                <div class="LOgin_in_in ">

                    <div class=" pages_ rounded-3  shadow-lg justify-center items-center">
                        <div class="d-flex justify-content-center ">
                            <img class="m-4" style="height:100px" src="/assets/image/Ramanujan_College_Logo.jpg"
                                alt="">
                        </div>
                        <form method="POST" action="{{ route('login') }}" class=" b">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" :value="'Email '" class="form-label text-primary " />
                                <x-text-input id="email" class="form-control" type="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username"
                                    placeholder="Enter register email" autocomplete="off" />


                            </div>

                            <!-- Password -->
                            <div class="mt-4">
                                <x-input-label for="password" :value="'Password'" class="form-label text-primary" />

                                <x-text-input id="password" class="form-control" type="password" name="password"
                                    required autocomplete="current-password" placeholder="Enter your password" />

                                @if ($errors->has('email'))
                                    <div class="text-danger fw-bold mb-3">
                                        {{ __('These credentials do not match our records.') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Remember Me -->
                            <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        name="remember">
                                    <span
                                        class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                                </label>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4 mb-3">


                                <button type="submit" class="btn btn-primary px-4 mb-2 fw-bold">Login</button>


                                @if (Route::has('password.request'))
                                    <a class="text-danger" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
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
