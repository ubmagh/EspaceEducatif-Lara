@extends('masterPage.master')


@section('title')
    Modifier un Professeur
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
          <h5 class="title"> Modifier un Professeur </h5>
        </div>
        <div class="card-body">
          <form action="{{ url('/listeProfesseur-modifier/'.$professeur->id) }}" method="POST">

            {{  csrf_field()  }}
            {{  method_field('PUT')  }}

            <div class="row">
              <div class="col-md-5 pr-1">
                <div class="form-group">
                  <label>ID</label>
                  <input type="text" class="form-control" disabled="" value="{{$professeur->id}}">
                </div>
              </div>
              <div class="col-md-3 px-1">
                <div class="form-group">
                  <label> Nom </label>
                  <input type="text" class="form-control" name="nom" value="{{ $professeur->Lname }}">
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
                  <input type="text" class="form-control" name="prenom" value="{{ $professeur->Fname }}">
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
                  <label>Matiere</label>
                    <input type="text" class="form-control" name="Matiere" value="{{ $professeur->Matiere }}">
                </div>
                @if ($errors->has('matiere'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('matiere') }}
                  </div>
                @endif
              </div>
              <div class="col-md-6 pl-1">
                
              </div>
            </div>
            <div class="row">
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
        <a href="{{ url('/liste-professeur') }}" class="btn btn-danger">Annuler</a>
          </form>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-user ">
        <div class="image" style="background-color: rgb(14,41,73);">
          
        </div>
        <div class="card-body">
          <div class="author">
          
              <img class="avatar border-gray" src="{{ asset('images/Avatars/' .$professeur->AvatarPath)}}" alt="Image">
              <h5 class="title">{{ $professeur->Fname }} {{ $professeur->Lname }}</h5>
                      
          </div>
          <p class="description text-center">
            {{ $professeur->Filiere}}
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