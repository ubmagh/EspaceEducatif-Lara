
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title> 

      @yield('title')   

  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}"  />
  <link href="../assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="{{ asset('assets/demo/demo.css')}}">

  {{-- hadi ztha ela wd recherche --}}
  <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css')}}">

</head>

<body class="">
  <div class="wrapper ">

    <div class="sidebar" data-color="blue"><!--Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"-->
      <div class="logo">
        <a href="{{ url('/dac hboard') }}" class="simple-text logo-mini d-block w-100 h2 text-center mt-3 mb-0">
          {{ config('app.name')}}
        </a>
        <a href="{{ url('/dac hboard') }}" class="simple-text logo-mini d-block w-100 mt-n3 mb-0 ">
          {{ "Espace Administratif" }}
        </a>
      </div>
      <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
          <li class="{{ 'dachboard' == request()->path() ? 'active' : '' }}">
            <a href="/dachboard">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          {{-- <li>
            <a href="./icons.html">
              <i class="now-ui-icons education_atom"></i>
              <p>Icons</p>
            </a>
          </li> --}}
          {{-- <li>
            <a href="./map.html">
              <i class="now-ui-icons location_map-big"></i>
              <p>Maps</p>
            </a>
          </li> --}}
          <li>
          <a href="{{ url('/Messages') }}">
              <i class="fas fa-envelope"></i>
              <p>Messages</p>
            </a>
          </li>
          <li class="{{ 'liste-professeur' == request()->path() ? 'active' : ''}}">
            <a href="/liste-professeur">
              <i class="fas fa-chalkboard-teacher"></i>
              <p>Gérer les Professeurs</p>
            </a>
          </li>
          <li class="{{ 'liste-etudiant' == request()->path() ? 'active' : ''}}">
            <a href="/liste-etudiant">
              <i class="fas fa-user-graduate"></i>
              <p>Gérer les Etudiants </p>
            </a>
          </li>
          <li class="{{ 'liste-utilisateur' == request()->path() ? 'active' : ''}}">
            <a href="/liste-utilisateur">
              <i class="fas fa-users"></i>
              <p>Gérer Les Utilisateurs</p>
            </a>
          </li>
          <li>
            <a href="{{ url('/Classes') }}">
              <i class="fas fa-th-list"></i>
              <p>Gérer Les Classes</p>
            </a>
          </li>
         
        </ul>
      </div>
    </div>


    <div class="main-panel" id="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="#pablo"></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            
            <ul class="navbar-nav">
             
              <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->UserType }} 
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>

              {{-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="now-ui-icons location_world"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Some Actions</span>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li> --}}
              <li class="nav-item">
              <a class="nav-link" href="{{ url('/Settings') }}">
                  <i class="now-ui-icons users_single-02"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Paramètres</span>
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->

     
      

        
        @yield('content')

        


      </div>



      <footer class="footer">
        <div class=" container-fluid ">
          <nav>
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="http://presentation.creative-tim.com">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright" id="copyright">
            &copy; <script>
              document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
            </script>, Espace Administratif. {{ config('app.name') }}
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="{{asset('/assets/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/bootstrap.min.js')}}"></script>

   {{-- hadi zdtha ela wed recherche --}}
   <script src="{{ asset('/assets/js/dataTables.min.js')}}"></script>

  <script src="{{asset('/assets/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{asset('/assets/js/plugins/chartjs.min.js')}}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{asset('/assets/js/plugins/bootstrap-notify.js')}}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('/assets/js/now-ui-dashboard.min.js?v=1.5.0')}}" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{asset('/assets/demo/demo.js')}}"></script>
  <script> 
  // Javascript method's body can be found in assets/js/demos.js
    // $(document).ready(function() {
     
    //   demo.initDashboardPageCharts();
    // });
  </script>

   @yield('scripts')

</body>

</html>