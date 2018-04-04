<?php

namespace App\Models;

use App\Models\Traits\ActiveTrait;
use App\Models\Traits\VerifiedTrait;
use Illuminate\Database\Eloquent\Model;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Models\Crud;

/**
 * App\Models\BaseModel
 *
 * @property-read \App\Models\Language $language
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    use Crud, ActiveTrait, VerifiedTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    // ...
}
