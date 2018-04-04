<?php

namespace App\Models;

use App\Models\Traits\TranslatedTrait;
use App\Models\Scopes\ActiveScope;

/**
 * App\Models\SalaryType
 *
 * @property int $id
 * @property string|null $translation_lang
 * @property int|null $translation_of
 * @property string $name
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $depth
 * @property int|null $active
 * @property-read mixed $tid
 * @property-read \App\Models\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SalaryType[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalaryType whereTranslationOf($value)
 * @mixin \Eloquent
 */
class SalaryType extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salary_types';
    
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
    protected $fillable = ['name', 'active', 'translation_lang', 'translation_of', 'lft', 'rgt', 'depth',];
    public $translatable = ['name'];
    
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
