<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class VerifiedScope implements Scope
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
	
		return $builder->where('verified_email', 1)->where('verified_phone', 1);
    }
}
