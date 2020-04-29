@extends('masterPage.master')


@section('title')
    Modifier une CLasse
@endsection


@section('content')
<div class="panel-header panel-header-sm">
</div>

<div class="content">
  <div class="row">
    <div class="col-md-10 mx-auto px-3">
      <div class="card">
        <div class="card-header">
          <h5 class="title"> Modifier une Classe </h5>
        </div>
        <div class="card-body">
          <form action="{{ url('/Classes/'.$classe->id) }}" method="POST">

            {{  csrf_field()  }}
            {{  method_field('PUT')  }}

            <div class="row">
              <div class="col-md-5 pr-1">
                <div class="form-group">
                  <label>ID</label>
                  <input type="text" class="form-control" disabled="" value="{{$classe->id}}">
                </div>
              </div>
              <div class="col-md-3 px-1">
                <div class="form-group">
                  <label> Nom de la classe :</label>
                  <input type="text" class="form-control" name="nom" value="{{ $classe->ClasseName }}">
                </div>
                @if ($errors->has('nom'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('nom') }}
                  </div>
                @endif
              </div>


              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>le professeur :</label>
                  <select name="prof" class="form-control" >
                  @foreach ($profs as $prof)
                    @if($prof->id==$classe->ProfID)
                      <option value="{{ $prof->id }}" selected>{{ $prof->Lname.' '.$prof->Fname }}</option>
                    @else
                      <option value="{{ $prof->id }}">{{ $prof->Lname.' '.$prof->Fname }}</option>
                    @endif
                  @endforeach
                  </select>
                </div>
                @if ($errors->has('prof'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('prof') }}
                  </div>
                @endif
              </div>
            </div>
            </div>
            <div class="row">
              <div class="col-1"></div>
              <div class="col-md-4 px-1">
                <div class="form-group">
                  <label>Année :</label>
                     <select name="annee" class="form-control" >
                      <option value="1" {{ ($classe->Annee.''=='1')?'selected':'' }} >1</option>
                      <option value="2" {{ ($classe->Annee.''=='2')?'selected':'' }} >2</option>                        
                    </select> 
                </div>
                @if ($errors->has('annee'))
                  <div class="alert alert-warning" role="alert">
                   {{ $errors->first('annee') }}
                  </div>
                @endif
              </div>
              <div class="col-md-4 pl-1">
                <div class="form-group">
                  <label>Filière </label>
                     <select name="filiere" class="form-control" >
                       <option value="GI" {{ ($classe->Filiere=='GI')? 'selected':'' }} >GI</option>
                        <option value="GE" {{ ($classe->Filiere=='GE')? 'selected':'' }} >GE</option>                        
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
          <a href="{{ url('/Classes') }}" class="btn btn-danger">Annuler</a>
          </form>

        </div>
      </div>
    </div>
    
  </div>
</div>

@endsection
