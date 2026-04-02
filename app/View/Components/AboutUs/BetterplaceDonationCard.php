<?php

declare(strict_types=1);

namespace App\View\Components\AboutUs;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;
use RuntimeException;

class BetterplaceDonationCard extends Component
{
    private const string BETTERPLACE_BASE = 'https://api.betterplace.org/de/api_v4/fundraising_events/';

    private const int HTTP_TIMEOUT = 2;

    // After how many seconds do we consider the data stale (and refresh in the background)
    private const int CACHE_TTL_STALE = 300;

    // After how many seconds do we want to force a refresh
    private const int CACHE_TTL_INVALID = 86_400;

    public ?string $error = null;

    public array $donations = [];

    public ?array $eventData = null;

    public function __construct(
        public readonly int $eventId = 55712,
    ) {}

    public function render(): View
    {
        $this->loadData();

        return view('components.about-us.betterplace-donation-card');
    }

    protected function loadData(): void
    {
        try {
            [$this->eventData, $this->donations] = Cache::flexible(
                "betterplace_donation_card_{$this->eventId}",
                [self::CACHE_TTL_STALE, self::CACHE_TTL_INVALID],
                function (): array {
                    [$eventResponse, $donationsResponse] = Http::timeout(self::HTTP_TIMEOUT)
                        ->pool(fn (Pool $pool): array => [
                            $pool->get(self::BETTERPLACE_BASE.$this->eventId.'.json'),
                            $pool->get(self::BETTERPLACE_BASE.$this->eventId.'/opinions.json'),
                        ]);

                    if (! $eventResponse->successful() || ! $donationsResponse->successful()) {
                        throw new RuntimeException('Betterplace API request failed.');
                    }

                    return [$eventResponse->json(), $donationsResponse->json()['data'] ?? []];
                }
            );
        } catch (Exception) {
            $this->error = __('betterplace.error');
        }
    }
}
