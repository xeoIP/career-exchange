<?php

namespace App\Models;

use App\Models\Traits\TranslatedTrait;
use App\Models\Scopes\ActiveScope;

/**
 * App\Models\PostType
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostType[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostType whereTranslationOf($value)
 * @mixin \Eloquent
 */
class PostType extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_types';
    
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
    protected $fillable = ['name', 'active', 'translation_lang', 'translation_of'];
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
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_type_id');
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
