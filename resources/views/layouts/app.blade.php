<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{isset($title) ? $title : 'Smart Lock Voltage Monitoring'}}</title>
    
    <link rel="shortcut icon" href="{{asset('assets/files/logo.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/files/logo.png')}}" type="image/png">
  <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}">  --}}
  <style type="text/css">
      #main #main-content {
        padding-top: 0px !important;
      }
      .sidebar-wrapper .menu .submenu .submenu-item a {
        padding-left: 16px;
      }
  </style>
  @section('css')
  @show
</head>

<body class="light">
     {{-- <script src="{{asset('assets/static/js/initTheme.js')}}"></script>  --}}
    <div id="app">
        <div id="sidebar" class="sidebar-desktop inactive">
            <div class="sidebar-wrapper">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                {{-- <img src="{{asset('assets/files/logo.png')}}" alt="Logo">--}} <h4 >SLV</h4> 
            </div>
           
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        @section('sidebar')
            @include('layouts.sidebar',['user' => Auth::User()])
        @show
    </div>
</div>
        </div>
        <div id="main" class='layout-navbar navbar-fixed'>
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{Auth::user()->name}}</h6>
                                          <!--   <p class="mb-0 text-sm text-gray-600">Hak akses</p> -->
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{asset('assets/files/user.png')}}">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">Hello, {{Auth::user()->name}}!</h6>
                                    </li>
                                    {{-- <li>
                                        <a href="" class='dropdown-item'>
                                                            <i class="fa-solid fa-user"></i>
                                                            <span>Edit Profile</span>
                                                        </a>
                                            </li> <li> --}}
                                        <a href="{{ route('logout') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class='dropdown-item text-danger'>
                                                            <i class="fa-solid fa-power-off text-danger"></i>
                                                            <span>Logout</span>
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                    {{ csrf_field() }}
                                                                </form>
                                                        </a>
                                            </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">
                @yield('content')

     </div>
          
  <footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>{{date('Y')}}</p>
        </div>
<!--         <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                by <a href="https://saugi.me">Saugi</a></p>
        </div> -->
    </div>
</footer>
        </div>
    </div>
<!--     <script src="../assets/static/js/components/dark.js"></script> -->
    <script src="{{asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    
    <script src="{{asset('assets/compiled/js/app.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
      $(document).ready(function() {
        $('.dataTable').DataTable();
      });
</script>
<script>
    // If you want to use tooltips in your project, we suggest initializing them globally
    // instead of a "per-page" level.
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>
@section('js')

@show

    
</body>

</html>