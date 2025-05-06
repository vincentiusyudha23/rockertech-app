@props(['title_page'])
<div class="d-flex flex-column min-dvh-100 overflow-hidden">
    <nav class="navbar navbar-main navbar-expand-lg px-0 m-2 shadow-sm border-radius-xl bg-white border" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $title_page }}</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">{{ $title_page }}</h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item d-flex align-items-center"></li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">{{ Auth::user()->name ?? '' }}</span>
                        </a>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item dropdown px-3 d-flex align-items-center">
                        <a href="javascript:void(0)" class="nav-link text-body p-0" id="navbar-dropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                            aria-labelledby="navbar-dropdown">
                            <li class="mb-2">
                                <a href="{{ route(route_prefix().'profile') }}" class="dropdown-item border-radius-md p-1 d-flex align-items-center">
                                    <i class="fa-solid fa-user px-2"></i>
                                    Profile
                                </a>
                            </li>
                            <li class="mb-2">
                                <form class="dropdown-item border-radius-md p-1 d-flex flex-row align-items-center"
                                    action="{{ Auth::user()->hasRole('admin') ? route('admin.logout') : route('employe.logout') }}"
                                    method="POST">
                                    @csrf
                                    <a onclick="event.preventDefault(); this.closest('form').submit();"
                                        href="javascript:void(0)">
                                        <i class="fa-solid fa-right-from-bracket px-2"
                                            style="transform: rotate(180deg);"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="flex-fill">
        {{ $slot }}
    </div>
</div>
