<?php

namespace App\Models;

use App\Models\Traits\TranslatedTrait;

/**
 * App\Models\MetaTag
 *
 * @property int $id
 * @property string $translation_lang
 * @property int $translation_of
 * @property string|null $page
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property int $active
 * @property-read mixed $tid
 * @property-read \App\Models\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MetaTag[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MetaTag whereTranslationOf($value)
 * @mixin \Eloquent
 */
class MetaTag extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meta_tags';
    
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
    protected $fillable = ['page', 'title', 'description', 'keywords', 'translation_lang', 'translation_of', 'active'];
    public $translatable = ['title', 'description', 'keywords'];
    
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
