<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class ReviewedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
	 * @param Builder $builder
	 * @param Model $model
	 * @return $this|Builder
	 */
    public function apply(Builder $builder, Model $model)
    {
        if (Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
            return $builder;
        }

        if (config('settings.posts_review_activation')) {
            return $builder->where('reviewed', 1);
        }

        return $builder;
    }
}
