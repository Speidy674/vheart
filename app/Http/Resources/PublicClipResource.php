<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'thumbnail_url' => $this->thumbnail_url,
            'clip_url' => $this->url,

            'broadcaster' => $this->whenLoaded('broadcaster', [
                'id' => $this->broadcaster_id,
                'name' => $this->broadcaster->name,
                'avatar' => $this->broadcaster->avatar_url,
            ]),

            'clipper' => $this->whenHas('creator', [
                'id' => $this->creator_id,
                'name' => $this->creator?->name,
                'avatar' => $this->creator?->avatar_url,
            ]),

            'submitter' => $this->whenHas('submitter', [
                'id' => $this->submitter_id,
                'name' => $this->submitter?->name,
                'avatar' => $this->submitter?->avatar_url,
            ]),

            'game' => $this->whenLoaded('game', [
                'id' => $this->game_id,
                'title' => $this->game->title,
                'box_art' => $this->game->getBoxArt(),
            ]),

            'vod' => [
                'id' => $this->vod_id,
                'offset' => $this->vod_offset,
            ],
            'votes' => $this->whenCounted('votes', $this->votes_count),
            'clip_duration' => $this->duration,
            'clipped_at' => $this->date,
            'submitted_at' => $this->created_at,
        ];
    }
}
