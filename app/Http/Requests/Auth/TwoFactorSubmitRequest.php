<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Closure;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

class TwoFactorSubmitRequest extends TwoFactorChallengeRequest
{
    protected bool $isOtp = false;

    public function authorize(): bool
    {
        return $this->session()->has('auth.2fa.id');
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'min:6',
                'max:21',
                function (string $attribute, mixed $value, Closure $fail) {
                    $this->isOtp = ctype_digit($value);
                    $length = mb_strlen($value);

                    if ($this->isOtp && $length !== 6) {
                        $fail(__('auth.two-factor.validation.otp'));
                    }

                    if (! $this->isOtp && $length !== 21) {
                        $fail(__('auth.two-factor.validation.recovery'));
                    }
                },
            ],
        ];
    }

    /**
     * Validates the given code or recovery_code
     *
     * Will also forget the auth.2fa.id session key on success.
     */
    public function ensureCodeIsValid(User $user): void
    {
        $mfa = app(AppAuthentication::class);
        $code = $this->input('code', '');

        if (
            $this->isOtp
                ? $mfa->verifyCode($code, $user->app_authentication_secret)
                : $mfa->verifyRecoveryCode($code, $user)
        ) {
            $this->session()->forget(['auth.2fa.id']);

            return;
        }

        throw ValidationException::withMessages([
            'code' => __('auth.two-factor.validation.incorrect'),
        ]);
    }
}
