<x-ui.input
    id="code"
    name="code"
    type="text"
    autocomplete="one-time-code"
    autofocus
    class="h-14 text-center font-mono font-bold tracking-[0.5em] data-[mode=backup]:tracking-normal"
    required

    aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
    data-mode="{{ request('mode', 'otp') }}"
    inputmode="{{ request('mode', 'otp') === 'otp' ? 'numeric' : 'text' }}"
    pattern="{{ request('mode', 'otp') === 'otp' ? '[0-9]*' : '' }}"
    minlength="{{ request('mode', 'otp') === 'otp' ? '6' : '21' }}"
    maxlength="{{ request('mode', 'otp') === 'otp' ? '6' : '21' }}"
    placeholder="{{ request('mode', 'otp') === 'otp' ? '••••••' : __('auth.two-factor.form.backup.label') }}"

    x-ref="twoFactorCodeInput"
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
