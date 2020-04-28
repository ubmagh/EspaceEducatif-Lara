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
            <h3 class="h3"> Saisissez votre nouveau Mot de Passe  </h3>
            <form method="POST" class="mb-3 " action="{{ url('/Reset') }}">
                @csrf
            <input type="hidden" name="res_Token" value="{{ $res_Token }}" />
            <input type="hidden" name="res_email" value="{{ $res_email }}" />
                <input type="password" placeholder="Nouveau mot de passe" name="password1" />
                @if( $errors->has('password1') )
                    <div class="alert alert-danger alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('password1') }}
                    </div>
                @endif

                <input type="password" placeholder="Confirmer le mot de passe" name="password2" />
                @if( $errors->has('password2') )
                    <div class="alert alert-danger alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('password2') }}
                    </div>
                @endif
                

                <button type="submit">
                    <i class="fa fa-check" aria-hidden="true"></i> Valider
                </button>




            </form>
        <a href="{{ url('/') }}" class="d-block mx-auto mt-4  "> <i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i> Annuler et Retourner  </a>
        </div>
        <script src="{{asset('/assets/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/bootstrap.min.js')}}"></script>
    </body>

    </html>
