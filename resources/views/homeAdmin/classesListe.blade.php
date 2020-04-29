@extends('masterPage.master')



@section('title')
    Liste des Classes
@endsection



@section('content')
      

{{-- Modal --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Créer une Classe </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body">

        
          <form action="{{route('classe-insert')}}" method="get">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="" class="col-form-label">Nom du classe :</label>
              <input type="text" maxlength="50" class="form-control"  name="nom">
            </div>
            @if( $errors->has('nom') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('nom') }}
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
              <label> Professeur :</label>
              <select name="prof" class="form-control" >
                  @foreach ($profs as $prof)
                <option value="{{ $prof->id }}"> {{$prof->Lname.' '.$prof->Fname}} </option>
                  @endforeach                     
              </select> 
            </div>
            @if( $errors->has('prof') )
                          <div class="alert alert-danger" role="alert">
                              {{ $errors->first('prof') }}
                          </div>
            @endif
            
           


            
              <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-times" aria-hidden="true"></i> Annuler </button>
          <button type="submit" class="btn btn-info"> <i class="fa fa-plus" aria-hidden="true"></i> Créer </button>
          </div>
          </form>
        
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
          <h5 class="card-title"> Liste Des Classes </h5>

          <button class="btn btn-info d-block mx-auto " type="button" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Créer Une Classe
          </button>

          @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
           </div>
          @endif
          @if(session('notfound'))
            <div class="alert alert-danger" role="alert">
                Classe introuvable !
            </div>
            @endif

        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table id="datatable" class="table">       
                <thead class=" text-primary">
                    <th> Id </th>
                    <th> Nom </th>
                    <th> Filière </th>
                    <th> Année </th>
                    <th> Professeur </th>    
                    <th> EDIT </th>   
                    <th> DELETE </th>      
                </thead>

                <tbody>
                  @foreach ($classes as $row)               
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->ClasseName }}</td>
                        <td>{{ $row->Filiere }}</td>
                        <td>{{ $row->Annee }}</td>
                        <td>{{ $row->ProfID }}</td>                       
                        <td>
                            <a href="/Classes/{{ $row->id }}" class="btn btn success">EDIT</a>
                        </td>
                        <td>
                          <form action="/Classes-delete/{{$row->id }}" method="POST">
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
  
  <?php if($errors->has('nom')||$errors->has('annee')||$errors->has('filiere')||$errors->has('prof')) 
echo '
$("#exampleModal").modal("show");
';
?>
$(".deleting").click(function(e){
  e.preventDefault();
  let i = confirm(' Vous risquerez de supprimer toutes les données relatives à cette Classe, Continuer ? ');
  if(i){
    e.target.closest('form').submit();
  }});

$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection