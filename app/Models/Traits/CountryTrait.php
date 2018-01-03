<?php

namespace App\Models\Traits;

use App\Models\Country;
use App\Models\TimeZone;

trait CountryTrait
{
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
    
    public function timeZone()
    {
        return $this->hasOne(TimeZone::class, 'country_code', 'country_code');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeCurrentCountry($builder)
    {
        return $builder->where('country_code', config('country.code'));
    }
    
    public function scopeCountryOf($builder, $countryCode)
    {
        return $builder->where('country_code', $countryCode);
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
