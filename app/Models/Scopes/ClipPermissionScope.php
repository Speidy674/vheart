<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Limit Clips to only those we have permissions for
 */
class ClipPermissionScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereHas('broadcaster', function (Builder $q) {
            $q->where('clip_permission', true);
        });
    }
}
