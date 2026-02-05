<?php

namespace App\Http\Resources;

use App\Models\FaQ\FaqEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FaqEntry
 */
class FaqEntryResource extends JsonResource
{
    public static $wrap = null;
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'order' => $this->order ?? 0,
        ];
    }
}
