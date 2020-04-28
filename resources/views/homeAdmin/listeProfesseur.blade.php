@extends('masterPage.master')



@section('title')
    Liste des professeurs
@endsection



@section('content')
<div class="panel-header panel-header-sm">
</div>
      
      <div class="content">
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title"> Liste Des Professeurs</h5>

          @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
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
                          <form action="/listeEtudiant-delete/{{$row->id }}" method="POST">
                            {{  csrf_field()  }}
                            {{  method_field('DELETE') }}
                          <button class="btn btn-danger">DELETE</button>
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
$(document).ready( function () {
    $('#datatable').DataTable();
});

</script>
    
@endsection