@extends('masterPage.master')



@section('title')
    Edit-etud | Admin
@endsection



@section('content')
 
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <h4 class="card-title"> Modification | Users</h4>
                </div>
                <div class="card-body">
              {{-- <div class="table-responsive"> --}}
                  {{-- hna zdet mn site bootstrap --}}
                  <form action="/listeUser-modifier/{{ $user->id}}" method="POST">

                      {{  csrf_field()  }}
                      {{  method_field('PUT')  }}

                    
                    <div class="form-group">
                        <label> E-mail </label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}"> 
                    </div>
                   
                    <div class="form-group">
                        <label> Type  </label>
                        <select name="type" class="form-control" >
                            <option value="etud" >Etudiant</option>
                            <option value="prof">Professeur</option>                        
                        </select> 

                        <label> Activation </label>
                        <select name="activation" class="form-control" >
                            <option value="1" >Activé</option>
                            <option value="0">Désactivé</option>                        
                        </select> 
                  

                    </div> 

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