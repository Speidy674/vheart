<section class="w-full">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-start mb-8">
        <x-ui.card class="rounded-2xl border border-gray-200 bg-gradient-to-br from-white/70 via-white/85 to-white/70 p-8 shadow-2xl ring-1 shadow-black/10 ring-black/5 dark:border-white/20 dark:bg-black/30 dark:!bg-none dark:!from-transparent dark:!via-transparent dark:!to-transparent dark:ring-0 dark:shadow-purple-900/30">
            <div class="flex justify-center pt-10 pb-6">
                <x-ui.branding.banner class="h-32 w-auto" />
            </div>

            <div class="px-6 pb-8 sm:px-10 sm:pb-12">
                <div class="mx-auto max-w-5xl">
                    <div class="mb-10 text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl dark:text-white">
                            {{ __('about.hero.title_prefix') }}
                            <span class="bg-gradient-to-r from-purple-700 via-gray-900 to-cyan-700 bg-clip-text text-transparent dark:from-purple-300 dark:via-white dark:to-cyan-300">
                                {{ __('about.hero.brand') }}
                            </span>
                        </h1>

                        <p class="mx-auto mt-6 max-w-3xl text-base leading-relaxed text-gray-800 sm:text-lg dark:text-white/90">
                            {{ __('about.hero.description') }}
                        </p>

                        <div class="mt-6 flex flex-wrap justify-center gap-2">
                            <span class="rounded-full border border-gray-300/80 bg-gradient-to-r from-purple-100/80 to-cyan-100/70 px-3 py-1.5 text-sm font-medium text-gray-900/90 dark:border-white/15 dark:bg-gradient-to-r dark:from-purple-500/20 dark:to-cyan-500/20 dark:text-white/85">
                                {{ __('about.hero.tags.tag1') }}
                            </span>
                            <span class="rounded-full border border-gray-300/80 bg-gradient-to-r from-purple-100/80 to-cyan-100/70 px-3 py-1.5 text-sm font-medium text-gray-900/90 dark:border-white/15 dark:bg-gradient-to-r dark:from-purple-500/20 dark:to-cyan-500/20 dark:text-white/85">
                                {{ __('about.hero.tags.tag2') }}
                            </span>
                            <span class="rounded-full border border-gray-300/80 bg-gradient-to-r from-purple-100/80 to-cyan-100/70 px-3 py-1.5 text-sm font-medium text-gray-900/90 dark:border-white/15 dark:bg-gradient-to-r dark:from-purple-500/20 dark:to-cyan-500/20 dark:text-white/85">
                                {{ __('about.hero.tags.tag3') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>
</section>
