<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Authenticated User Data
 *
 * @mixin User
 */
class AuthenticatedUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'avatar' => $this->proxiedContentUrl(),
            'clip_permission' => $this->clip_permission,
            'rules' => $this->rules,
            'has_email_authentication' => $this->has_email_authentication,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
