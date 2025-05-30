<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('employe.dashboard') }}">
        <img src="{{ assets('img/logo-1.png') }}" class="navbar-brand-img h-100" alt="main_logo">
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-autov" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('employe.dashboard') ? 'active' : '' }}" href="{{ route('employe.dashboard') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-gauge fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('employe.myprecense') ? 'active' : '' }}" href="{{ route('employe.myprecense') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-user-check fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">My Presence</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('employe.todolist') ? 'active' : '' }}" href="{{ route('employe.todolist') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-list-check fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">To-do List</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('employe.permit') ? 'active' : '' }}" href="{{ route('employe.permit') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-envelope fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Permit Submission</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('employe.permit.list') ? 'active' : '' }}" href="{{ route('employe.permit.list') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-list fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">List Permit</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>