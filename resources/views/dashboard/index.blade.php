@extends("dashboard.layouts.main")

@section("container")
    <div>
        <!-- row 1 -->
        <div class="-mx-3 flex flex-wrap">
            <!-- card1 -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                        Today's Money</p>
                                    <h5 class="mb-2 font-bold dark:text-white">$53,000</h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-bold leading-normal text-emerald-500">+55%</span>
                                        since yesterday
                                    </p>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-blue-500 to-violet-500 text-center">
                                    <i class="ri-money-dollar-box-line relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                        Today's Users</p>
                                    <h5 class="mb-2 font-bold dark:text-white">2,300</h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-bold leading-normal text-emerald-500">+3%</span>
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-red-600 to-orange-600 text-center">
                                    <i class="ri-earth-line relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card3 -->
            <div class="mb-6 w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                        New Clients</p>
                                    <h5 class="mb-2 font-bold dark:text-white">+3,462</h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-bold leading-normal text-red-600">-2%</span>
                                        since last quarter
                                    </p>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-emerald-500 to-teal-400 text-center">
                                    <i class="ri-file-paper-2-line relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl bg-white bg-clip-border shadow-xl">
                    <div class="flex-auto p-4">
                        <div class="-mx-3 flex flex-row">
                            <div class="w-2/3 max-w-full flex-none px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold uppercase leading-normal dark:text-white dark:opacity-60">
                                        Sales</p>
                                    <h5 class="mb-2 font-bold dark:text-white">$103,430</h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-bold leading-normal text-emerald-500">+5%</span>
                                        than last month
                                    </p>
                                </div>
                            </div>
                            <div class="basis-1/3 px-3 text-right">
                                <div class="rounded-circle inline-block h-12 w-12 bg-gradient-to-tl from-orange-500 to-yellow-500 text-center">
                                    <i class="ri-shopping-cart-2-fill relative top-3 text-2xl leading-none text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
