@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function () {
            $("select[name='role']").change(function() {
                if ($("select[name='role']").find(":selected").text() == 'rw') {
                    $("#form_rw").append(`
                        <label id="form_nomor_rw" class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Nomor RW'" />
                                </span>
                            </div>
                            <input type="number" min="1" step="1" name="nomor_rw" placeholder="Nomor RW" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("nomor_rw") }}" required />
                            @error("nomor_rw")
                                <div class="label">
                                    <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                    `);
                } else {
                    $("#form_nomor_rw").remove();
                }
            });
        });

        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: '{{ $title }} Gagal Dibuat',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            })
        @endif
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Form Tambah --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="mb-3">
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                    </div>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <form action="{{ route("rw.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Role'" />
                                </span>
                            </div>
                            <select name="role" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                <option disabled selected>Pilih Role!</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error("role")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>

                        <div id="form_rw"></div>

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Username'" />
                                </span>
                            </div>
                            <input type="text" name="username" placeholder="Username" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("username") }}" required />
                            @error("username")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Email'" />
                                </span>
                            </div>
                            <input type="email" name="email" placeholder="Email" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("email") }}" required />
                            @error("email")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Password'" />
                                    </span>
                                </div>
                                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                                @error("password")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Konfirmasi Password'" />
                                    </span>
                                </div>
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                            </label>
                        </div>

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Nama'" />
                                </span>
                            </div>
                            <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("nama") }}" required />
                            @error("nama")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <div class="mt-3 w-full max-w-md">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Jenis Kelamin'" />
                                </span>
                            </div>
                            <div class="rounded-lg border border-slate-300 px-3 py-2">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700 dark:text-blue-300">Laki-laki</span>
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" required />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700 dark:text-blue-300">Perempuan</span>
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" required />
                                    </label>
                                </div>
                            </div>
                            @error("jenis_kelamin")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Tempat Lahir'" />
                                    </span>
                                </div>
                                <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("tempat_lahir") }}" required />
                                @error("tempat_lahir")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Tanggal Lahir'" />
                                    </span>
                                </div>
                                <input type="date" name="tanggal_lahir" placeholder="Tanggal Lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("tanggal_lahir") }}" required />
                                @error("tanggal_lahir")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Telepon'" />
                                </span>
                            </div>
                            <input type="text" name="telepon" placeholder="Telepon" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("telepon") }}" required />
                            @error("telepon")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <x-label-input-required :value="'Alamat'" />
                                </span>
                            </div>
                            <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-base text-blue-700" required>{{ old("alamat") }}</textarea>
                            @error("alamat")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Mulai Kerja</span>
                            </div>
                            <input type="date" name="mulai_kerja" placeholder="Mulai Kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ old("mulai_kerja") }}" />
                            @error("mulai_kerja")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>

                        <div class="mt-5 flex flex-wrap justify-center gap-2">
                            <button type="submit" class="btn btn-success w-full max-w-md text-white">Tambah</button>
                            <a href="{{ route('rw') }}" class="btn btn-ghost w-full max-w-md bg-slate-500 text-white dark:bg-slate-500 dark:hover:opacity-80">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Form Tambah --}}
        </div>
    </div>
@endsection
