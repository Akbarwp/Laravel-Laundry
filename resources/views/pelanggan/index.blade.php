<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" data-theme="light" class="scroll-smooth" :class="{ 'theme-dark': dark }" x-data="data()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo-laundry-simokerto.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo-laundry-simokerto.png') }}" />

    @vite(["resources/css/app.css", "resources/js/app.js"])
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}" />

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="text-blueGray-700 antialiased">
    <nav class="navbar-expand-lg absolute top-0 z-50 flex w-full flex-wrap items-center justify-between px-2 py-3">
        <div class="container mx-auto flex flex-wrap items-center justify-between px-4">
            <div class="relative flex w-full justify-between lg:static lg:block lg:w-auto lg:justify-start">
                <a class="mr-4 inline-block whitespace-nowrap py-2 text-sm font-bold uppercase leading-relaxed text-white" href="#home">
                    Laundry Simokerto
                </a>
                <button class="block cursor-pointer rounded border border-solid border-transparent bg-transparent px-3 py-1 text-xl leading-none outline-none focus:outline-none lg:hidden" type="button" onclick="toggleNavbar('example-collapse-navbar')">
                    <i class="ri-side-bar-fill text-white"></i>
                </button>
            </div>
            <div class="hidden flex-grow items-center bg-white lg:flex lg:bg-opacity-0 lg:shadow-none" id="example-collapse-navbar">
                <ul class="flex list-none flex-col items-center lg:ml-auto lg:flex-row">
                    <li class="relative inline-block">
                        <a class="lg:hover:text-blueGray-200 text-blueGray-700 flex items-center px-3 py-4 text-xs font-bold uppercase lg:py-2 lg:text-white" href="#cekTransaksi">
                            Cek Transaksi
                        </a>
                    </li>
                    {{-- <li class="flex items-center">
                        <a class="lg:hover:text-blueGray-200 text-blueGray-700 flex items-center px-3 py-4 text-xs font-bold uppercase lg:py-2 lg:text-white" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdemos.creative-tim.com%2Fnotus-js%2F" target="_blank">
                            <i class="ri-github-fill lg:text-blueGray-200 text-blueGray-400 leading-lg text-lg"></i>
                            <span class="ml-2 inline-block lg:hidden">Share</span>
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </nav>

    <main>
        {{-- Awal Hero --}}
        <section id="home" class="min-h-screen-75 relative flex content-center items-center justify-center pb-32 pt-16">
            <div class="absolute top-0 h-full w-full bg-cover bg-center" style="
            background-image: url('{{ asset('img/home-decor-2.jpg') }}');">
                <span id="blackOverlay" class="absolute h-full w-full bg-black opacity-75"></span>
            </div>
            <div class="container relative mx-auto">
                <div class="flex flex-wrap items-center">
                    <div class="ml-auto mr-auto w-full px-4 text-center lg:w-6/12">
                        <div class="pr-12">
                            <h1 class="text-5xl font-semibold text-white">
                                Cuci Bersih, Hidup Lebih Rapi
                            </h1>
                            <p class="text-blueGray-200 mt-4 text-lg">
                                Pakaian yang bersih mencerminkan semangat yang positif. Mulailah harimu dengan keharuman dan kerapihan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-70-px pointer-events-none absolute bottom-0 left-0 right-0 top-auto w-full overflow-hidden" style="transform: translateZ(0px)">
                <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                    <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </section>
        {{-- Akhir Hero --}}

        {{-- Awal Info --}}
        <section class="bg-blueGray-200 -mt-24 pb-20">
            <div class="container mx-auto px-4">
                {{-- Kelebihan --}}
                <div class="flex flex-wrap">
                    <div class="w-full px-4 pt-6 text-center md:w-4/12 lg:pt-12">
                        <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                            <div class="flex-auto px-4 py-5">
                                <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-400 p-3 text-center text-white shadow-lg">
                                    <i class="ri-award-fill text-2xl"></i>
                                </div>
                                <h6 class="text-xl font-semibold">Kualitas Terjamin</h6>
                                <p class="text-blueGray-500 mb-4 mt-2">
                                    Kami menggunakan peralatan modern dan deterjen ramah lingkungan untuk memastikan pakaian Anda bersih sempurna.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 text-center md:w-4/12">
                        <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                            <div class="flex-auto px-4 py-5">
                                <div class="bg-lightBlue-400 mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full p-3 text-center text-white shadow-lg">
                                    <i class="ri-timer-flash-line text-2xl"></i>
                                </div>
                                <h6 class="text-xl font-semibold">Layanan Cepat dan Tepat Waktu</h6>
                                <p class="text-blueGray-500 mb-4 mt-2">
                                    Kami memahami betapa berharganya waktu Anda, oleh karena itu kami selalu mengutamakan ketepatan waktu dalam setiap layanan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 pt-6 text-center md:w-4/12">
                        <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                            <div class="flex-auto px-4 py-5">
                                <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-400 p-3 text-center text-white shadow-lg">
                                    <i class="ri-hand-heart-fill text-2xl"></i>
                                </div>
                                <h6 class="text-xl font-semibold">Kontribusi Nyata</h6>
                                <p class="text-blueGray-500 mb-4 mt-2">
                                    Setiap pakaian yang Anda cuci bersama kami, adalah langkah kecil untuk perubahan besar dalam kehidupan keluarga kurang mampu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Kelebihan --}}

                {{-- Sekilas Tentang Kami --}}
                <div class="mt-32 flex flex-wrap items-center">
                    <div class="ml-auto mr-auto w-full px-4 md:w-5/12">
                        <div class="text-blueGray-500 mb-6 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white p-3 text-center shadow-lg">
                            <i class="ri-information-2-line text-2xl"></i>
                        </div>
                        <h3 class="mb-2 text-3xl font-semibold leading-normal">
                            Sekilas tentang Kami
                        </h3>
                        <p class="text-blueGray-600 mb-4 mt-4 text-lg font-light leading-relaxed">
                            Bisnis layanan pencucian, atau dikenal sebagai laundry, menyajikan pelayanan membersihkan pakaian secara menyeluruh, mencakup tahap mencuci hingga menyetrika, dan akhirnya mengembalikan pakaian kepada pelanggan dalam keadaan bersih.
                        </p>
                        <p class="text-blueGray-600 mb-4 mt-4 text-lg font-light leading-relaxed">
                            Usaha laundry merupakan salah satu bisnis yang terus berkembang di Surabaya, sebuah kota metropolitan dengan populasi yang besar dan tingkat mobilitas yang tinggi.
                        </p>
                        {{-- <a href="#" class="text-blueGray-700 mt-8 font-bold">Check Notus Tailwind JS!</a> --}}
                    </div>
                    <div class="ml-auto mr-auto w-full px-4 md:w-4/12">
                        <div class="relative mb-6 flex w-full min-w-0 flex-col break-words rounded-lg bg-pink-500 shadow-lg">
                            <img alt="gambar1" src="{{ asset('img/carousel-1.jpg') }}" class="w-full rounded-t-lg align-middle" />
                            <blockquote class="relative mb-4 p-8">
                                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="h-95-px -top-94-px absolute left-0 block w-full">
                                    <polygon points="-30,95 583,95 583,65" class="fill-current text-pink-500"></polygon>
                                </svg>
                                <h4 class="text-xl font-bold text-white">
                                    Namun,
                                </h4>
                                <p class="text-md mt-2 font-light text-white">
                                    Di Laundry kami percaya bahwa setiap pakaian yang bersih membawa kebahagiaan, tapi misi kami tidak berhenti di situ. Kami memiliki satu tujuan mulia yaitu membantu mensejahterakan keluarga kurang.
                                </p>
                            </blockquote>
                        </div>
                    </div>
                </div>
                {{-- Sekilas Tentang Kami --}}
            </div>
        </section>
        {{-- Akhir Info --}}

        {{-- Awal Ajakan --}}
        <section class="pb-48 pt-20">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-center text-center">
                    <div class="w-full px-4 lg:w-6/12">
                        <h2 class="text-4xl font-semibold">Bergabunglah dengan Kami</h2>
                        <p class="text-blueGray-500 m-4 text-lg leading-relaxed">
                            Mari bergandengan tangan untuk masa depan yang lebih baik. Gunakan jasa Laundry kami dan jadilah bagian dari perubahan. Bersama, kita bisa membuat perbedaan.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        {{-- Akhir Ajakan --}}

        <section id="cekTransaksi" class="bg-blueGray-800 relative block pb-20">
            <div class="pointer-events-none absolute bottom-auto left-0 right-0 top-0 -mt-20 h-20 w-full overflow-hidden" style="transform: translateZ(0px)">
                <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                    <polygon class="text-blueGray-800 fill-current" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
            <div class="container mx-auto px-4 pb-5 lg:pb-64 lg:pt-24">
                <div class="flex flex-wrap justify-center text-center">
                    <div class="w-full px-4 lg:w-6/12">
                        <h2 class="text-4xl font-semibold text-white">Cek Status Laundry Anda</h2>
                        <p class="text-blueGray-400 mb-4 mt-4 text-lg leading-relaxed">
                            Kini Anda dapat dengan mudah mengecek status laundry Anda! Masukkan nota Anda di bawah ini untuk mengetahui sudah sampai mana proses pengerjaan laundry Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-blueGray-800 relative block py-24 lg:pt-0">
            <div class="container mx-auto px-4">
                <div class="-mt-48 flex flex-wrap justify-center lg:-mt-64">
                    <div class="w-full px-4 lg:w-6/12">
                        <div class="bg-blueGray-200 relative mb-6 flex w-full min-w-0 flex-col break-words rounded-lg shadow-lg">
                            <div class="flex-auto p-5 lg:p-10">
                                <h4 class="text-2xl font-semibold">Masukkan Nota Anda</h4>
                                {{-- <p class="text-blueGray-500 mb-4 mt-1 leading-relaxed">
                                    Complete this form and we will get back to you in 24 hours.
                                </p> --}}
                                <div class="relative mb-3 mt-8 w-full">
                                    <input type="text" name="nota" class="placeholder-blueGray-300 text-blueGray-600 w-full rounded border-0 bg-white px-3 py-3 text-sm shadow transition-all duration-150 ease-linear focus:outline-none focus:ring" placeholder="Nota Transaksi" required />
                                </div>
                                <div class="mt-6 text-center">
                                    <button type="button" name="cekTansaksi" class="bg-blueGray-800 active:bg-blueGray-600 mb-1 mr-1 rounded px-6 py-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear hover:shadow-lg focus:outline-none">
                                        Cek Status
                                    </button>
                                </div>

                                <div id="hasilCek" class="mt-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-blueGray-200 relative pb-6 pt-8">
        <div class="pointer-events-none absolute bottom-auto left-0 right-0 top-0 -mt-20 h-20 w-full overflow-hidden" style="transform: translateZ(0px)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap text-center lg:text-left">
                <div class="w-full px-4 lg:w-6/12">
                    <h4 class="text-3xl font-semibold">
                        Cuci Bersih, Hidup Lebih Rapi
                    </h4>
                    <h5 class="text-blueGray-600 mb-2 mt-0 text-lg">
                        Pakaian yang bersih mencerminkan semangat yang positif. Mulailah harimu dengan keharuman dan kerapihan.
                    </h5>
                </div>
                <div class="w-full px-4 lg:w-6/12">
                    <div class="items-top mb-6 flex flex-wrap">
                        <div class="ml-auto w-full px-4 lg:w-4/12">
                            <span class="text-blueGray-500 mb-2 block text-sm font-semibold uppercase">Laundry Simokerto</span>
                            <ul class="list-unstyled">
                                <li>
                                    <a class="text-blueGray-600 hover:text-blueGray-800 block pb-2 text-sm font-semibold" href="#home">
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a class="text-blueGray-600 hover:text-blueGray-800 block pb-2 text-sm font-semibold" href="#cekTransaksi">
                                        Cek Transaksi
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border-blueGray-300 my-6" />
            {{-- <div class="flex flex-wrap items-center justify-center md:justify-between">
                <div class="mx-auto w-full px-4 text-center md:w-4/12">
                    <div class="text-blueGray-500 py-1 text-sm font-semibold">
                        Copyright © <span id="get-current-year"></span> Laundry Simokerto
                        by
                        <a href="#" class="text-blueGray-500 hover:text-blueGray-800">Laundry Simokerto</a>.
                    </div>
                </div>
            </div> --}}
        </div>
    </footer>

    <script src="{{ asset("js/init-alpine.js") }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        /* Make dynamic date appear */
        (function() {
            if (document.getElementById("get-current-year")) {
                document.getElementById("get-current-year").innerHTML =
                    new Date().getFullYear();
            }
        })();
        /* Function for opning navbar on mobile */
        function toggleNavbar(collapseID) {
            document.getElementById(collapseID).classList.toggle("hidden");
            document.getElementById(collapseID).classList.toggle("block");
        }
    </script>

    <script>
        $(document).ready(function () {
            $("button[name='cekTansaksi']").click(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "get",
                    url: "{{ route('landing-page.nota') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "nota": $("input[name='nota']").val()
                    },
                    success: function(data) {
                        // console.log(data);
                        let total_bayar_akhir = new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(data[0].total_bayar_akhir);

                        let bayar = new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(data[0].bayar);

                        let kembalian = new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(data[0].kembalian);

                        let date = new Date(data[0].tanggal);
                        let options = { year: 'numeric', month: '2-digit', day: '2-digit' };
                        let tanggal = new Intl.DateTimeFormat('id-ID', options).format(date);

                        let nomor = 1;
                        $("#hasilCek").html(`
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Nota:
                                <span class="text-blue-600 font-semibold"> `+ data[0].nota_pelanggan +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Tanggal:
                                <span class="text-blue-600 font-semibold"> `+ tanggal +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Cabang:
                                <span class="text-blue-600 font-semibold"> `+ data[0].cabang_nama +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Jenis Pembayaran:
                                <span class="text-blue-600 font-semibold"> `+ data[0].jenis_pembayaran +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Total Biaya:
                                <span class="text-blue-600 font-semibold"> Rp`+ data[0].total_bayar_akhir +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Bayar:
                                <span class="text-blue-600 font-semibold"> Rp`+ data[0].bayar +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Kembalian:
                                <span class="text-blue-600 font-semibold"> Rp`+ data[0].kembalian +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed">
                                Status:
                                <span class="text-blue-600 font-semibold"> `+ data[0].status +`</span>
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed font-bold">
                                Layanan Tambahan:
                                `+
                                    data[2].map(value => {
                                        return `<span class="text-blue-600 font-semibold"> `+value.layanan+`</span>`
                                    })
                                +`
                            </p>
                            <p class="text-slate-800 mb-4 mt-1 leading-relaxed font-bold">Detail Layanan:</p>
                            `+
                                data[1].map(item => {
                                    return `
                                        <div>
                                            <p class="mb-4 mt-1 leading-relaxed font-semibold">${nomor++}.</p>
                                            <p class="mb-4 mt-1 leading-relaxed text-blue-600 font-semibold">
                                                Pakaian:
                                                <span class="text-blue-800 font-semibold">${item.pakaian}</span>
                                            </p>
                                            <p class="mb-4 mt-1 leading-relaxed text-blue-600 font-semibold">
                                                Total Berat:
                                                <span class="text-blue-800 font-semibold">${item.total} kg</span>
                                            </p>
                                            <p class="mb-4 mt-1 leading-relaxed text-blue-600 font-semibold">
                                                Layanan yang diambil:
                                                `+
                                                    item.layanan.map(value => {
                                                        return `<span class="text-blue-800 font-semibold"> `+value+`</span>`
                                                    })
                                                +`
                                            </p>
                                        </div>
                                    `
                                }).join('')
                            +`
                        `);
                    },
                    error: function(data) {
                        $("#hasilCek").html(`<p class="text-blueGray-500 mb-4 mt-1 leading-relaxed text-center">Transaksi Tidak Ditemukan</p>`);
                    }
                });
            });
        });
    </script>
</body>

</html>
