<?php

declare(strict_types=1);

namespace App\View\Components\AboutUs;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class BetterplaceDonationCard extends Component
{
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
        $cacheTtl = 300;

        try {
            $eventData = Cache::get('betterplace_event_'.$this->eventId);
            $donationsData = Cache::get('betterplace_donations_'.$this->eventId);

            if ($eventData === null || $donationsData === null) {
                $eventResponse = Http::get('https://api.betterplace.org/de/api_v4/fundraising_events/'.$this->eventId.'.json');
                $donationsResponse = Http::get('https://api.betterplace.org/de/api_v4/fundraising_events/'.$this->eventId.'/opinions.json');

                if ($eventResponse->successful() && $donationsResponse->successful()) {
                    $eventData = $eventResponse->json();
                    $donationsData = $donationsResponse->json();

                    Cache::put('betterplace_event_'.$this->eventId, $eventData, $cacheTtl);
                    Cache::put('betterplace_donations_'.$this->eventId, $donationsData, $cacheTtl);

                    $this->error = '';
                } else {
                    $this->error = __('betterplace.error');
                    $eventData = null;
                    $donationsData = ['data' => []];
                }
            }

            $this->eventData = $eventData;
            $this->donations = $donationsData['data'] ?? [];
        } catch (Exception) {
            $this->error = __('betterplace.error');
            $this->eventData = null;
            $this->donations = [];
        }
    }
}
