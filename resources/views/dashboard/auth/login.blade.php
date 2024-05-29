<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" data-theme="light" class="scroll-smooth">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login | Laundry</title>

        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("img/apple-icon.png") }}" />
        <link rel="icon" type="image/png" href="{{ asset("img/favicon.png") }}" />

        <link rel="stylesheet" href="{{ asset("css/argon-dashboard-tailwind.css?v=1.0.1") }}">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>

    <body class="leading-default m-0 bg-white text-start font-sans text-base font-normal text-slate-500 antialiased">
        <main class="mt-0 transition-all duration-200 ease-in-out">
            <section>
                <div class="relative flex min-h-screen items-center overflow-hidden bg-cover bg-center p-0">
                    <div class="z-1 container">
                        <div class="-mx-3 flex flex-wrap">
                            <div class="md:flex-0 mx-auto flex w-full max-w-full shrink-0 flex-col px-3 md:w-7/12 lg:mx-0 lg:w-5/12 xl:w-4/12">
                                <div class="lg:py4 relative flex min-w-0 flex-col break-words rounded-2xl border-0 bg-transparent bg-clip-border shadow-none dark:bg-gray-950">
                                    <div class="mb-0 p-6 pb-0">
                                        <h4 class="font-bold text-lg">Login</h4>
                                        <p class="mb-0">Masukkan email dan password untuk login</p>
                                    </div>
                                    <div class="flex-auto p-6">
                                        @if ($errors->get('email') || $errors->get('password'))
                                            <div role="alert" class="alert alert-error mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>
                                                    {{ $errors->get('email')[0] }}
                                                </span>
                                            </div>
                                        @endif
                                        <form role="form" action="{{ route("login.attempt") }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-4">
                                                <input type="text" name="email" placeholder="Email" class="focus:shadow-primary-outline leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 text-sm font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none dark:bg-gray-950 dark:text-white/80 dark:placeholder:text-white/80" value="@gmail.com" autofocus required />
                                            </div>
                                            <div class="mb-4">
                                                <input type="password" name="password" placeholder="Password" class="focus:shadow-primary-outline leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 text-sm font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none dark:bg-gray-950 dark:text-white/80 dark:placeholder:text-white/80" value="password" required />
                                            </div>
                                            {{-- <div class="mb-4">
                                                <input type="text" name="email" placeholder="Email" class="focus:shadow-primary-outline leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 text-sm font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none dark:bg-gray-950 dark:text-white/80 dark:placeholder:text-white/80" value="{{ old('email') }}" autofocus required />
                                            </div>
                                            <div class="mb-4">
                                                <input type="password" name="password" placeholder="Password" class="focus:shadow-primary-outline leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 text-sm font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none dark:bg-gray-950 dark:text-white/80 dark:placeholder:text-white/80" required />
                                            </div> --}}
                                            <div class="min-h-6 mb-0.5 flex justify-between items-center text-left">
                                                <div>
                                                    <input id="rememberMe" name="remember" type="checkbox" class="checkbox checkbox-sm border-gray-200 [--chkbg:theme(colors.blue.500)] [--chkfg:white] checked:border-blue-500/95 checked:bg-blue-500/95" />
                                                    <label class="ml-2 cursor-pointer select-none text-sm font-normal text-slate-700" for="rememberMe">Ingat saya</label>
                                                </div>
                                                {{-- <div>
                                                    @if (Route::has('password.request'))
                                                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                                            {{ __('Lupa Password?') }}
                                                        </a>
                                                    @endif
                                                </div> --}}
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="active:opacity-85 hover:shadow-xs tracking-tight-rem bg-150 bg-x-25 mb-0 mt-6 inline-block w-full cursor-pointer rounded-lg border-0 bg-blue-500 px-16 py-3.5 text-center align-middle text-sm font-bold leading-normal text-white shadow-md transition-all ease-in hover:-translate-y-px">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-0 absolute right-0 top-0 my-auto hidden h-full w-6/12 max-w-full flex-col justify-center px-3 pr-0 text-center lg:flex">
                                <div class="relative m-4 flex h-full flex-col justify-center overflow-hidden rounded-xl bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] bg-cover px-24">
                                    <span class="absolute left-0 top-0 h-full w-full bg-gradient-to-tl from-blue-500 to-violet-500 bg-cover bg-center opacity-60"></span>
                                    <h4 class="z-20 mt-12 font-bold text-white">"Cuci bersih, hidup lebih rapi"</h4>
                                    <p class="z-20 text-white">Pakaian yang bersih mencerminkan semangat yang positif. Mulailah harimu dengan keharuman dan kerapihan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <script src="{{ asset("js/argon-dashboard-tailwind.js") }}"></script>
        @vite(["resources/js/app.js"])
    </body>

</html>
