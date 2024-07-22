<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> @yield('title') | {{ config('app.name', 'RockerTech') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ assets('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ assets('css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/ccf5b15bef.js" crossorigin="anonymous"></script>

    <!-- CSS Files -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <link id="pagestyle" href="{{ assets('css/soft-ui-dashboard.css') }}" rel="stylesheet"/>
    <link href="{{ assets('css/skeleton.css') }}" rel="stylesheet"/>
    @stack('styles')
    <style>
        .vh-50{
            height: 50vh;
        }
        .vh-30{
            height: 30vh;
        }
    </style>
</head>
<body class="@if(Auth::check()) g-sidenav-show  bg-gray-100 @endif">
    @if (Auth::check())
        @include('admin.partials.sidebar')
    @endif
    <main class="main-content mt-0">
        <section class="@if(Auth::check()) pe-3 pt-2 @endif">
            @yield('content')
        </section>
    </main>
    {{-- Add Scripts --}}
    <script src="{{ assets('js/core/popper.min.js') }}"></script>
    <script src="{{ assets('js/core/bootstrap.min.js') }}"></script>
    
    <script src="{{ assets('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ assets('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ assets('js/plugins/chartjs.min.js') }}"></script>
    
    <script src="{{ assets('js/soft-ui-dashboard.min.js') }}"></script>
    @stack('scripts')
</body>
</html>