<!DOCTYPE html>
    <html>

    <head>
        <title> Mot de passe Oublié | Admin</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('loginAdmin') }}/css/css.css">
        <link rel="stylesheet" href="{{ asset('assets/css/all.css')}}"  />
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}"  />
    </head>

    <body>


        <div class="container">
            <div class="info">
            </div>
        </div>


        @if(session('notfound')=='o')

            <div class="form">
                <div class="thumbnail"><img src="{{ asset('loginAdmin') }}/image/logo.jpg"></div>

                <div class="alert alert-success py-3 my-2 " role="alert">
                    Un lien de reinitialisation de votre mot de passe est envoyé à votre boite de messagerie !
                </div>

                <div class="d-block my-3">
                <a name="" id="" class="btn btn-primary" href="{{ url('/') }}" role="button"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Retourner </a>
                </div>
                
            </div>

        @else
        <div class="form">
            <div class="thumbnail"><img src="{{ asset('loginAdmin') }}/image/logo.jpg"></div>
            <h3 class="h3"> Saisissez votre adresse Email : </h3>
            <form method="POST" class="mb-3 " action="{{ url('/forgot') }}">
                @csrf
                <input type="email" placeholder="adresse Email" name="email" />
                @if( $errors->has('email') )
                    <div class="alert alert-danger alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('email') }}
                    </div>
                @endif
                @if(session('notfound')=='x')
                <div class="alert alert-danger alert-dismissible fade show mt-n2" role="alert">
                    <button type="button" class="close float-right" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    Utilisateur Introuvable !
                </div>
                @endif
                <button type="submit">
                    Suivant
                </button>




            </form>
        <a href="{{ url('/') }}" class="d-block mx-auto mt-4  "> <i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i> Se-connecter plutot ? </a>
        </div>
        @endif

        <script src="{{asset('/assets/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/bootstrap.min.js')}}"></script>
    </body>

    </html>
