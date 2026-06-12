<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Superadmin')</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
  @yield('styles')
</head>
<body>

  @include('layouts.sidebar-superadmin')
  <div id="main-content" class="main-content">
    @include('layouts.topbar-absen')

    <div class="dashboard-content">
      @yield('content')
    </div>
  </div>

  <script src="{{ asset('js/script.js') }}"></script>
  @yield('scripts')
</body>
</html>
