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
                @if ($errors->has('nom'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('nom') }}
                  </div>
                @endif
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>Prénom</label>
                  <input type="text" class="form-control" name="prenom" value="{{ $users->Lname }}">
                </div>
                @if ($errors->has('prenom'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('prenom') }}
                  </div>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 pr-1">
                <div class="form-group">
                  <label>CIN</label>
                    <input type="text" class="form-control" name="cin" value="{{ $users->CIN }}">
                </div>
                @if ($errors->has('cin'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('cin') }}
                  </div>
                @endif
                @if (session('cin')=='taken')
                  <div class="alert alert-warning" role="alert">
                   Cin enregistré déja pour un autre étudiant
                  </div>
                @endif
              </div>
              <div class="col-md-6 pl-1">
                <div class="form-group">
                  <label>Date de Naissance</label>
                  <input type="date" class="form-control" name="naissance" value="{{$users->dateNaissance}}">
                </div>
                @if ($errors->has('naissance'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('naissance') }}
                  </div>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label> Permissions:  </label>
                  <div class="row">
                    <div class="col-md text-center">
                      <input type="checkbox" name="publier" class="" id="exampleCheck1" {{ $permissions->posting ? 'checked':'' }} >
                      <label class="" for="exampleCheck1">Publier</label>
                    </div>                   
                    <div class="col-md text-center ">
                      <input type="checkbox" name="commenter" class="" id="exampleCheck2" {{ $permissions->commenting ? 'checked':'' }}>
                      <label class="" for="exampleCheck2">Commenter</label>
                    </div>
                  </div>
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
                @if ($errors->has('annee'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('annee') }}
                  </div>
                @endif
              </div>
              <div class="col-md-4 px-1">
                <div class="form-group">
                  <label>Sex</label>
                     <select name="sex" class="form-control" >
                      <option value="M" >M</option>
                      <option value="F">F</option>                        
                    </select> 
                </div>
                @if ($errors->has('sex'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('sex') }}
                  </div>
                @endif
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>Filière </label>
                     <select name="filiere" class="form-control" >
                       <option value="GI" >GI</option>
                        <option value="GE">GE</option>                        
                     </select> 

                </div>
                @if ($errors->has('filiere'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('filiere') }}
                  </div>
                @endif
              </div>
            </div>
           
            <button type="submit" class="btn btn-success">Modifier</button>
            <a href="/liste-etudiant" class="btn btn-danger">Annuler</a>
          </form>

        </div>
      </div>
    </div>
    <div class="col-md-4 py-3">
      <div class="card card-user ">
        <div class="image" style="background-color: rgb(14,41,73);">
          
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