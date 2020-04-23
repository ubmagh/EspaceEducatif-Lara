@extends('masterPage.master')


@section('title')
    Profiles | Etudiants
@endsection


@section('content')
<div class="panel-header panel-header-sm">
  {{-- sdfsdfsdfsdfsdfsdfs --}}
</div>

<div class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="title">Edit Profile  </h5>
        </div>
        <div class="card-body">
          <form action="/listeEtudiant-modifier/{{$users->id}}" method="POST">

            {{  csrf_field()  }}
            {{  method_field('PUT')  }}

            <div class="row">
              <div class="col-md-5 pr-1">
                <div class="form-group">
                  <label>ID</label>
                  <input type="text" class="form-control" disabled="" value="{{$users->id}}">
                </div>
              </div>
              <div class="col-md-3 px-1">
                <div class="form-group">
                  <label> Nom </label>
                  <input type="text" class="form-control" name="nom" value="{{ $users->Fname }}">
                </div>
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>Prénom</label>
                  <input type="text" class="form-control" name="prenom" value="{{ $users->Lname }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 pr-1">
                <div class="form-group">
                  <label>CIN</label>
                    <input type="text" class="form-control" name="cin" value="{{ $users->CIN }}">
                </div>
              </div>
              <div class="col-md-6 pl-1">
                <div class="form-group">
                  <label>Date de Naissance</label>
                  <input type="text" class="form-control" name="naissance" value="{{$users->dateNaissance}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>E-mail</label>
                  <input type="email" class="form-control" name="email" value="{{ $users->email }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 pr-1">
                <div class="form-group">
                  <label>Année</label>
                     <select name="annee" class="form-control" >
                        <option value="1" >1</option>
                        <option value="2">2</option>                        
                    </select> 
                </div>
              </div>
              <div class="col-md-4 px-1">
                <div class="form-group">
                  <label>Sex</label>
                     <select name="sex" class="form-control" >
                      <option value="M" >M</option>
                      <option value="F">F</option>                        
                    </select> 
                </div>
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>Filière </label>
                     <select name="filiere" class="form-control" >
                       <option value="GI" >GI</option>
                      <option value="GE">GE</option>                        
                     </select> 

                </div>
              </div>
            </div>
           
{{-- hadi yalah ztha --}}
            <button type="submit" class="btn btn-success">Modifier</button>
            <a href="/liste-etudiant" class="btn btn-danger">Annuler</a>
          </form>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-user">
        <div class="image">
          <img src="../assets/img/bg5.jpg" alt="...">
        </div>
        <div class="card-body">
          <div class="author">
          
              <img class="avatar border-gray" src="{{ asset('images/Avatars/' .$users->AvatarPath)}}" alt="Image">
              <h5 class="title">{{ $users->Fname }} {{ $users->Lname }}</h5>
                      
          </div>
          <p class="description text-center">
            Etudiant <br>
            {{ $users->Annee }} Année<br>
            {{ $users->Filiere}}
          </p>
        </div>
        <hr>
        <div class="button-container">
          <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
            <i class="fab fa-facebook-f"></i>
          </button>
          <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
            <i class="fab fa-twitter"></i>
          </button>
          <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
            <i class="fab fa-google-plus-g"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>
// hadi dial recherche
$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection