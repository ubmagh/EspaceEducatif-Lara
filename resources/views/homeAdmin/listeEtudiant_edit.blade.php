@extends('masterPage.master')



@section('title')
    Edit-etud | Admin
@endsection



@section('content')

<div class="panel-header panel-header-sm">
    sdfsdfsdfsdfsdfsdfs
</div>
sqdkjsqjkjqdsknlqslk
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <h4 class="card-title"> Modification | Etudiant</h4>
                </div>
                <div class="card-body">
              {{-- <div class="table-responsive"> --}}
                  {{-- hna zdet mn site bootstrap --}}
                  <form action="/listeEtudiant-modifier/{{$users->id}}" method="POST">

                      {{  csrf_field()  }}
                      {{  method_field('PUT')  }}

                    <div class="form-group">
                        <label>  Nom </label>
                        <input type="text" class="form-control" name="nom" value="{{ $users->Fname }}"> 
                    </div>
                    
                    <div class="form-group">
                        <label> Prénom </label>
                        <input type="text" class="form-control" name="prenom" value="{{ $users->Lname }}"> 
                    </div> 
                    
                    <div class="form-group">
                        <label>  E-mail </label>
                        <input type="email" class="form-control" name="email" value="{{ $users->email }}"> 
                    </div>
                   
                    <div class="form-group">
                        <label> Filiere </label>
                        <select name="filiere" class="form-control" >
                            <option value="GI" >GI</option>
                            <option value="GE">GE</option>                        
                        </select> 

                        <label> Sex </label>
                        <select name="sex" class="form-control" >
                            <option value="M" >M</option>
                            <option value="F">F</option>                        
                        </select> 

                        <label> Année </label>
                        <select name="annee" class="form-control" >
                            <option value="1" >1</option>
                            <option value="2">2</option>                        
                        </select> 

                    </div> 
                    
                    <div class="form-group">
                        <label> CIN </label>
                        <input type="text" class="form-control" name="cin" value="{{ $users->CIN }}"> 
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