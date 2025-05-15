<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
        <img src="{{ assets('img/logo-1.png') }}" class="navbar-brand-img h-100" alt="main_logo">
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-autov" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-home fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.kpi') ? 'active' : '' }}" href="{{ route('admin.kpi') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-gauge fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">KPI</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.employee') ? 'active' : '' }}" href="{{ route('admin.employee') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-user fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Employee</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.presence') ? 'active' : '' }}" href="{{ route('admin.presence') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-list-check fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Presence</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.todolist') ? 'active' : '' }}" href="{{ route('admin.todolist') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-list fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">To-do List</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.permit') ? 'active' : '' }}" href="{{ route('admin.permit') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-envelope fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Permit Submission List</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.file_report') ? 'active' : '' }}" href="{{ route('admin.file_report') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-folder-open fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">File Report</span>
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white me-2 text-center d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-gear fa-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>