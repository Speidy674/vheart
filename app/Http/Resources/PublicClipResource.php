<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\ExternalContentProxyType;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Clip
 */
class PublicClipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->twitch_id,
            'title' => $this->title,
            'thumbnail_url' => ExternalContentProxyType::toProxyUrl($this->getModel()),
            'clip_url' => $this->url,

            'broadcaster' => $this->whenLoaded('broadcaster', [
                'id' => $this->broadcaster_id,
                'name' => $this->broadcaster->name,
                'avatar' => ExternalContentProxyType::toProxyUrl($this->broadcaster),
            ]),

            'clipper' => $this->whenHas('creator', [
                'id' => $this->creator_id,
                'name' => $this->creator?->name,
                'avatar' => ExternalContentProxyType::toProxyUrl($this->creator),
            ]),

            'submitter' => $this->whenHas('submitter', [
                'id' => $this->submitter_id,
                'name' => $this->submitter?->name,
                'avatar' => ExternalContentProxyType::toProxyUrl($this->submitter),
            ]),

            'game' => $this->whenLoaded('game', $this->game->toResource()),

            'vod' => $this->when($this->vod_id, [
                'id' => $this->vod_id,
                'offset' => $this->vod_offset,
            ]),
            'votes' => $this->whenCounted('votes', default: 0),
            'clip_duration' => $this->duration,
            'clipped_at' => $this->date,
            'submitted_at' => $this->created_at,
        ];
    }
}
