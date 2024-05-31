<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <title>{{ $judul }}</title>
    <link rel="stylesheet" href="{!! asset("css/pdf.css") !!}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{!! asset("img/home-decor-3.jpg") !!}" alt="logo">
        </div>
        <h1>{{ $judulTabel }}</h1>
        <div id="company" class="clearfix">
            <div>Program Laundry</div>
            <div>Simokerto,<br /> Surabaya, Jawa Timur</div>
            {{-- <div>(62) 6282181080609</div>
            <div><a href="mailto:paperus@example.com">paperus@example.com</a></div> --}}
            <div>{{ \Carbon\Carbon::now()->format("d F Y") }}</div>
        </div>
        <div id="project"></div>
    </header>
    <main>
        @yield("tanggal")
        @yield("tabel")
        <div id="notices"></div>
    </main>
    <footer>
        {{ $footer }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>
