<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;

class Package extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packages';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    protected $appends = ['tid'];
    
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
    protected $fillable = [
        'name',
        'short_name',
        'ribbon',
        'has_badge',
        'price',
        'currency_code',
        'duration',
        'description',
        'active',
        'parent_id',
        'lft',
        'rgt',
        'depth',
        'translation_lang',
        'translation_of'
    ];
    public $translatable = ['name', 'short_name', 'description'];
    
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
        
        static::addGlobalScope(new ActiveScope());
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'package_id');
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
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
