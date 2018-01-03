<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Prologue\Alerts\Facades\Alert;

class Category extends BaseModel
{
    use Sluggable, SluggableScopeHelpers, TranslatedTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    
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
        'parent_id',
        'name',
        'slug',
        'description',
        'picture',
        'css_class',
        'active',
        'lft',
        'rgt',
        'depth',
        'translation_lang',
        'translation_of',
        'type'
    ];
    public $translatable = ['name', 'slug', 'description'];
    
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
        $out = '';
    
        $currentUrl = preg_replace('#/(search)$#', '', url()->current());
        $editUrl = $currentUrl . '/' . $this->id . '/edit';
        $subCatUrl = url(config('larapen.admin.route_prefix', 'admin') . '/category/' . $this->id . '/sub_category');
        
        $out .= '<a href="' . $editUrl . '" style="float:left;">' . $this->name . '</a>';
        $out .= ' ';
        $out .= '<span style="float:right;">';
        $out .= '<a class="btn btn-xs btn-primary" href="' . $subCatUrl . '"><i class="fa fa-folder"></i> ' . ucfirst(__t('subcategories')) . '</a>';
        $out .= '</span>';
    
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasManyThrough(Post::class, Category::class, 'parent_id', 'category_id');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    public function lang()
    {
        return $this->hasOne(Category::class, 'translation_of', 'abbr');
    }
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
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
    // The slug is created automatically from the "title" field if no slug exists.
    public function getSlugOrNameAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }
        return $this->name;
    }

    /**
     * Category icons pictures from original version
     * Only the file name is set in Category 'picture' field
     * Example: fa-car.png
     *
     * @return null|string
     */
    public function getPictureFromOriginPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['picture'])) {
            return null;
        }

        $value = $this->attributes['picture'];
        if (empty($value)) {
            return null;
        }

        // Fix path
        $value = 'app/categories/' . config('app.skin', 'skin-default') . '/' . $value;

        if (!Storage::exists($value)) {
            $value = null;
        }

        return $value;
    }

    public function getPictureAttribute()
    {
        // OLD PATH
        $value = $this->getPictureFromOriginPath();
        if (!empty($value)) {
            return $value;
        }

        // NEW PATH
        if (!isset($this->attributes) || !isset($this->attributes['picture'])) {
            $value = 'app/default/categories/fa-folder-' . config('app.skin', 'skin-default') . '.png';
            return $value;
        }

        $value = $this->attributes['picture'];

        if (!Storage::exists($value)) {
            $value = 'app/default/categories/fa-folder-' . config('app.skin', 'skin-default') . '.png';
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
        $destination_path = 'app/categories/custom';

        // If the image was erased
        if (empty($value)) {
            // Don't delete the default pictures
            $defaultPicture = 'app/default/categories/fa-folder-' . config('settings.app_skin', 'skin-default') . '.png';
            if (!str_contains($this->picture, $defaultPicture)) {
                // delete the image from disk
                Storage::delete($this->picture);
            }

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
                $image = Image::make($value)->resize(400, 400, function($constraint) {
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

    /**
     * Activate/Deactivate categories with their children if exist
     * Activate/Deactivate translated entries with their translations if exist
     * @param $value
     */
    public function setActiveAttribute($value)
    {
        $entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : null;

        if (!empty($entityId)) {
            // Activate the entry
            $this->attributes['active'] = $value;

            // If the entry is a parent entry, activate its children
            $parentId = (isset($this->attributes['parent_id'])) ? $this->attributes['parent_id'] : null;
            if ($parentId == 0) {
                // ... AND don't select the current parent entry to prevent infinite recursion
                $entries = $this->where('parent_id', $entityId)->get();
                if (!empty($entries)) {
                    foreach ($entries as $entry) {
                        $entry->active = $value;
                        $entry->save();
                    }
                }
            }
        } else {
            // Activate the new entries
            $this->attributes['active'] = $value;
        }
    }
}
