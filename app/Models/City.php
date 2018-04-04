<?php

namespace App\Models;

use App\Helpers\Geo;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\CountryTrait;

/**
 * App\Models\City
 *
 * @property int $id
 * @property string $country_code ISO-3166 2-letter country code, 2 characters
 * @property string $name name of geographical point (utf8) varchar(200)
 * @property string|null $asciiname name of geographical point in plain ascii characters, varchar(200)
 * @property float|null $latitude latitude in decimal degrees (wgs84)
 * @property float|null $longitude longitude in decimal degrees (wgs84)
 * @property string|null $feature_class see http://www.geonames.org/export/codes.html, char(1)
 * @property string|null $feature_code see http://www.geonames.org/export/codes.html, varchar(10)
 * @property string|null $subadmin1_code fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)
 * @property string|null $subadmin2_code code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80)
 * @property int|null $population bigint (4 byte int)
 * @property string|null $time_zone the timezone id (see file timeZone.txt)
 * @property int|null $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read \App\Models\TimeZone $timeZone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City countryOf($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City currentCountry()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereAsciiname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereFeatureClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereFeatureCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereSubadmin1Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereSubadmin2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class City extends BaseModel
{
    use CountryTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    // protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'country_code',
        'name',
        'asciiname',
        'latitude',
        'longitude',
        'subadmin1_code',
        'subadmin2_code',
        'population',
        'time_zone',
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
    protected $dates = ['created_at', 'updated_at'];
    
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
    
    public function getAdmin1Html()
    {
        $out = $this->subadmin1_code;
        
        $admin = $this->subAdmin1();
        if (!empty($admin)) {
            $out = $admin->name;
        }
        
        return $out;
    }
    
    public function getAdmin2Html()
    {
        $out = $this->subadmin2_code;
        
        $admin = $this->subAdmin2();
        if (!empty($admin)) {
            $out = $admin->name;
        }
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(Post::class, 'city_id');
    }
    
    public function timeZone()
    {
        return $this->hasOne(TimeZone::class, 'country_code', 'country_code');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    /*
    public function subAdmin1()
    {
        return $this->belongsTo(SubAdmin1::class, 'subadmin1_code', 'code');
    }
    
    public function subAdmin2()
    {
        return $this->belongsTo(SubAdmin2::class, 'subadmin2_code', 'code');
    }
    */
    
    // Specials
    public function subAdmin1()
    {
        return SubAdmin1::where('code', $this->subadmin1_code)->first();
    }
    
    public function subAdmin2()
    {
        return SubAdmin2::where('code', $this->subadmin2_code)->first();
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
    public function getAsciinameAttribute($value)
    {
        return preg_replace(array('#\s\s+#', '#\' #'), array(' ', "'"), $value);
    }
    
    public function getNameAttribute($value)
    {
        //return Geo::getShortName($value);
        return $value;
    }
    
    public function getLatitudeAttribute($value)
    {
        return fixFloatVar($value);
    }
    
    public function getLongitudeAttribute($value)
    {
        return fixFloatVar($value);
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
