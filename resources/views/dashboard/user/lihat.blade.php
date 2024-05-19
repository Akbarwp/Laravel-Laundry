@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            if ($("input[name='role']").value() == 'lurah' || $("input[name='role']").value() == 'rw') {
                $("input[name='cabang_id']").attr('disabled', true);
            } else {
                $("input[name='cabang_id']").attr('disabled', false);
            }

            if ($("input[name='role']").value() == 'gamis') {
                $("#form_gamis").append(`
                    <label id="form_kk_gamis" class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">
                                Kartu Keluarga Gamis |
                                <a href="#" class="link link-primary">Sudah membuat KK Gamis?</a>
                            </span>
                        </div>
                        <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? 'tes' : 'ada' }}" readonly />
                    </label>
                `);
            }
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
            {{-- Awal Form --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }}</h6>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
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
                            <input type="text" name="cabang_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->cabang? $user->cabang->nama : '-' }}" readonly />
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
                    <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
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
                        <input type="date" name="mulai_kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->mulai_kerja }}" />
                    </label>

                    @if($user->roles[0]->name == "gamis")
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Kartu Keluarga</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->kartu_keluarga : '-' }}" readonly />
                            </label>
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">RT</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->rt : '-' }}" readonly />
                            </label>
                            <label class="form-control w-full lg:w-1/3">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">RW</span>
                                </div>
                                <input type="text" name="gamis_id" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->gamis ? $profile->gamis->rw : '-' }}" readonly />
                            </label>
                        </div>
                    @endif

                    <div class="mt-5 flex flex-wrap justify-center gap-2">
                        <a href="{{ route("user.edit", $user->slug) }}" class="btn btn-warning w-full max-w-md text-slate-700">Ubah User</a>
                        <a href="{{ url()->previous() }}" class="btn btn-ghost w-full max-w-md bg-slate-500 text-white dark:bg-slate-500 dark:hover:opacity-80">Kembali</a>
                    </div>
                </div>
            </div>
            {{-- Akhir Form --}}
        </div>
    </div>
@endsection
