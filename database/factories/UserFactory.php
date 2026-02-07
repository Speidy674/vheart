<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $id = fake()->unique()->numberBetween();

        return [
            'id' => $id,
            'name' => fake()->name(),
            'email' => null,
            'clip_permission' => false,
            'avatar_url' => 'https://api.dicebear.com/9.x/pixel-art/svg?seed='.$id,
            'email_verified_at' => null,
            'app_authentication_secret' => null,
            'app_authentication_recovery_codes' => null,
        ];
    }

    public function withTwoFactor(?string $secret = null): self
    {
        return $this->state(function (array $attributes) use ($secret): array {
            return [
                'app_authentication_secret' => $secret ?? app(AppAuthentication::class)->generateSecretKey(),
                'app_authentication_recovery_codes' => [],
            ];
        });
    }

    public function withTwoFactorRecoveryCodes(?array $recoveryCodes = null): self
    {
        return $this->state(function (array $attributes) use ($recoveryCodes): array {
            return [
                'app_authentication_recovery_codes' => $recoveryCodes ?? app(AppAuthentication::class)->generateRecoveryCodes(),
            ];
        });
    }

    public function withVerifiedEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => fake()->safeEmail(),
            'email_verified_at' => fake()->dateTime(),
        ]);
    }

    public function withUnverifiedEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => fake()->safeEmail(),
            'email_verified_at' => null,
        ]);
    }
}
