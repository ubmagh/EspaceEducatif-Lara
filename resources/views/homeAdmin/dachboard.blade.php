@extends('masterPage.master')



@section('title')
    Dashboard | Admin
@endsection




@section('content')
<div class="panel-header panel-header-lg py-5">
  <h2 class="h2 text-light mb-1 text-center mt-n3"> publications par mois </h2>
  
  <canvas id="bigDashboardChart"></canvas>
</div>




<div class="content">
  <div class="row">
    <div class="col-lg-4">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Nombre de</h5>
          <h4 class="card-title">Messages par mois</h4>
          
        </div>
        <div class="card-body py-2">

        
        {{-- !!!!!!!!!!!!!!!!!!!!!!   charts tani --}}

          <div class="chart-area mb-1">
            <canvas id="lineChartExample" class=""></canvas>
          </div>


        </div>
        <div class="card-footer">
          <div class="stats">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Nombre de</h5>
          <h4 class="card-title">Fichiers Ajoutés par mois</h4>
         
        </div>
        <div class="card-body">

           {{-- :::::::::::::::::::::::: charts talt attention     !!!!!!!!!!!!!!!!!!!!!!! --}}

          <div class="chart-area">
            <canvas id="lineChartExampleWithNumbersAndGrid"></canvas>
          </div>
        </div>
        <div class="card-footer">
          <div class="stats">
            
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Nombre de</h5>
          <h4 class="card-title">Commentaires ajoutés par mois</h4>
        </div>
        <div class="card-body">

  {{-- :::::::::::::::::::::::: charts rab3 attention     !!!!!!!!!!!!!!!!!!!!!!! --}}

          <div class="chart-area">
            <canvas id="barChartSimpleGradientsNumbers"></canvas>
          </div>
        </div>
        <div class="card-footer">
          <div class="stats">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card  card-tasks">
        <div class="card-header ">
          <h4 class="card-title"> Statistiques Générales </h4>
        </div>
        <div class="card-body ">
          

          <div class="chart-area mt-n2">
            <canvas id="SexeDonutsChart" class="w-100"></canvas>
          </div>

          <div class="chart-area ">
            <canvas id="UsersTypesDonut" class="w-100"></canvas>
          </div>

        </div>
        <div class="card-footer ">
          <hr>
          <div class="stats">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          
        <h4 class="card-title"> Utilisateurs non Activés </h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table mb-2 py-4">
              <thead class=" text-primary">
                <th>
                  Nom 
                </th>
                <th>
                  Type
                </th>
                <th>
                  Créé le 
                </th>
                <th >
                  Email
                </th>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td>
                      {{ $user->nom }}
                    </td>
                    <td>
                      @if( $user->UserType =="prof")
                        Professeur
                      @else 
                        Etudiant
                      @endif
                    </td>
                    <td>
                      {{ substr($user->CreatedAt,0,19) }}
                    </td>
                    <td>
                      {{ $user->email }}
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
  const url = "{{url('/')}}";
  var nbr_messages_labels   = [
                    "Janvier",
                    "Fevrier",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Aout",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Decembre",
                ];
    var nbr_fichiers_labels = [...nbr_messages_labels];
    var nbr_comments_labels = [...nbr_messages_labels];

  var nbr_messages_data  =  [
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                            100,
                        ];
    var nbr_fichiers_data = [...nbr_messages_data];
    var nbr_comments_data = [...nbr_messages_data];
    var Sexedata =[1,1];
    var Typesdata2=[1,1];

        $(document).ready(function(){     

          $.get(url+"/api/Stats/Messages", function(response){
            let stop = parseInt( response.current );
            for(let i=12;i>=stop;i--){
              nbr_messages_data.splice(i,1);
              nbr_messages_labels.splice(i,1);
            }for(let i=0;i<=stop-1;i++){
              nbr_messages_data[i]=parseInt( response.data[i+1] );
            }});
            

            $.get(url+"/api/Stats/Fichiers", function(response){
            let stop = parseInt( response.current );
            for(let i=12;i>=stop;i--){
              nbr_fichiers_data.splice(i,1);
              nbr_fichiers_labels.splice(i,1);
            }for(let i=0;i<=stop-1;i++){
              nbr_fichiers_data[i]=parseInt( response.data[i+1] );
            }});


            $.get(url+"/api/Stats/Comments", function(response){
            let stop = parseInt( response.current );
            for(let i=12;i>=stop;i--){
              nbr_comments_data.splice(i,1);
              nbr_comments_labels.splice(i,1);
            }for(let i=0;i<=stop-1;i++){
              nbr_comments_data[i]=parseInt( response.data[i+1] );
            }});

            $.get(url+"/api/Stats/Sexe", function(response){
            Sexedata = [response.data.M,response.data.F];
            });

            $.get(url+"/api/Stats/Types", function(response){
              Typesdata2 = [response.data.profs,response.data.etuds];
            });


          $.get(url+"/test", function(response){
            demo.initDashboardPageCharts(response.months, response.post_count_data, response.max);
              });
          
              
            });
            
</script>



    
{{-- <script src="{{asset('assets/demo/demo.js')}}"></script> --}}

@endsection