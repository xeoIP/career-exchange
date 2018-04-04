<?php

namespace App\Models;

use App\Helpers\Geo;
use App\Models\Traits\CountryTrait;

/**
 * App\Models\SubAdmin2
 *
 * @property int $id
 * @property string $code
 * @property string|null $country_code
 * @property string|null $subadmin1_code
 * @property string|null $name
 * @property string|null $asciiname
 * @property int|null $active
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\SubAdmin1|null $subAdmin1
 * @property-read \App\Models\TimeZone $timeZone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 countryOf($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 currentCountry()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereAsciiname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubAdmin2 whereSubadmin1Code($value)
 * @mixin \Eloquent
 */
class SubAdmin2 extends BaseModel
{
    use CountryTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subadmin2';
    
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
    protected $fillable = ['country_code', 'subadmin1_code', 'code', 'name', 'asciiname', 'active'];
    
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
        $cityUrl = url(config('larapen.admin.route_prefix', 'admin') . '/loc_admin2/' . $this->code . '/city');
        
        $out .= '<a href="' . $editUrl . '" style="float:left;">' . $this->asciiname . '</a>';
        $out .= ' ';
        $out .= '<span style="float:right;">';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $cityUrl . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('cities')) . '</a>';
        $out .= '</span>';
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function subAdmin1()
    {
        return $this->belongsTo(SubAdmin1::class, 'subadmin1_code', 'code');
    }
    
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
