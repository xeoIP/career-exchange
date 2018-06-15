<?php

namespace App\Models;

use App\Helpers\Geo;
use App\Models\Scopes\ActiveScope;
use App\Models\Traits\CountryTrait;

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
