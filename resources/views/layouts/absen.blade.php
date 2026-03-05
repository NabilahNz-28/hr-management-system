<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Absensi')</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  @include('layouts.sidebar-absen')
  <div id="main-content" class="main-content">
    @include('layouts.topbar-absen')

    <div class="dashboard-content">
      @yield('content')
    </div>
  </div>

  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
