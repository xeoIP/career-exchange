<?php

namespace App\Models;


use App\Models\Traits\CountryTrait;

/**
 * App\Models\TimeZone
 *
 * @property int $id
 * @property string $country_code
 * @property string|null $time_zone_id
 * @property float|null $gmt
 * @property float|null $dst
 * @property float|null $raw
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\TimeZone $timeZone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone countryOf($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone currentCountry()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereDst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereGmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TimeZone whereTimeZoneId($value)
 * @mixin \Eloquent
 */
class TimeZone extends BaseModel
{
    use CountryTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'time_zones';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    public $incrementing = false;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_code', 'time_zone_id', 'gmt', 'dst', 'raw'];
    
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [];
    
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
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    /*public function getIdAttribute($value)
    {
        return $this->attributes['time_zone_id'];
    }*/
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
