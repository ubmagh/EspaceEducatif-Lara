<!DOCTYPE html>
    <html>

    <head>
        <title>Login | Admin</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('loginAdmin') }}/css/css.css">
    </head>

    <body>


        <div class="container">
            <div class="info">
                <h1>Administrateur</h1>
            </div>
        </div>
        <div class="form">
            <div class="thumbnail"><img src="{{ asset('loginAdmin') }}/image/logo.jpg"></div>
            <form method="POST" class="mb-3 " action="{{ route('login') }}">
                @csrf
                {{-- <form class="login-form"> --}}
                <input type="email" placeholder="E-MAIL" name="email" />
                <input type="password" placeholder="PASSWORD" name="password" />
                {{-- <button>
        LOGIN
    </button>   --}}

                <button type="submit">
                    {{ __('Login') }}
                </button>




            </form>
        <a href="{{ url('/forgot') }}" class="d-block mx-auto mt-4  "> <i class="fas fa-question"></i> Mot de passe oublié ? </a>
        </div>
    </body>

    </html>
