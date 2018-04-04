<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $code
 * @property string|null $iso3
 * @property int|null $iso_numeric
 * @property string|null $fips
 * @property string|null $name
 * @property string|null $asciiname
 * @property string|null $capital
 * @property int|null $area
 * @property int|null $population
 * @property string|null $continent_code
 * @property string|null $tld
 * @property string|null $currency_code
 * @property string|null $phone
 * @property string|null $postal_code_format
 * @property string|null $postal_code_regex
 * @property string|null $languages
 * @property string|null $neighbours
 * @property string|null $equivalent_fips_code
 * @property string $admin_type
 * @property int|null $admin_field_active
 * @property int|null $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Continent|null $continent
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $icode
 * @property-read \App\Models\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereAdminFieldActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereAdminType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereAsciiname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereContinentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereEquivalentFipsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereFips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereIsoNumeric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereNeighbours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country wherePostalCodeFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country wherePostalCodeRegex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereTld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Country extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $appends = ['icode'];
    protected $visible = ['code', 'name', 'asciiname', 'icode', 'currency_code', 'phone', 'languages', 'currency', 'admin_type', 'admin_field_active'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;
    
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
    protected $fillable = [
        'code',
        'name',
        'asciiname',
        'capital',
        'continent_code',
        'tld',
        'currency_code',
        'phone',
        'languages',
        'admin_type',
        'admin_field_active',
        'active'
    ];
    
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
    protected $dates = ['created_at', 'created_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope(new ActiveScope());
    }
    
    public function getNameHtml()
    {
        $out = '';
        
        $editUrl = url(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->id . '/edit');
        $admin1Url = url(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->id . '/loc_admin1');
        $cityUrl = url(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->id . '/city');
        
        $out .= '<a href="' . $editUrl . '" style="float:left;">' . $this->asciiname . '</a>';
        $out .= ' ';
        $out .= '<span style="float:right;">';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $admin1Url . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('admin. divisions 1')) . '</a>';
        $out .= ' ';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $cityUrl . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('cities')) . '</a>';
        $out .= '</span>';
        
        return $out;
    }

	public function getActiveHtml()
	{
		if (!isset($this->active)) return false;

        return installAjaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'active', $this->active);
	}
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
    public function continent()
    {
        return $this->belongsTo(Continent::class, 'continent_code', 'code');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        if (Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
            return $query;
        }
        
        return $query->where('active', 1);
    }
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getIcodeAttribute()
    {
        return strtolower($this->attributes['code']);
    }
    
    public function getIdAttribute($value)
    {
        return $this->attributes['code'];
    }

    public function getAllCountries()
    {
        return $this::withoutGlobalScopes()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
