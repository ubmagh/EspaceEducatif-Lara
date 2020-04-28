<!DOCTYPE html>
    <html>

    <head>
        <title> Réinitialisation de mot de passe | Admin</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('loginAdmin') }}/css/css.css">
        <link rel="stylesheet" href="{{ asset('assets/css/all.css')}}"  />
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}"  />
    </head>

    <body>


        <div class="container">
            <div class="info">
            </div>
        </div>
        <div class="form">
            <div class="thumbnail"><img src="{{ asset('loginAdmin') }}/image/logo.jpg"></div>
            <h3 class="h3 text-success">  Mot de passe Bien Changé  </h3>
        <a href="{{ url('/') }}" class="d-block mx-auto mt-4  "> <i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i> Se-connecter  </a>
        </div>
        <script src="{{asset('/assets/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/bootstrap.min.js')}}"></script>
    </body>

    </html>
