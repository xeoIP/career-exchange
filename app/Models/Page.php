<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Prologue\Alerts\Facades\Alert;

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
