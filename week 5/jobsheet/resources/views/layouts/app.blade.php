<html>
<head>
    <title>Halaman @yield('title')</title>
</head>
<body>
    @section('sidebar')
        Ini adalah master sidebar.
    @show

    <div class="container">
        @yield('content')
    </div>
</body>
</html>