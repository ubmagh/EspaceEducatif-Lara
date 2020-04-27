@extends('masterPage.master')



@section('title')
    Edit-etud | Admin
@endsection



@section('content')
 
    <div class="panel-header panel-header-sm">
    </div>
    <div class="row col-md-11 mx-auto">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <h4 class="card-title"> Modification | Users</h4>
                </div>
                <div class="card-body">
              {{-- <div class="table-responsive"> --}}
                  {{-- hna zdet mn site bootstrap --}}
                  <form action="/listeUser-modifier/{{ $user->id}}" class="col-md-10 mx-auto" method="POST">

                      {{  csrf_field()  }}
                      {{  method_field('PUT')  }}

                    
                    <div class="form-group">
                        <label> E-mail </label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}"> 
                    </div>
                    @if( $errors->has('email') )
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                   
                    <div class="form-group">
                        

                        <label> Activation </label>
                        <select name="activation" class="form-control" >
                            <option value="1" >Activé</option>
                            <option value="0">Désactivé</option>                        
                        </select> 
                  

                    </div> 
                    @if( $errors->has('activation') )
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('activation') }}
                        </div>
                    @endif

                   <button type="submit" class="btn btn-success">Modifier</button>
                   <a href="/liste-etudiant" class="btn btn-danger">Annuler</a>
                   </form>
                </div>
            </div>
        </div>
    </div>    

@endsection

@section('scripts')
    
@endsection