@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            $("select[name='role']").change(function() {
                if ($("select[name='role']").find(":selected").text() == 'gamis') {
                    $("#form_gamis").append(`
                        <label id="form_kk_gamis" class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">
                                    <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Kartu Keluarga Gamis | '" />
                                    </span>
                                    <a href="{{ route('gamis') }}" class="link link-primary dark:link-accent">Sudah membuat KK Gamis?</a>
                                </span>
                            </div>
                            <select name="gamis_id" class="select select-bordered" required>
                                <option disabled selected>Pilih Kartu Keluarga!</option>
                                @foreach ($kkGamis as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        @if ($item->id == $profile->gamis_id) selected @endif
                                        >
                                        KK: {{ $item->kartu_keluarga }}
                                        RT: {{ $item->rt }}
                                        RW: {{ $item->rw }}
                                    </option>
                                @endforeach
                            </select>
                            @error("gamis_id")
                                <div class="label">
                                    <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                    `);
                } else {
                    $("#form_kk_gamis").remove();
                }
            });

            if ($("select[name='role']").find(":selected").text() == 'gamis') {
                $("#form_gamis").append(`
                    <label id="form_kk_gamis" class="form-control w-full">
                        <div class="label">
                            <span class="label-text font-semibold dark:text-slate-100">
                                <span class="label-text font-semibold dark:text-slate-100">
                                        <x-label-input-required :value="'Kartu Keluarga Gamis | '" />
                                    </span>
                                <a href="{{ route('gamis') }}" class="link link-primary dark:link-accent">Sudah membuat KK Gamis?</a>
                            </span>
                        </div>
                        <select name="gamis_id" class="select select-bordered" required>
                            <option disabled selected>Pilih Kartu Keluarga!</option>
                            @foreach ($kkGamis as $item)
                                <option
                                    value="{{ $item->id }}"
                                    @if ($item->id == $profile->gamis_id) selected @endif
                                    >
                                    KK: {{ $item->kartu_keluarga }}
                                    RT: {{ $item->rt }}
                                    RW: {{ $item->rw }}
                                </option>
                            @endforeach
                        </select>
                        @error("gamis_id")
                            <div class="label">
                                <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </label>
                `);
            } else {
                $("#form_kk_gamis").remove();
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
                    <form action="{{ route("user.update", $user->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Role'" />
                                    </span>
                                </div>
                                <select name="role" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    <option disabled selected>Pilih Role!</option>
                                    @foreach ($role as $item)
                                        <option value="{{ $item->name }}" @if ($item->name == $user->roles[0]->name) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error("role")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                        <x-label-input-required :value="'Cabang'" />
                                    </span>
                                </div>
                                <select name="cabang_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100">
                                    <option disabled selected>Pilih Cabang!</option>
                                    @foreach ($cabang as $item)
                                        <option value="{{ $item->id }}" @if ($item->id == auth()->user()->cabang_id || $item->id == $user->cabang_id) selected @endif>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                @error("cabang_id")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>

                        <div id="form_gamis"></div>

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Username'" />
                                </span>
                            </div>
                            <input type="text" name="username" placeholder="Username" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->username }}" required />
                            @error("username")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Email'" />
                                </span>
                            </div>
                            <input type="email" name="email" placeholder="Email" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $user->email }}" required />
                            @error("email")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Nama'" />
                                </span>
                            </div>
                            <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->nama }}" required />
                            @error("nama")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <div class="mt-3 w-full max-w-md">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Jenis Kelamin'" />
                                </span>
                            </div>
                            <div class="rounded-lg border border-slate-300 px-3 py-2">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700 dark:text-blue-300">Laki-laki</span>
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" @if ($profile->jenis_kelamin == "L") checked @endif required />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700 dark:text-blue-300">Perempuan</span>
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" @if ($profile->jenis_kelamin == "P") checked @endif required />
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
                                    <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Tempat Lahir'" />
                                </span>
                                </div>
                                <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->tempat_lahir }}" required />
                                @error("tempat_lahir")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Tanggal Lahir'" />
                                </span>
                                </div>
                                <input type="date" name="tanggal_lahir" placeholder="Tanggal Lahir" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->tanggal_lahir }}" required />
                                @error("tanggal_lahir")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                        </div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Telepon'" />
                                </span>
                            </div>
                            <input type="text" name="telepon" placeholder="Telepon" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->telepon }}" required />
                            @error("telepon")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">
                                    <x-label-input-required :value="'Alamat'" />
                                </span>
                            </div>
                            <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-base text-blue-700" required>{{ $profile->alamat }}</textarea>
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
                            <input type="date" name="mulai_kerja" placeholder="Mulai Kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->mulai_kerja }}" />
                            @error("mulai_kerja")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Selesai Kerja</span>
                            </div>
                            <input type="date" name="selesai_kerja" placeholder="Selesai Kerja" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" value="{{ $profile->selesai_kerja }}" />
                            @error("selesai_kerja")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>

                        <div class="mt-5 flex flex-wrap justify-center gap-2">
                            <button type="submit" class="btn btn-warning w-full max-w-md text-slate-700">Perbarui</button>
                            <a href="{{ url()->previous() }}" class="btn btn-ghost w-full max-w-md bg-slate-500 text-white dark:bg-slate-500 dark:hover:opacity-80">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Form --}}
        </div>
    </div>
@endsection
