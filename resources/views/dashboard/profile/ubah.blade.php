@extends("dashboard.layouts.main")

@section("js")
    <script>
        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

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
                    <form action="{{ route("profile.update", $user->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Foto Profile</span>
                            </div>
                            <div class="relative">
                                <input type="file" class="file-input file-input-bordered w-full dark:file-input-info" type="file" name="foto" id="foto" onchange="previewImage()" />
                                <div class="avatar mt-3">
                                    <div class="w-24 rounded-full">
                                        @if ($profile->foto)
                                            <img src="{{ asset("storage/" . $profile->foto) }}" alt="{{ $user->slug }}" class="img-preview" />
                                        @else
                                            @if ($profile->jenis_kelamin == "L")
                                                <img class="img-preview" src="{{ asset("img/team-2.jpg") }}" />
                                            @elseif ($profile->jenis_kelamin == "P")
                                                <img class="img-preview" src="{{ asset("img/team-1.jpg") }}" />
                                            @else
                                                <img class="img-preview" src="{{ asset("img/home-decor-1.jpg") }}" />
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @error("foto")
                                <div class="label">
                                    <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Username</span>
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
                                <span class="label-text font-semibold dark:text-slate-100">Email</span>
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
                                <span class="label-text font-semibold dark:text-slate-100">Nama</span>
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
                                <span class="label-text font-semibold dark:text-slate-100">Jenis Kelamin</span>
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
                        <div class="flex w-full flex-wrap justify-center gap-2 lg:flex-nowrap">
                            <label class="form-control w-full lg:w-1/2">
                                <div class="label">
                                    <span class="label-text font-semibold dark:text-slate-100">Tempat Lahir</span>
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
                                    <span class="label-text font-semibold dark:text-slate-100">Tanggal Lahir</span>
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
                                <span class="label-text font-semibold dark:text-slate-100">Telepon</span>
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
                                <span class="label-text font-semibold">Alamat</span>
                            </div>
                            <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full text-base text-blue-700" required>{{ $profile->alamat }}</textarea>
                            @error("alamat")
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
