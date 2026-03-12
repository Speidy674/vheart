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
