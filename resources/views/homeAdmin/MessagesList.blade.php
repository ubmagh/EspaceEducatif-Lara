@extends('masterPage.master')



@section('title')
    Liste des Messages 
@endsection



@section('content')
      
<div class="panel-header panel-header-sm">
</div>
      
      <div class="content">
        

<div class="message">

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title"> Liste Des Messages</h5>

          @if (session('status')=='success')
          <div class="alert alert-success" role="alert">
              Message Bien supprimé !
           </div>
          @endif

        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table id="datatable" class="table">       
                <thead class=" text-primary">
                    <th> Id </th>
                    <th> Nom </th>
                    <th> Email </th>
                    <th> Type </th>    
                    <th> date </th>
                    <th>  </th>
                </thead>

                <tbody>
                  @foreach ($messages as $message)    
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->type }}</td>                       
                        <td>{{ substr($message->date,0,16) }}</td>
                        <td>  <a name="" id="" class="btn btn-warning px-4 py-2" href="{{ url('/Messages/'.$message->id) }}" role="button"> <i class="fa fa-eye fa-2x" aria-hidden="true"></i> </a> </td>
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