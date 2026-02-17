<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Mockery\MockInterface;

beforeEach(function () {
    $this->mock(AppAuthentication::class, function (MockInterface $mock) {
        $mock->shouldReceive('isEnabled')->byDefault()->andReturn(false);
        $mock->shouldReceive('verifyCode')->byDefault()->andReturn(false);
        $mock->shouldReceive('verifyRecoveryCode')->byDefault()->andReturn(false);
        $mock->shouldReceive('generateSecretKey')->byDefault()->andReturn('mock-secret-key');
        $mock->shouldReceive('generateRecoveryCode')->byDefault()->andReturn(['mock-recovery-code1', 'mock-recovery-code2']);
    });
});

describe('Twitch Callback', function () {

    it('redirects to 2fa challenge if user has mfa enabled', function () {
        $user = User::factory()->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->with(Mockery::on(static fn (User $u) => $u->id === $user->id))
            ->andReturn(true);

        mockTwitchUser($user);

        $this->get(route('auth.callback'))
            ->assertRedirect(route('auth.challenge'));

        expect(session('auth.2fa.id'))->toBe($user->id)
            ->and(auth()->check())->toBeFalse(); // User should NOT be logged in yet
    });

    it('logs in immediately if user has mfa disabled', function () {
        $user = User::factory()->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->andReturn(false);

        mockTwitchUser($user);

        $this->get(route('auth.callback'))
            ->assertRedirect(route('dashboard'));

        expect(auth()->user()->id)->toBe($user->id)
            ->and(session('auth.2fa.id'))->toBeNull();
    });
});

describe('2FA Challenge', function () {

    it('blocks access if session is missing', function () {
        $this->get(route('auth.challenge'))
            ->assertRedirect(route('login'));
    });

    it('renders challenge page if session is valid', function () {
        $user = User::factory()->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->with(Mockery::on(static fn (User $u) => $u->id === $user->id))
            ->andReturn(true);

        $this->withSession(['auth.2fa.id' => $user->id])
            ->get(route('auth.challenge'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('auth/challenge'));
    });
});

describe('2FA Submission', function () {

    it('authenticates with valid TOTP code', function () {
        $user = User::factory()->withTwoFactor('valid-secret')->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->andReturn(true)
            ->shouldReceive('verifyCode')->with('123456', 'valid-secret')->andReturn(true);

        $this->withSession(['auth.2fa.id' => $user->id])
            ->post(route('auth.challenge.submit'), ['code' => '123456'])
            ->assertRedirect(route('home'));

        expect(auth()->user()->id)->toBe($user->id)
            ->and(session('auth.2fa.id'))->toBeNull();
    });

    it('authenticates with valid recovery code', function () {
        $user = User::factory()->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->andReturn(true)
            ->shouldReceive('verifyCode')->andReturn(false)
            ->shouldReceive('verifyRecoveryCode')
            ->with('recovery-key', Mockery::on(static fn (User $u) => $u->id === $user->id))
            ->andReturn(true);

        $this->withSession(['auth.2fa.id' => $user->id])
            ->post(route('auth.challenge.submit'), ['recovery_code' => 'recovery-key'])
            ->assertRedirect(route('home'));

        expect(auth()->user()->id)->toBe($user->id);
    });

    it('denies access with invalid codes', function () {
        $user = User::factory()->create();

        $this->mock(AppAuthentication::class)
            ->shouldReceive('isEnabled')->andReturn(true)
            ->shouldReceive('verifyCode')->andReturn(false)
            ->shouldReceive('verifyRecoveryCode')->andReturn(false);

        $this->withSession(['auth.2fa.id' => $user->id])
            ->post(route('auth.challenge.submit'), [
                'code' => '000000',
                'recovery_code' => 'wrong-recovery',
            ])
            ->assertSessionHasErrors(['code', 'recovery_code']);

        expect(auth()->check())->toBeFalse();
    });

    it('redirects to login if user identifier is missing from session on challenge view', function () {
        $this->get(route('auth.challenge'))
            ->assertRedirect(route('login'));
    });

    it('denies with access forbidden if user identifier is missing from session on challenge submit', function () {
        $this->post(route('auth.challenge.submit'), ['code' => '123456'])
            ->assertForbidden();
    });
});
