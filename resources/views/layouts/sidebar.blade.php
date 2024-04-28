
@php
$currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
@endphp
       <ul class="menu">
       <!--      <li class="sidebar-title"><i class="fa fa-user"></i> {{Auth::user()->name}}</li>
 -->
            <li class="sidebar-title">Menu</li>
            
            <li
                class="sidebar-item {{ Request::segment(1) == '' ? 'active' : null }} {{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                <a href="{{ url('/dashboard') }}" class="sidebar-link ">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li
                class="sidebar-item {{ Request::segment(1) === 'monitoring' ? 'active' : null }}">
                <a href="{{route('monitoring.index')}}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Monitoring</span>
                </a>
            </li>
            

            

            <li class="sidebar-item  has-sub {{ Str::startsWith($currentRoute, 'report.profilspbu') || Str::startsWith($currentRoute, 'report.voltage') || Str::startsWith($currentRoute, 'report.broadcast') ? 'active' : '' }}">
                <a href="#" class="sidebar-link ">
                    <i class="bi bi-pentagon-fill"></i>
                    <span>Report</span>
                </a>
                <ul class="submenu {{ Str::startsWith($currentRoute, 'report.profilspbu') || Str::startsWith($currentRoute, 'report.voltage') || Str::startsWith($currentRoute, 'report.broadcast') ? 'active' : '' }}" >
                    <li class="submenu-item {{ Str::startsWith($currentRoute, 'report.profilspbu') ? 'active' : '' }}">
                        <a href="{{route('report.profilspbu')}}" class="submenu-link">Report Profil SPBU</a>
                    </li>
                    <li class="submenu-item {{ Str::startsWith($currentRoute, 'report.voltage') ? 'active' : '' }}">
                        <a href="{{route('report.voltage')}}" class="submenu-link">Report Voltage</a>
                    </li>
                    <li class="submenu-item {{ Str::startsWith($currentRoute, 'report.broadcast') ? 'active' : '' }}">
                        <a href="{{route('report.broadcast')}}" class="submenu-link">Report Broadcast</a>
                    </li>
                </ul>
            </li>
            @if (auth()->user()->level != 'user')
            <li class="sidebar-item  has-sub {{ Request::segment(1) === 'device' || Request::segment(1) === 'contact' || Request::segment(1) === 'user' ? 'active' : null }}">
                <a href="#" class="sidebar-link ">
                    <i class="bi bi-pentagon-fill"></i>
                    <span>Master Data</span>
                </a>
                <ul class="submenu" >
                    <li class="submenu-item {{ Request::segment(1) === 'device' ? 'active' : null }}">
                        <a href="{{route('device.index')}}" class="submenu-link">Device</a>
                    </li>
                    <li class="submenu-item {{ Request::segment(1) === 'contact' ? 'active' : null }}">
                        <a href="{{route('contact.index')}}" class="submenu-link">Contact</a>
                    </li>
                    <li class="submenu-item {{ Request::segment(1) === 'user' ? 'active' : null }}">
                        <a href="{{route('user.index')}}" class="submenu-link">User</a>
                    </li>
                </ul>
            </li>
            @endif
         



        </ul>