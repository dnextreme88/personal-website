<div>
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <section class="h-[125vh] px-6 bg-linear-to-r from-white to-blue-300 pt-36 pb-80 dark:from-slate-400 dark:bg-linear-to-br dark:to-blue-600 sm:py-32 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-5xl font-semibold tracking-tight text-gray-800 dark:text-gray-100 sm:text-7xl" aria-label="Welcome header">Welcome!</h2>

            {{--
            <p class="mt-8 text-lg font-medium text-gray-600 text-pretty dark:text-gray-100 sm:text-xl/8" aria-label="Welcome description">My name is Jeanne Kevin T. Decena and welcome to my personal website! Here you can find all the goodies I've been doing in my life - from my blog and to my portfolio. Feel free to look around! If you need some web development professional, look no further and contact me right away!</p>
            --}}
        </div>
    </section>

    <section class="bg-gray-200 dark:bg-gray-800">
        <div class="isolate">
            <!-- Hero section -->
            <div class="relative isolate -z-10">
                <svg aria-hidden="true" class="absolute inset-x-0 top-0 -z-10 h-256 w-full mask-[radial-gradient(28rem_36rem_at_center,white,transparent)] stroke-gray-200 dark:stroke-white/10">
                    <defs>
                        <pattern id="1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                            <path d="M.5 200V.5H200" fill="none" />
                        </pattern>
                    </defs>

                    <svg x="50%" y="-1" class="overflow-visible fill-gray-300 dark:fill-gray-700">
                        <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z" stroke-width="0" />
                    </svg>

                    <rect width="100%" height="100%" fill="url(#1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84)" stroke-width="0" />
                </svg>

                <div aria-hidden="true" class="absolute top-0 right-0 left-1/2 -z-10 -ml-24 transform-gpu overflow-hidden blur-2xl lg:ml-24 xl:ml-48">
                    <div style="clip-path: polygon(63.1% 29.5%, 100% 17.1%, 76.6% 3%, 48.4% 0%, 44.6% 4.7%, 54.5% 25.3%, 59.8% 49%, 55.2% 57.8%, 44.4% 57.2%, 27.8% 47.9%, 35.1% 81.5%, 0% 97.7%, 39.2% 100%, 35.2% 81.4%, 97.2% 52.8%, 63.1% 29.5%)" class="aspect-801/1036 w-200.25 bg-linear-to-tr from-purple-400 to-blue-300 opacity-30"></div>
                </div>

                <div class="mx-auto max-w-7xl px-6 pt-36 pb-32 sm:pt-60 lg:px-8 lg:pt-32">
                    <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">
                        <div class="relative w-full lg:max-w-lg lg:shrink-0 xl:max-w-xl">
                            <h1 class="text-5xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-7xl dark:text-white transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100">Web Developer.</h1>
                            <p class="mt-8 mb-6 text-lg font-medium text-pretty text-gray-500 sm:max-w-lg sm:text-xl/8 lg:max-w-none dark:text-gray-400 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">My name is Jeanne Kevin T. Decena and welcome to my personal website! Here you can find all the goodies I've been doing in my life - from my blog and to my portfolio. Feel free to look around! If you need some web development professional, look no further and contact me right away!</p>
                        </div>

                        {{-- TODO: TO FIX RESPONSIVE DESIGN ON 1024px AND UP --}}
                        <div class="mt-14 flex justify-end gap-8 sm:flex-row sm:-mt-10 sm:justify-start sm:pl-2 md:pl-16 lg:mt-0 lg:pl-0">
                            <div class="mx-auto sm:ml-auto w-44 flex-none space-y-8 pt-32 sm:pt-80 lg:order-last lg:pt-66 xl:order-0">
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-1.webp') }}" class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 200ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>

                            <div class="mx-auto sm:mr-auto w-44 flex-none space-y-8 sm:pt-52 lg:pt-16">
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-2.webp') }}" class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 400ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-3.webp') }}" class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 600ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>

                            <div class="mx-auto w-44 flex-none space-y-8 lg:pt-36 xl:pt-0">
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-4.webp') }}" class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 800ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-5.webp') }}" class="aspect-2/3 w-full rounded-xl bg-gray-900/5 object-cover shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 1000ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blocky background -->
            <div class="relative isolate -z-10 -mt-20 md:mt-30 xl:mt-40">
                <div class="absolute inset-x-0 top-1/2 -z-10 flex -translate-y-1/2 justify-center overflow-hidden mask-[radial-gradient(75%_60%_at_50%_60%,white,transparent)] mt-30 sm:mt-0">
                    <svg aria-hidden="true" class="h-160 w-7xl flex-none stroke-gray-200 dark:stroke-white/10">
                        <defs>
                            <pattern id="e9033f3e-f665-41a6-84ef-756f6778e6fe" width="200" height="200" x="50%" y="25%" patternUnits="userSpaceOnUse" patternTransform="translate(-100 0)">
                                <path d="M.5 200V.5H200" fill="none" />
                            </pattern>
                        </defs>

                        <svg x="50%" y="25%" class="overflow-visible fill-gray-300 dark:fill-gray-700">
                            <path d="M-300 0h201v201h-201Z M300 200h201v201h-201Z" stroke-width="0" />
                        </svg>

                        <rect width="100%" height="100%" fill="url(#e9033f3e-f665-41a6-84ef-756f6778e6fe)" stroke-width="0" />
                    </svg>
                </div>
            </div>

            <!-- Mini section for About Me -->
            <div class="mx-auto max-w-7xl pb-16 pt-32 px-6 sm:mt-40 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
                    <h2 class="text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl dark:text-white transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100">Relevant Experiences</h2>
                </div>

                <div class="flex flex-col mx-auto max-w-2xl mt-10 mb-5 md:grid lg:mx-0 lg:max-w-none grid-cols-[5%_1fr]">
                    <x-timeline :is_current="true">
                        <x-slot name="content">
                            <div>
                                <h2 class="font-semibold mb-2 text-2xl text-gray-800 dark:text-gray-100 lg:text-xl">MachShip Philippines</h2>
                                <h3 class="font-bold mb-2 text-base text-gray-800 dark:text-gray-100">Workflow Scripter</h3>
                                <p class="font-bold mb-2 text-gray-800 dark:text-gray-100">February 2025 - present</p>

                                <ul class="list-disc list-inside font-bold text-gray-800 dark:text-gray-100">
                                    <li>Create and maintain workflows of carrier integrations using the Limber platform</li>
                                    <li>Create solutions for integrating existing carriers on the MachShip platform into Limber</li>
                                    <li>Test carrier integrations on the MachShip platform</li>
                                </ul>
                            </div>
                        </x-slot>
                    </x-timeline>

                    <x-timeline :is_current="false">
                        <x-slot name="content">
                            <div>
                                <h2 class="font-semibold text-2xl mb-2 text-gray-800 dark:text-gray-100 lg:text-xl">Zeldan Nordic Languages Review Center</h2>
                                <h3 class="text-base mb-2 text-gray-800 dark:text-gray-100">Laravel Developer (Part-time)</h3>
                                <p class="mb-2 text-gray-800 dark:text-gray-100">June 2024 - December 2024</p>

                                <ul class="list-disc list-inside text-gray-800 dark:text-gray-100">
                                    <li>Develop a simple learning website for trainees and instructors for their language training courses using the TALL stack (Tailwind, AlpineJS, Laravel, and Livewire)</li>
                                    <li>Technologies used: AlpineJS, JavaScript, Laravel 11, Livewire, PHP 8.3, and Tailwind</li>
                                </ul>
                            </div>
                        </x-slot>
                    </x-timeline>

                    <x-timeline :is_current="false">
                        <x-slot name="content">
                            <div>
                                <h2 class="font-semibold text-2xl mb-2 text-gray-800 dark:text-gray-100 lg:text-xl">Think Bullish</h2>
                                <h3 class="text-base mb-2 text-gray-800 dark:text-gray-100">Virtual Assistant</h3>
                                <p class="mb-2 text-gray-800 dark:text-gray-100">January 2024 - August 2024</p>

                                <ul class="list-disc list-inside text-gray-800 dark:text-gray-100">
                                    <li>Assist in workflow automations, ensure updated data, and review prospect leads using GoHighLevel CRM</li>
                                    <li>Quality assurance calls based on company criteria by reviewing calls of appointment setters using VICIdial</li>
                                    <li>Review leads for the waitlist process and check if they conflict with any existing active clients</li>
                                </ul>
                            </div>
                        </x-slot>
                    </x-timeline>
                </div>

                <div class="mx-auto max-w-2xl mb-2 lg:mx-0 lg:max-w-none">
                    <a
                        wire:navigate
                        href="{{ route('about_me') }}"
                        class="inline-block mt-2 text-lg/8 text-blue-800 dark:text-blue-200 hover:underline transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-3 intersect:opacity-100"
                        title="View the full resume"
                        aria-label="About Me link"
                    >
                        View the full resume &rarr;
                    </a>
                </div>
            </div>

            <!-- Latest posts from blog section -->
            <div class="mx-auto max-w-7xl pb-16 pt-32 px-6 sm:mt-40 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
                    <h2 class="text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl dark:text-white transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100">From the Blog...</h2>
                    <p class="mt-2 text-lg/8 text-gray-600 dark:text-gray-400 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Latest updates from me!</p>
                </div>

                <livewire:blog.latest-posts />
            </div>

            <!-- Current computer specs section -->
            <div class="relative isolate -z-10">
                <svg aria-hidden="true" class="absolute inset-x-0 top-0 -z-10 h-256 w-full mask-[radial-gradient(28rem_36rem_at_center,white,transparent)] stroke-gray-200 dark:stroke-white/10">
                    <defs>
                        <pattern id="1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                            <path d="M.5 200V.5H200" fill="none" />
                        </pattern>
                    </defs>

                    <svg x="50%" y="-1" class="overflow-visible fill-gray-300 dark:fill-gray-700">
                        <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z" stroke-width="0" />
                    </svg>

                    <rect width="100%" height="100%" fill="url(#1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84)" stroke-width="0" />
                </svg>

                <div aria-hidden="true" class="absolute top-0 right-0 left-1/2 -z-10 -ml-24 transform-gpu overflow-hidden blur-2xl lg:ml-24 xl:ml-48">
                    <div style="clip-path: polygon(63.1% 29.5%, 100% 17.1%, 76.6% 3%, 48.4% 0%, 44.6% 4.7%, 54.5% 25.3%, 59.8% 49%, 55.2% 57.8%, 44.4% 57.2%, 27.8% 47.9%, 35.1% 81.5%, 0% 97.7%, 39.2% 100%, 35.2% 81.4%, 97.2% 52.8%, 63.1% 29.5%)" class="aspect-801/1036 w-200.25 bg-linear-to-tr from-purple-400 to-blue-300 opacity-30"></div>
                </div>

                <div class="mx-auto max-w-7xl px-6 pt-36 pb-32 sm:pt-60 lg:px-8 lg:pt-32">
                    <div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-start">
                        <div class="relative w-full lg:max-w-lg lg:shrink-0 xl:max-w-xl">
                            <h1 class="text-5xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-7xl dark:text-white transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100">Current Specs!</h1>
                            <p class="mt-8 mb-6 text-lg font-medium text-pretty text-gray-500 sm:max-w-lg sm:text-xl/8 lg:max-w-none dark:text-gray-400 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Last update: November 13, 2025</p>

                            <div class="flex flex-wrap gap-2 flex-col space-y-1">
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Internet Service Provider (ISP)</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">PLDT 200Mbp/s</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Memory</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">TEAMGROUP T-Force Delta RGB White 16GB (2x8GB) DDR4 3600MHz</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Webcam</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">GSou T16s 1080p</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Monitor 1</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">ASUS ROG XG27ACS-W 27" 180Hz</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Monitor 2</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500 italic">TBD</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">CPU</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">AMD Ryzen 7 5700X AM4</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">GPU</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">MSi RTX 4060 Ventus 2X White OC 8GB GDDR6</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">PSU</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Corsair RM850 850W White</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">CPU AIO Cooler</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">NZXT Kraken X53 RGB White</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Motherboard</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">MSi B550M PRO-VDH Wifi AM4</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Fans</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">NZXT F120 RGB Core Fans x5</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">SSD 1</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Kingston NV2 NVMe M.2 500GB</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">SSD 2</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">ADATA NVMe 1TB</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Computer Case</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">NZXT H7 Flow White</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Speakers</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Redragon GS520 Anvil RGB White</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Keyboard</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">AULA F87 TKL Wireless Mechanical Keyboard</div>
                                <div class="dark:text-gray-400 even:font-bold even:ml-2">Mouse</div>
                                <div class="dark:text-gray-200 even:font-bold even:ml-2 transition duration-2000 -translate-x-32 opacity-0 intersect-once intersect-half intersect:translate-x-0 intersect:opacity-100 delay-500">Redragon Woki Supperlight Wireless Mouse</div>
                            </div>
                        </div>

                        <div class="mt-14 hidden lg:flex justify-end gap-8 sm:flex-row sm:-mt-10 sm:justify-start sm:pl-2 md:pl-16 lg:mt-0 lg:pl-0">
                            <div class="space-y-8 pt-32 sm:pt-48 lg:pt-32">
                                <div class="relative">
                                    <img src="{{ asset('/images/homepage-6.webp') }}" class="aspect-square w-full rounded-xl bg-gray-900/5 shadow-lg dark:bg-gray-700/5 hover:scale-110 -translate-x-32 opacity-0 intersect-once intersect:translate-x-0 intersect:opacity-100" style="transition: scale 0.25s, translate 2s, opacity 3s 200ms;" />
                                    <div class="absolute inset-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-slot name="footer">
        <x-social-links-and-copyright />
    </x-slot>
</div>
