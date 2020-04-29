@extends('masterPage.master')



@section('title')
    Liste des utilisateurs
@endsection



@section('content')
    

{{-- Modal --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Créer un utilisateur </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
      </div>
      <div class="modal-body">
        <form action="{{route('user-insert')}}" method="POST">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Email</label>
            <input type="email" class="form-control" id="recipient-name" name="email">
          </div>
          @if( $errors->has('email') )
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('email') }}
                        </div>
          @endif
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Password</label>
            <input type="password" class="form-control" id="recipient-name" name="password" placeholder="">
          </div>
          @if( $errors->has('password') )
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('password') }}
                        </div>
          @endif
          <div class="form-group">
            <label> Type d'utilisateur : </label>
            <select name="type" class="form-control" >
                <option value="prof" >Professeur</option>
                <option value="etud">Etudiant</option>                        
            </select> 
            @if( $errors->has('type') )
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('type') }}
            </div>
            @endif
           <label> Activation de compte :</label>
           <select name="activation" class="form-control" >
              <option value="1" >Activé</option>
              <option value="0">Désactivé</option>                        
           </select> 
           @if( $errors->has('activation') )
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('activation') }}
            </div>
            @endif
           </div>
           
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
          <h4 class="card-title"> Liste Des Utilisateurs</h4>

         
        
  <button class="btn btn-info d-block mx-auto " type="button" data-toggle="modal" data-target="#exampleModal">
    <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Ajouter Un Utilisateur
  </button>
         
  @if (session('status'))
  <div class="alert alert-success" role="alert">
      {{ session('status') }}
   </div>
  @endif
  @if(session('notfound'))
  <div class="alert alert-danger" role="alert">
    Utilisateur introuvable !
 </div>
  @endif



        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table id="datatable" class="table">       
                <thead class=" text-primary">
                    <th> Id </th>
                    <th> E-mail </th>
                    <th> Créé le  </th>
                    <th> Type </th>
                    <th> Activation </th>
                    <th> EDIT </th>
                    <th> DELETE </th>  
                </thead>

                <tbody>
                  @foreach ($users as $row)               
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ substr($row->CreatedAt,0,19) }}</td>
                        <td>

                          @if( $row->UserType =="prof" )
                            Professeur
                          @else
                            Etudiant
                          @endif
                        </td>
                        {{-- ($row->Activated)?"active":"desactive"      --}}
                        <td>                         
                         @if($row->Activated == true) 
                              <span class="label text-success"> Activé </span>    
                         @else 
                              <span class="label text-danger"> Désactivé </span>  
                         @endif
                        </td>                  
                        
                        <td>
                            <a href="/listeUser-edit/{{ $row->id }}" class="btn btn success">EDIT</a>
                        </td>
                        <td>
                          <form action="/listeUser-delete/{{ $row->id }}" method="POST">
                            {{  csrf_field()  }}
                            {{  method_field('DELETE')  }}
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
// hadi dial recherche
<?php if($errors->has('email')||$errors->has('password')||$errors->has('activation')||$errors->has('type')) 
echo '
$("#exampleModal").modal("show");
';
?>
$(".deleting").click(function(e){
  e.preventDefault();
  let i = confirm(' Vous supprimerez toutes les données relatives à cet utilisateur ? ');
  if(i){
    e.target.closest('form').submit();
  }
});

$(document).ready( function () {
    $('#datatable').DataTable();
});


</script>
    
@endsection