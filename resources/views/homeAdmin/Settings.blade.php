@extends('masterPage.master')



@section('title')
    Paramètres de Compte | Admin 
@endsection




@section('content')

<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-11 mx-auto">
            <div class="card">
                <div class="card-header">
                     <h4 class="card-title"> Modifier les information de l'administrateur </h4>
                </div>
                <div class="card-body">
              {{-- <div class="table-responsive"> --}}

                  <h2 class=" text-center h4 "> Votre dernier Connexion à : {{ substr($user->LastLogin,0,16) }} </h2>

                  @if ( session('error')!=null )

                    @if(session('error')=='false')
                        <div class="alert alert-success" role="alert">
                            Vos données sont bien mis-à-jour !
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                                La modification de vos données est échouée
                        </div>
                    @endif
                  @endif
              
                  <form action="{{ url('/Settings') }}" class="col-md-10 mx-auto align-content-center align-items-center mb-3" method="POST">

                      {{  csrf_field()  }}
                      {{  method_field('post')  }}

                    <div class="form-group">
                        <label>  Email </label>
                    <input type="text" class="form-control" name="email" minlength="6" value="{{ $user->email }}"> 
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-primary alert-dismissible fade show mt-n2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    

                    <div class="form-group">
                        <label> Mot de passe </label>
                        <input type="password" class="form-control" name="pwd" minlength="6" value=""> 
                    </div> 
                    @if ($errors->has('pwd'))
                    <div class="alert alert-primary alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('pwd') }}
                    </div>
                    @endif

                    
                    <div class="form-group">
                        <label>  Confirmez le mot de passe  </label>
                        <input type="password" class="form-control" name="pwdC" value=""> 
                    </div>
                    @if ($errors->has('pwdC'))
                    <div class="alert alert-primary alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('pwdC') }}
                    </div>
                    @endif


                    <div class="form-group">
                        <label>  Ancien mot de passe  </label>
                        <input type="password" class="form-control" name="pwdA" minlength="6" value=""> 
                    </div>
                    @if ($errors->has('pwdA'))
                    <div class="alert alert-primary alert-dismissible fade show mt-n2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        {{ $errors->first('pwdA') }}
                    </div>
                    @endif
                    
                   
                   
                     
                   <button type="submit" class="btn btn-success">Modifier</button>
                   <a href="/liste-etudiant" class="btn btn-danger">Annuler</a>
                   </form>
                </div>
            </div>
        </div>
    </div>    
</div>  

@endsection

@section('scripts')


    
@endsection