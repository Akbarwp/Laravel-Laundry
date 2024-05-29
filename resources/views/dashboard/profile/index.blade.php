@extends("dashboard.layouts.main")

@section("js")
    <script>
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
            {{-- Awal Form --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <div class="flex items-center gap-x-3">
                        <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                        <a href="{{ route("profile.edit", $user->slug) }}" class="btn btn-warning btn-sm max-w-md text-slate-700">
                            <i class="ri-pencil-fill text-lg"></i>
                            Ubah User
                        </a>
                        <a href="{{ route("profile.edit.password", $user->slug) }}" class="btn btn-outline tooltip btn-primary btn-sm" data-tip="Ganti Password">
                            <i class="ri-lock-password-line text-base"></i>
                        </a>
                    </div>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Foto Profile</span>
                        </div>
                        @if ($profile->foto)
                            <div class="avatar">
                                <div class="w-24 rounded-full">
                                    <img src="{{ asset("storage/" . $profile->foto) }}" alt="{{ $user->slug }}" />
                                </div>
                            </div>
                        @else
                            <input type="file" class="file-input file-input-bordered w-full dark:file-input-info" type="file" name="foto" id="foto" disabled />
                        @endif
                    </label>
                    <div class="flex w-full flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Role</span>
                            </div>
                            <input type="text" name="role" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->roles[0]->name }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Cabang</span>
                            </div>
                            <input type="text" name="cabang_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->cabang ? $user->cabang->nama : "-" }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Username</span>
                        </div>
                        <input type="text" name="username" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->username }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Email</span>
                        </div>
                        <input type="email" name="email" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->email }}" readonly />
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Nama</span>
                        </div>
                        <input type="text" name="nama" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->nama }}" readonly />
                    </label>
                    <div class="mt-3 w-full max-w-md">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Jenis Kelamin</span>
                        </div>
                        <div class="rounded-lg border border-slate-300 px-3 py-2">
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text text-blue-700 dark:text-blue-300">Laki-laki</span>
                                    <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" @if ($profile->jenis_kelamin == "L") checked @endif disabled />
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text text-blue-700 dark:text-blue-300">Perempuan</span>
                                    <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" @if ($profile->jenis_kelamin == "P") checked @endif disabled />
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full flex-wrap justify-center gap-2 lg:flex-nowrap">
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Tempat Lahir</span>
                            </div>
                            <input type="text" name="tempat_lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->tempat_lahir }}" readonly />
                        </label>
                        <label class="form-control w-full lg:w-1/2">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Tanggal Lahir</span>
                            </div>
                            <input type="date" name="tanggal_lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->tanggal_lahir }}" readonly />
                        </label>
                    </div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Telepon</span>
                        </div>
                        <input type="text" name="telepon" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->telepon }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold">Alamat</span>
                        </div>
                        <textarea name="alamat" class="textarea textarea-bordered w-full text-base text-blue-700" readonly>{{ $profile->alamat }}</textarea>
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Mulai Kerja</span>
                        </div>
                        <input type="date" name="mulai_kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->mulai_kerja }}" readonly />
                    </label>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">Selesai Kerja</span>
                        </div>
                        <input type="date" name="selesai_kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->selesai_kerja }}" readonly />
                    </label>

                    @if ($user->roles[0]->name == "gamis")
                        <div class="flex w-full flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Kartu Keluarga</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->kartu_keluarga : "-" }}" readonly />
                            </label>
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">RT</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->rt : "-" }}" readonly />
                            </label>
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">RW</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->rw : "-" }}" readonly />
                            </label>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Akhir Form --}}
        </div>
    </div>
@endsection
