<?php

namespace App\Models;

use App\Models\Traits\VerifiedTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Models\Crud;

class BaseUser extends Authenticatable
{
    use Crud, VerifiedTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    // ...
}
