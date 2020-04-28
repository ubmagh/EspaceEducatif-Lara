@extends('masterPage.master')



@section('title')
    Message de {{$message->name}}
@endsection



@section('content')
      
<div class="panel-header panel-header-sm">
</div>
      
      <div class="content">
        

<div class="message">

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
        <h5 class="card-title"> <a href="{{ url('/Messages') }}"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Liste Des Messages </a></h5>
        </div>
        <div class="card-body">
          <div class="table-responsive py-3 ">
            <div class="d-block mx-auto w-75 border border-secondary py-4 px-2">
                <div class="row my-1">
                    <div class="col-3 text-right">
                        <h2 class="h4 font-weight-bold">  Nom :  </h2>
                    </div>
                    <div class="col-9 text-left">
                        <h4 class="h4">{{ $message->name  }}</h4>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-3 text-right">
                        <h2 class="h4 font-weight-bold"> adresse Email :  </h2>
                    </div>
                    <div class="col-9 text-left">
                        <h4 class="h4">{{ $message->email  }}</h4>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-3 text-right">
                        <h2 class="h4 font-weight-bold"> date et heure :  </h2>
                    </div>
                    <div class="col-9 text-left">
                        <h4 class="h4">{{ substr($message->date,0,16)  }}</h4>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-3 text-right">
                        <h2 class="h4 font-weight-bold"> Source/type :  </h2>
                    </div>
                    @if($message->type=='interne')
                    <div class="col-9 text-left">
                        <h4 class="h4 text-info"> Interne par un utilisateur </h4>
                    </div>
                    @else
                    <div class="col-9 text-left">
                        <h4 class="h4 text-warning"> Interne par un visiteur </h4>
                    </div>
                    @endif
                </div>
                <h3 class="h3 text-left ml-5 mt-3">
                    Le message :
                </h3>
                <div class="row col-10 mx-auto py-2 px-1">
                <p class="h5">
                    {{$message->message}}
                </p>
             </div>
            </div>
            <div class="row mt-3 mb-2 w-100 text-center">
             <a name="" id="" class="btn btn-danger px-4 py-3 d-block mx-auto col-2" href="{{ url('/Messages/del/'.$message->id) }}" role="button"> <i class="fa fa-trash fa-lg" aria-hidden="true"></i> </a>
            </div>
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