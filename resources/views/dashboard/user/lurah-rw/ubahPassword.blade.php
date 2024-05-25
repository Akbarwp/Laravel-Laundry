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
            {{-- Awal Form Ganti Password --}}
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                    <h6 class="font-bold dark:text-white">{{ $title }} | <span class="text-blue-500">{{ $user->email }}</span></h6>
                </div>
                <div class="flex-auto px-6 pb-6 pt-0">
                    <form action="{{ route("rw.update.password", $user->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="slug" value="{{ $user->slug }}" hidden >
                        {{-- <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Password Lama</span>
                            </div>
                            <input type="password" name="current_password" placeholder="Password Lama" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </label> --}}
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Password Baru</span>
                            </div>
                            <input type="password" name="password" placeholder="Password Baru" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold dark:text-slate-100">Konfirmasi Password baru</span>
                            </div>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password baru" class="input input-bordered w-full text-blue-700 dark:bg-slate-100" required />
                        </label>

                        <div class="mt-5 flex flex-wrap justify-center gap-2">
                            <button type="submit" class="btn btn-warning w-full max-w-md text-slate-700">Ganti Password</button>
                            <a href="{{ route('rw') }}" class="btn btn-ghost w-full max-w-md bg-slate-500 text-white dark:bg-slate-500 dark:hover:opacity-80">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Form Ganti Password --}}
        </div>
    </div>
@endsection
