<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;

/**
 * App\Models\Package
 *
 * @property int $id
 * @property string|null $translation_lang
 * @property int|null $translation_of
 * @property string|null $name In country language
 * @property string|null $short_name In country language
 * @property string|null $ribbon
 * @property int|null $has_badge
 * @property float|null $price
 * @property string|null $currency_code
 * @property int|null $duration In days
 * @property string|null $description In country language
 * @property int|null $parent_id
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $depth
 * @property int|null $active
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $tid
 * @property-read \App\Models\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Package[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereHasBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereRibbon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Package whereTranslationOf($value)
 * @mixin \Eloquent
 */
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
