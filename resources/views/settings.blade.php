@use(App\Enums\FeatureFlag)
<x-layout class="max-w-3xl w-full mx-auto space-y-6" :title="__('user.settings.title')">
    @feature(FeatureFlag::UserDashboard)
        <x-ui.alert>
            <x-ui.alert.title>
                {{ __('user.settings.broadcaster_note.label') }}
            </x-ui.alert.title>
            <x-ui.alert.description class="flex flex-col md:flex-row items-center justify-between">
                <p>
                    {{ __('user.settings.broadcaster_note.description') }}
                </p>
                <x-ui.button href="{{ route('dashboard') }}" variant="link">
                    <span class="md:hidden">
                        {{ __('user.settings.broadcaster_note.link') }}
                    </span>
                    <x-lucide-external-link defer class="size-5" />
                </x-ui.button>
            </x-ui.alert.description>
        </x-ui.alert>
    @endfeature

    <x-ui.card class="shadow-sm">
        <x-ui.card.header class="pb-6 border-b border-border">
            <x-ui.card.title class="text-center text-2xl font-bold tracking-tight">
                <h1>{{ __('user.settings.delete.heading') }}</h1>
            </x-ui.card.title>
        </x-ui.card.header>

        <x-ui.card.content class="p-4 pt-6 space-y-8">
            <section id="consent" class="space-y-6">
                <div class="space-y-1">
                    <h3 class="text-base font-semibold text-foreground">
                        {{ __('user.settings.delete.subheading') }}
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        {{ __('user.settings.delete.description') }}
                    </p>
                </div>

                <form method="post" action="{{ route('user.settings.delete') }}" class="space-y-6">
                    @method('delete')

                    @if($useTwoFactor)
                        <div class="space-y-2" x-data="twoFactorInput">
                            <label for="code" class="block text-sm font-medium text-foreground">
                                {{ __('user.settings.delete.confirmation.two-factor.label') }}
                                Gib zur Bestätigung deinen Zwei-Faktor Code ein
                            </label>
                            <x-ui.input.two-factor />
                            <x-ui.button.two-factor-toggle />
                        </div>

                        @pushonce('elements')
                            <script>
                                document.addEventListener('alpine:init', () => {
                                    Alpine.data('twoFactorInput', () => ({
                                        mode: '{{ request('mode', 'otp') }}',
                                        labels: {
                                            codeLabel: '{{ __('auth.two-factor.form.code.label') }}',
                                            backupLabel: '{{ __('auth.two-factor.form.backup.label') }}',
                                            toggleOtpLabel: '{{ __('auth.two-factor.form.mode-toggle.otp.label') }}',
                                            toggleBackupLabel: '{{ __('auth.two-factor.form.mode-toggle.backup.label') }}'
                                        },

                                        init() {
                                            this.$watch('mode', value => {
                                                const url = new URL(window.location);
                                                url.searchParams.set('mode', value);
                                                window.history.replaceState(null, '', url.toString());
                                            });
                                        },

                                        toggleMode() {
                                            this.mode = this.mode === 'otp' ? 'backup' : 'otp';
                                            this.$refs.twoFactorCodeInput.value = null;
                                            this.$nextTick(() => {
                                                this.$refs.twoFactorCodeInput.focus();
                                            });
                                        }
                                    }));
                                });
                            </script>
                        @endpushonce
                    @else
                        <div class="space-y-2">
                            <label for="code" class="block text-sm font-medium [&_code]:font-mono [&_code]:border [&_code]:px-1 [&_code]:py-0.5 [&_code]:rounded [&_code]:bg-muted [&_code]:select-none">
                                {!! __('user.settings.delete.confirmation.keyword.label', ['keyword' => __('user.settings.delete.confirmation.keyword.keyword')]) !!}
                            </label>
                            <x-ui.input id="code" name="code" type="text" required />
                            @error('code')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <x-ui.button type="submit" variant="destructive">
                            {{ __('user.settings.delete.submit') }}
                        </x-ui.button>
                    </div>
                </form>
            </section>
        </x-ui.card.content>
    </x-ui.card>
</x-layout>
