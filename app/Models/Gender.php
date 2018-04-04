<?php

namespace App\Models;

use App\Models\Traits\TranslatedTrait;

/**
 * App\Models\Gender
 *
 * @property int $id
 * @property string|null $translation_lang
 * @property int|null $translation_of
 * @property string|null $name
 * @property-read mixed $tid
 * @property-read \App\Models\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gender[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gender whereTranslationOf($value)
 * @mixin \Eloquent
 */
class Gender extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gender';
    
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
    protected $fillable = ['name', 'translation_lang', 'translation_of'];
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
