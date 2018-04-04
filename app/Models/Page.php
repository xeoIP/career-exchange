<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Prologue\Alerts\Facades\Alert;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $translation_lang
 * @property int|null $translation_of
 * @property int|null $parent_id
 * @property string $type
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $title
 * @property string|null $picture
 * @property string|null $content
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $depth
 * @property string|null $name_color
 * @property string|null $title_color
 * @property int $excluded_from_footer
 * @property int|null $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $slug_or_name
 * @property-read mixed $tid
 * @property-read \App\Models\Language|null $language
 * @property-read \App\Models\Page|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Page[] $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page findSimilarSlugs(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page trans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page transIn($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page type($type)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereExcludedFromFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereNameColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereTitleColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereTranslationLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereTranslationOf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends BaseModel
{
    use Sluggable, SluggableScopeHelpers, TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

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
    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'slug',
        'picture',
        'title',
        'content',
        'name_color',
        'title_color',
        'excluded_from_footer',
        'active',
        'lft',
        'rgt',
        'depth',
        'translation_lang',
        'translation_of'
    ];
    public $translatable = ['name', 'slug', 'title', 'content'];

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

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_name',
            ],
        ];
    }

    public function getNameHtml()
    {
        // Pre URL (locale)
        $preUri = '';
        if (!(config('laravellocalization.hideDefaultLocaleInURL') == true && config('app.locale') == config('applang.abbr'))) {
            $preUri = config('app.locale') . '/';
        }

        return '<a href="' . url($preUri . trans('routes.v-page', ['slug' => $this->slug])) . '" target="_blank">' . $this->name . '</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeType($builder, $type)
    {
        return $builder->where('type', $type)->orderBy('id', 'DESC');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    // The slug is created automatically from the "name" field if no slug exists.
    public function getSlugOrNameAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }
        return $this->name;
    }

    public function getPictureAttribute()
    {
        if (!isset($this->attributes) || !isset($this->attributes['picture'])) {
            return null;
        }

        $value = $this->attributes['picture'];

        if (!Storage::exists($value)) {
            $value = null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setPictureAttribute($value)
    {
        $attribute_name = 'picture';
        $destination_path = 'app/page';

        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            Storage::delete($this->picture);

            // set null in the database column
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // Check the image file
        if ($value == url('/')) {
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // If laravel request->file('filename') resource OR base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            try {
                // Make the image
                $image = Image::make($value)->resize(1280, 1280, function($constraint) {
                    $constraint->aspectRatio();
                });
            } catch (\Exception $e) {
                Alert::error($e->getMessage())->flash();
                $this->attributes[$attribute_name] = null;

                return false;
            }

            // Generate a filename.
            $filename = md5($value . time()) . '.jpg';

            // Store the image on disk.
            Storage::put($destination_path . '/' . $filename, $image->stream());

            // Save the path to the database
            $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
        }
    }
}
