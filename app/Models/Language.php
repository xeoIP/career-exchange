<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Larapen\Admin\app\Models\LanguageFeatures;

/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $abbr
 * @property string|null $locale
 * @property string $name
 * @property string|null $native
 * @property string|null $flag
 * @property string $app_name
 * @property string|null $script
 * @property int|null $russian_pluralization
 * @property int $active
 * @property int $default
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereAppName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereNative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereRussianPluralization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereScript($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Language extends BaseModel
{
    use LanguageFeatures;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'abbr';
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
    protected $fillable = ['abbr', 'locale', 'name', 'native', 'flag', 'app_name', 'script', 'russian_pluralization', 'active', 'default'];
    
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
    
    public function getDefaultHtml()
    {
        return checkboxDisplay($this->default);
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
        return $this->attributes['abbr'];
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
