@extends('masterPage.master')



@section('title')
    Liste des professeurs
@endsection



@section('content')

{{-- Modal --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Créer un Professeur </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body">

        @if(count($emails)==0)

          <div class="alert alert-info py-3 px-2" role="alert">
              Créez d'abord un utilisateur de type Professeur !
          </div>

        @else
          <form action="{{route('prof-insert')}}" method="POST">
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
              
            </div>



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
              <label for="" class="col-form-label">Matière :</label>
              <input type="text" maxlength="30" class="form-control" id="" name="matiere">
            </div>
            @if( $errors->has('matiere') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('matiere') }}
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
          <h5 class="card-title"> Liste Des Professeurs</h5>

          <button class="btn btn-info d-block mx-auto " type="button" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Ajouter Un Professeur
          </button>

          @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
           </div>
          @endif
          @if(session('notfound'))
  <div class="alert alert-danger" role="alert">
    Professeur introuvable !
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
                    <th> Sex </th>
                    <th> Matiere </th>
                    <th> EDIT </th>   
                    <th> DELETE </th>      
                </thead>

                <tbody>
                  @foreach ($professeur as $row)               
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->Fname }}</td>
                        <td>{{ $row->Lname }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->Filiere }}</td>                       
                        <td>{{ $row->Sex }}</td>
                        <td>{{ $row->Matiere }}</td>
                        
                        <td>
                            <a href="/listeProfesseur-edit/{{ $row->id }}" class="btn btn success">EDIT</a>
                        </td>
                        <td>
                          <form action="/listeprof-delete/{{$row->id }}" method="POST">
                            {{  csrf_field()  }}
                            {{  method_field('DELETE') }}
                          <button type="submit" class="btn btn-danger deleting">DELETE</button>
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
  let i = confirm(' Vous supprimerez toutes les données relatives à ce Professeur, Continuer ? ');
  if(i){
    e.target.closest('form').submit();
  }
});

$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection