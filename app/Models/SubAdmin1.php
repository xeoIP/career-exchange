<?php

namespace App\Models;

use App\Helpers\Geo;
use App\Models\Traits\CountryTrait;

/**
 * App\Models\SubAdmin1
 *
 * @property int $id
 * @property string $code
 * @property string|null $country_code
 * @property string $name
 * @property string|null $asciiname
 * @property int|null $active
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\TimeZone $timeZone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 countryOf($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 currentCountry()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereAsciiname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin1 whereName($value)
 * @mixin \Eloquent
 */
class SubAdmin1 extends BaseModel
{
    use CountryTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subadmin1';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';
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
    protected $fillable = ['country_code', 'code', 'name', 'asciiname', 'active'];
    
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
    protected static function boot()
    {
        parent::boot();
    }
    
    public function getNameHtml()
    {
        $out = '';
        
        $currentUrl = preg_replace('#/(search)$#', '', url()->current());
        $editUrl = $currentUrl . '/' . $this->code . '/edit';
        $admin2Url = url(config('larapen.admin.route_prefix', 'admin') . '/loc_admin1/' . $this->code . '/loc_admin2');
        $cityUrl = url(config('larapen.admin.route_prefix', 'admin') . '/loc_admin1/' . $this->code . '/city');
        
        $out .= '<a href="' . $editUrl . '" style="float:left;">' . $this->asciiname . '</a>';
        $out .= ' ';
        $out .= '<span style="float:right;">';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $admin2Url . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('admin. divisions 2')) . '</a>';
        $out .= ' ';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $cityUrl . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('cities')) . '</a>';
        $out .= '</span>';
        
        return $out;
    }
    
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
    public function getIdAttribute($value)
    {
        return $this->attributes['code'];
    }
    
    public function getNameAttribute($value)
    {
        return Geo::getShortName($value);
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
