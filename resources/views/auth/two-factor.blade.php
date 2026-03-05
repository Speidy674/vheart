<x-layout title="Two Factor" class="flex flex-col items-center justify-center max-w-lg m-auto">
    <div x-data="twoFactorForm">
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title class="space-y-2 text-center">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ __('auth.two-factor.heading') }}
                    </h1>
                </x-ui.card.title>
            </x-ui.card.header>
            <x-ui.card.content class="space-y-6">
                <p class="text-sm text-muted-foreground text-center">
                    {{ __('auth.two-factor.subheading') }}
                </p>

                <form action="{{ route('auth.challenge.submit') }}" method="POST" class="space-y-6" @submit="this.processing = true">
                    <div class="space-y-2">
                        <label for="code" class="block text-sm font-medium text-foreground" x-text="mode === 'otp' ? labels.codeLabel : labels.backupLabel">
                            {{ request('mode', 'otp') === 'otp' ? __('auth.two-factor.form.code.label') : __('auth.two-factor.form.backup.label') }}
                        </label>
                        <x-ui.input
                            id="code"
                            name="code"
                            type="text"
                            autocomplete="one-time-code"
                            autofocus
                            class="h-14 text-center font-bold tracking-[0.5em] data-[mode=backup]:tracking-normal"
                            required

                            aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
                            data-mode="{{ request('mode', 'otp') }}"
                            inputmode="{{ request('mode', 'otp') === 'otp' ? 'numeric' : 'text' }}"
                            pattern="{{ request('mode', 'otp') === 'otp' ? '[0-9]*' : '' }}"
                            minlength="{{ request('mode', 'otp') === 'otp' ? '6' : '21' }}"
                            maxlength="{{ request('mode', 'otp') === 'otp' ? '6' : '21' }}"
                            placeholder="{{ request('mode', 'otp') === 'otp' ? '••••••' : __('auth.two-factor.form.backup.label') }}"

                            x-ref="input"
                            x-bind:data-mode="mode"
                            x-bind:disabled="processing"
                            x-bind:inputmode="mode === 'otp' ? 'numeric' : 'text'"
                            x-bind:pattern="mode === 'otp' ? '[0-9]*' : null"
                            x-bind:minlength="mode === 'otp' ? '6' : '21'"
                            x-bind:maxlength="mode === 'otp' ? '6' : '21'"
                            x-bind:placeholder="mode === 'otp' ? '••••••' : labels.backupLabel"
                        />
                        @error('code')
                            <p class="text-sm font-medium text-destructive text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-ui.button
                        type="submit"
                        class="w-full"
                        x-bind:disabled="processing"
                    >
                        {{ __('auth.two-factor.form.submit') }}
                    </x-ui.button>

                    <x-ui.button
                        variant="link"
                        as="a"
                        href="{{ request()->fullUrlWithQuery(['mode' => request('mode', 'otp') === 'otp' ? 'backup' : 'otp']) }}"
                        @click.prevent="toggleMode"
                        x-text="mode === 'otp' ? labels.toggleOtpLabel : labels.toggleBackupLabel"
                        class="w-full"
                        x-bind:disabled="processing"
                    >
                        {{ request('mode', 'otp') === 'otp' ? __('auth.two-factor.form.mode-toggle.otp.label') : __('auth.two-factor.form.mode-toggle.backup.label') }}
                    </x-ui.button>
                </form>
            </x-ui.card.content>
        </x-ui.card>
    </div>

    @pushonce('elements')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('twoFactorForm', () => ({
                    processing: false,
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
                        this.$refs.input.value = null;
                        this.$nextTick(() => {
                            this.$refs.input.focus();
                        });
                    }
                }));
            });
        </script>
    @endpushonce
</x-layout>
