<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>

    <script>
        if (confirm('Apakah Anda yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        } else {
            window.history.back();
        }
    </script>
</body>
</html>