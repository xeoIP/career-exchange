<?php

namespace App\Models;


use App\Models\Traits\CountryTrait;

/**
 * App\Models\SavedSearch
 *
 * @property int $id
 * @property string|null $country_code
 * @property int|null $user_id
 * @property string|null $keyword To show
 * @property string|null $query
 * @property int|null $count
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\TimeZone $timeZone
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch countryOf($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch currentCountry()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavedSearch whereUserId($value)
 * @mixin \Eloquent
 */
class SavedSearch extends BaseModel
{
    use CountryTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'saved_search';
    
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
    protected $fillable = ['country_code', 'user_id', 'keyword', 'query', 'count'];
    
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
