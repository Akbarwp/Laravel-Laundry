<aside class="dark:bg-slate-850 max-w-64 ease-nav-brand z-990 fixed inset-y-0 my-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-xl transition-transform duration-200 dark:shadow-none xl:left-0 xl:ml-6 xl:translate-x-0" aria-expanded="false">
    <div class="h-19">
        <i class="ri-close-large-fill absolute right-0 top-0 cursor-pointer p-4 text-slate-400 opacity-50 dark:text-white xl:hidden" sidenav-close></i>
        <a class="m-0 block whitespace-nowrap px-8 py-6 text-sm text-slate-700 dark:text-white" href="https://demos.creative-tim.com/argon-dashboard-tailwind/pages/dashboard.html" target="_blank">
            <img src="{{ asset("img/logo-ct-dark.png") }}" class="ease-nav-brand inline h-full max-h-8 max-w-full transition-all duration-200 dark:hidden" alt="main_logo" />
            <img src="{{ asset("img/logo-ct.png") }}" class="ease-nav-brand hidden h-full max-h-8 max-w-full transition-all duration-200 dark:inline" alt="main_logo" />
            <span class="ease-nav-brand ml-1 font-semibold transition-all duration-200">Laundry Lurah</span>
        </a>
    </div>

    <hr class="mt-0 h-px bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="h-sidenav block max-h-screen w-auto grow basis-full items-center overflow-auto">
        <ul class="mb-0 flex flex-col pl-0">
            <li class="mt-0.5 w-full">
                <a class="py-2.7 ease-nav-brand {{ Request::routeIs("dashboard") ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("dashboard") }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="ri-tv-2-line relative top-0 text-lg leading-normal text-blue-500"></i>
                    </div>
                    <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Dashboard</span>
                </a>
            </li>

            @role(["lurah", "manajer_laundry"])
                @role("lurah")
                    {{-- Awal Data Master --}}
                    <li class="mt-4 w-full">
                        <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Data Master</h6>
                    </li>

                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["cabang"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("cabang") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-home-smile-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Cabang</span>
                        </a>
                    </li>

                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["umr"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("umr") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-currency-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">UMR</span>
                        </a>
                    </li>
                    {{-- Akhir Data Master --}}
                @endrole

                {{-- Awal User Management --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">User Management</h6>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["user", "user.cabang", "user.cabang.create", "user.create", "user.view", "user.edit", "user.edit.password", "user.trash"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("user") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-user-3-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Akun</span>
                    </a>
                </li>

                @role("lurah")
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["rw", "rw.cabang", "rw.cabang.create", "rw.create", "rw.view", "rw.edit", "rw.edit.password", "rw.trash"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("rw") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-user-star-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Lurah & RW</span>
                        </a>
                    </li>
                @endrole

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["gamis", "gamis.anggota"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("gamis") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-parent-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Gamis</span>
                    </a>
                </li>
                {{-- Akhir User Management --}}

                {{-- Awal Layanan --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Layanan</h6>
                </li>

                @role("lurah")
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["layanan-cabang", "layanan-cabang.cabang", "layanan-cabang.trash"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("layanan-cabang") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-service-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Layanan Cabang</span>
                        </a>
                    </li>
                @endrole

                @role("manajer_laundry")
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["jenis-layanan"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("jenis-layanan") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-hand-heart-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Jenis Layanan</span>
                        </a>
                    </li>

                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["jenis-pakaian"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("jenis-pakaian") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-shirt-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Jenis Pakaian</span>
                        </a>
                    </li>

                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["harga-jenis-layanan"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("harga-jenis-layanan") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-price-tag-3-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Harga Jenis Layanan</span>
                        </a>
                    </li>

                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["layanan-prioritas"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("layanan-prioritas") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-customer-service-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Layanan Prioritas</span>
                        </a>
                    </li>
                @endrole
                {{-- Akhir Layanan --}}
            @endrole

            @role(["lurah", "manajer_laundry", "pegawai_laundry"])
                {{-- Awal Transaksi --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Transaksi</h6>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["pelanggan", "pelanggan.cabang"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("pelanggan") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-user-5-line relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Pelanggan</span>
                    </a>
                </li>

                @role('lurah')
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["transaksi.lurah", "transaksi.lurah.cabang", "transaksi.lurah.cabang.jadwal", "transaksi.lurah.view", "transaksi.lurah.view.layanan", "transaksi.lurah.cabang.create", "transaksi.lurah.cabang.edit"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("transaksi.lurah") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-shopping-bag-4-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Transaksi Cabang</span>
                        </a>
                    </li>
                @endrole

                @role(["manajer_laundry", "pegawai_laundry"])
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["transaksi", "transaksi", "transaksi.view", "transaksi.view.layanan", "transaksi.create", "transaksi.edit"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("transaksi") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-todo-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Transaksi Layanan</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["transaksi.jadwal"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("transaksi.jadwal") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-timeline-view relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Jadwal Layanan</span>
                    </a>
                </li>
                @endrole
                {{-- Akhir Transaksi --}}
            @endrole

            @role(["gamis"])
                {{-- Awal Monitoring Transaksi Gamis --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Transaksi Gamis</h6>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["transaksi-gamis"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("transaksi-gamis") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-todo-line relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Transaksi Harian</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["transaksi-gamis.semua"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("transaksi-gamis.semua") }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-todo-line relative top-0 text-lg leading-normal text-blue-500"></i>
                        </div>
                        <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Transaksi Semua</span>
                    </a>
                </li>
                {{-- Akhir Monitoring Transaksi Gamis --}}
            @endrole

            @role(["lurah", "manajer_laundry", "rw"])
                {{-- Awal Monitoring Gamis --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Monitoring Gamis</h6>
                </li>

                @role(["lurah", "manajer_laundry"])
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["monitoring"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("monitoring") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-bar-chart-box-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Monitoring Gamis</span>
                        </a>
                    </li>
                @endrole

                @role(["rw"])
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["monitoring.rw"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("monitoring.rw") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-bar-chart-box-line relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Monitoring Gamis</span>
                        </a>
                    </li>
                @endrole
                {{-- Akhir Monitoring Gamis --}}
            @endrole

            @role(["lurah", "manajer_laundry"])
                {{-- Awal Laporan --}}
                <li class="mt-4 w-full">
                    <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Laporan</h6>
                </li>

                @role(["lurah", "manajer_laundry"])
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["laporan.pendapatan.laundry"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("laporan.pendapatan.laundry") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-book-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Pendapatan Laundry</span>
                        </a>
                    </li>
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["laporan.pendapatan.gamis"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("laporan.pendapatan.gamis") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-book-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Pendapatan Gamis</span>
                        </a>
                    </li>
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["laporan.pelanggan"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("laporan.pelanggan") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-book-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Pelanggan</span>
                        </a>
                    </li>
                    <li class="mt-0.5 w-full">
                        <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["laporan.gamis"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("laporan.gamis") }}">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="ri-book-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                            </div>
                            <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Gamis</span>
                        </a>
                    </li>
                @endrole
                {{-- Akhir Laporan --}}
            @endrole

            <li class="mt-4 w-full">
                <h6 class="ml-2 pl-6 text-xs font-bold uppercase leading-tight opacity-60 dark:text-white">Pengaturan</h6>
            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.7 ease-nav-brand {{ Request::routeIs(["profile", "profile.edit", "profile.edit.password"]) ? "rounded-lg font text-slate-700 bg-blue-500/10" : "" }} mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white" href="{{ route("profile", auth()->user()->slug) }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="ri-profile-fill relative top-0 text-lg leading-normal text-blue-500"></i>
                    </div>
                    <span class="ease pointer-events-none ml-1 opacity-100 duration-300">Profile</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <form method="POST" action="{{ route("logout") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="py-2.7 ease-nav-brand mx-2 my-0 flex items-center whitespace-nowrap px-4 text-sm transition-colors hover:rounded-lg hover:bg-blue-500/10 dark:text-white">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ri-login-box-line relative top-0 text-lg leading-normal text-cyan-500"></i>
                        </div>
                        <button type="submit" class="ease ml-1 opacity-100 duration-300">Logout</button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</aside>
