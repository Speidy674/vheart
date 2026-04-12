<?php

declare(strict_types=1);

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterConsentLog;
use App\Models\User;

describe('Broadcaster', function () {
    describe('consent logging on model', function () {
        it('logs consent change on update if changed from own user', function () {
            $broadcaster = Broadcaster::factory()
                ->has(BroadcasterConsentLog::factory()->state(['state' => [BroadcasterConsent::Compilations]]), 'consentLogs')
                ->create(['consent' => [BroadcasterConsent::Compilations]]);

            $this->actingAs($broadcaster->user);

            $broadcaster->update(['consent' => [BroadcasterConsent::Compilations, BroadcasterConsent::Shorts]]);
            $broadcaster->refresh();

            expect($broadcaster->consentLogs()->count())->toBe(2)
                ->and($broadcaster->latestConsentLog->state)->toContain(BroadcasterConsent::Shorts);
        });

        it('does not log when consent is the same', function () {
            $broadcaster = Broadcaster::factory()
                ->has(BroadcasterConsentLog::factory()->state(['state' => [BroadcasterConsent::Compilations]]), 'consentLogs')
                ->create(['consent' => [BroadcasterConsent::Compilations]]);

            $this->actingAs($broadcaster->user);

            $broadcaster->update(['consent' => [BroadcasterConsent::Compilations]]);
            $broadcaster->refresh();

            expect($broadcaster->consentLogs()->count())->toBe(1)
                ->and($broadcaster->latestConsentLog->state)->toEqual(collect([BroadcasterConsent::Compilations]));
        });

        it('skips log when other user updates consent', function () {
            $broadcaster = Broadcaster::factory()->create();

            $this->actingAs(User::factory()->create());

            $broadcaster->update(['consent' => [BroadcasterConsent::Compilations]]);

            expect($broadcaster->consentLogs()->count())->toBe(0);
        });

        it('skips log when non-consent field changes', function () {
            $broadcaster = Broadcaster::factory()->create();

            $this->actingAs($broadcaster->user);

            $broadcaster->update(['submit_user_allowed' => true]);

            expect($broadcaster->consentLogs()->count())->toBe(0);
        });
    });
});
