<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Absensi')</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @yield('styles')
</head>
<body>

  @include('layouts.sidebar-absen')
  <div id="main-content" class="main-content">
    @include('layouts.topbar-absen')

    <div class="dashboard-content">
      @hasSection('content')
        @yield('content')
      @else
        <div class="page-content active">
          <div class="content-title">Dashboard Absensi</div>
          <p class="content-description">Pilih menu absensi di sidebar untuk mulai menggunakan fitur.</p>
        </div>
      @endif
    </div>
  </div>

  <script src="{{ asset('js/script.js') }}"></script>
  @yield('scripts')
</body>
</html>
