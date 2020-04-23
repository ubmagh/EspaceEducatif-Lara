@extends('masterPage.master')



@section('title')
    Users | Admin
@endsection



@section('content')
    

{{-- formulaire prof --}}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Professeur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
         </div>
        @endif
      </div>
      <div class="modal-body">
        <form action="{{route('user-insert')}}" method="POST">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nom</label>
            <input type="text" class="form-control" id="recipient-name" name="nom">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Prenom</label>
            <input type="text" class="form-control" id="recipient-name" name="prenom">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Email</label>
            <input type="email" class="form-control" id="recipient-name" name="email">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Password</label>
            <input type="password" class="form-control" id="recipient-name" name="password" placeholder="">
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
           </div>
           <div class="form-group">
            <label for="recipient-name" class="col-form-label">Matiere</label>
            <input type="text" class="form-control" id="recipient-name" name="matiere">
          </div>
          {{-- <div class="custom-file">
            <input type="file" class="custom-file-input" id="inputGroupFile04" name="image" aria-describedby="inputGroupFileAddon04">
            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
          </div> --}}
          
          {{-- <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div> --}}
            <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Enregistrer</button>
      </div>
        </form>
      </div>
    
    </div>
  </div>
</div>


{{-- formulaire etudiant --}}

<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Etudiant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
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
          <h4 class="card-title"> Liste | Utilisateurs</h4>

          {{-- @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
           </div>
          @endif --}}
          

          
         <div class="dropdown">

          
  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     Ajouter Un Utilisateurs
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#exampleModal" >Professeur</button>
    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#exampleModal1" >Etudiant</button>
    
  </div>
</div>
         




        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table id="datatable" class="table">       
                <thead class=" text-primary">
                    <th> Id </th>
                    <th> E-mail </th>
                    <th> Dernier Connexion </th>
                    <th> Type </th>
                    <th> Activation </th>
                    <th> EDIT </th>
                    <th> DELETE </th>  
                </thead>

                <tbody>
                  @foreach ($user as $row)               
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->LastLogin }}</td>
                        <td>{{ $row->UserType }}</td>
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
                          {{-- <form action="/listeEtudiant-delete/{{ $row->id }}" method="POST">
                            {{  csrf_field()  }}
                            {{  method_field('DELETE')  }}
                          
                          </form> --}}
                          <button class="btn btn-danger">DELETE</button>
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
$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection