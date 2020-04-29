@extends('masterPage.master')



@section('title')
    Liste des Etudiants 
@endsection



@section('content')
      

{{-- Modal --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Créer un Etudiant </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body">

        @if(count($emails)==0)

          <div class="alert alert-info py-3 px-2" role="alert">
              Créez d'abord un utilisateur de type Etudiant !
          </div>

        @else
          <form action="{{route('etud-insert')}}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="" class="col-form-label">Prenom :</label>
              <input type="text" maxlength="25" class="form-control" id="r" name="Fname">
            </div>
            @if( $errors->has('Fname') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('Fname') }}
                          </div>
            @endif


            <div class="form-group">
              <label for="" class="col-form-label">Nom :</label>
              <input type="text" maxlength="35" class="form-control" id="" name="Lname">
            </div>
            @if( $errors->has('Lname') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('Lname') }}
                          </div>
            @endif


            <div class="form-group">
              <label for="" class="col-form-label">Email :</label>
              <select name="email" class="form-control" >
                @foreach($emails as $email)
                  <option value="{{ $email->email }}">{{ $email->email }}</option>                        
                @endforeach
              </select> 
            </div>
            @if( $errors->has('email') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('email') }}
                          </div>
            @endif


            <div class="form-group">
              <label> Filiere: </label>
              <select name="filiere" class="form-control" >
                  <option value="GI" >GI</option>
                  <option value="GE">GE</option>                        
              </select> 
              @if( $errors->has('filiere') )
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first('filiere') }}
                </div>
              @endif
              <label> Année :</label>
              <select name="annee" class="form-control" >
                  <option value="1" >1er Année</option>
                  <option value="2">2éme Année</option>                        
              </select> 
              @if( $errors->has('annee') )
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first('annee') }}
                </div>
              @endif
            </div>


            <div class="form-group">
              <label for="" class="col-form-label">Date de naissance :</label>
              <input type="date"  class="form-control" id="" name="naissance">
            </div>
            @if( $errors->has('naissance') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('naissance') }}
                          </div>
            @endif


            <div class="form-group">
              <label> Sexe :</label>
              <select name="sex" class="form-control" >
                  <option value="M" >homme</option>
                  <option value="F">femme</option>                        
              </select> 
            </div>
            @if( $errors->has('sex') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('sex') }}
                          </div>
            @endif
            
            <div class="form-group">
              <label for="" class="col-form-label">CIN :</label>
              <input type="text" maxlength="12" minlength="8" class="form-control" id="" name="cin">
            </div>
            @if( $errors->has('cin') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('cin') }}
                          </div>
            @endif


            
              <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-times" aria-hidden="true"></i> Annuler </button>
          <button type="submit" class="btn btn-info"> <i class="fa fa-plus" aria-hidden="true"></i> Créer </button>
          </div>
          </form>
        @endif
        
      </div>
    
    </div>
  </div>
</div>


<div class="panel-header panel-header-sm">
</div>
      
      <div class="content">
        

<div class="row">

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title"> Liste Des Etudiant</h5>

          <button class="btn btn-info d-block mx-auto " type="button" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Ajouter Un Etudiant
          </button>

          @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
           </div>
          @endif
          @if(session('notfound'))
  <div class="alert alert-danger" role="alert">
    Etudiant introuvable !
 </div>
  @endif

        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table id="datatable" class="table">       
                <thead class=" text-primary">
                    <th> Id </th>
                    <th> Nom </th>
                    <th> Prenom </th>
                    <th> Email </th>
                    <th> Filiere </th>    
                    <th> Année </th>
                    <th> CIN </th>
                    <th> EDIT </th>   
                    <th> DELETE </th>      
                </thead>

                <tbody>
                  @foreach ($etudiants as $row)               
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->Fname }}</td>
                        <td>{{ $row->Lname }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->Filiere }}</td>                       
                        {{-- <td>{{ $row->Sex }}</td> --}}
                        <td>{{ $row->Annee }}</td>
                        <td>{{ $row->CIN }}</td>
                        <td>
                            <a href="/listeEtudiant-edit/{{ $row->id }}" class="btn btn success">EDIT</a>
                        </td>
                        <td>
                          <form action="/listeEtudiant-delete/{{$row->id }}" method="POST">
                            {{  csrf_field()  }}
                            {{  method_field('DELETE') }}
                          <button class="btn btn-danger deleting">DELETE</button>
                          </form>
                        </td>
                    </tr>
                  @endforeach
                </tbody>               
               </table>           
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>
  
  <?php if($errors->has('Lname')||$errors->has('Fname')||$errors->has('email')||$errors->has('cin')||$errors->has('naissance')||$errors->has('annee')||$errors->has('sex')||$errors->has('filiere')) 
echo '
$("#exampleModal").modal("show");
';
?>
$(".deleting").click(function(e){
  e.preventDefault();
  let i = confirm(' Vous supprimerez toutes les données relatives à cet Etudiant, Continuer ? ');
  if(i){
    e.target.closest('form').submit();
  }
});

$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection