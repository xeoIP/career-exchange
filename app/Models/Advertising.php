<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;

/**
 * App\Models\Advertising
 *
 * @property int $id
 * @property string $slug
 * @property string|null $provider_name
 * @property string|null $tracking_code_large
 * @property string|null $tracking_code_medium
 * @property string|null $tracking_code_small
 * @property int|null $active
 * @property-read \App\Models\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereTrackingCodeLarge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereTrackingCodeMedium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advertising whereTrackingCodeSmall($value)
 * @mixin \Eloquent
 */
class Advertising extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advertising';
    
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
    public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'slug'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_name', 'tracking_code_large', 'tracking_code_medium', 'tracking_code_small', 'active'];
    
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
