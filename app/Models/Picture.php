<?php

namespace App\Models;

use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * App\Models\Picture
 *
 * @property int $id
 * @property int|null $post_id
 * @property string|null $filename
 * @property int|null $active Set at 0 on updating the ad
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\Post|null $post
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Picture whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Picture extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pictures';

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
    protected $fillable = ['post_id', 'filename', 'active'];

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

    public function getPostTitleHtml()
    {
        if ($this->post) {
            return '<a href="/' . config('app.locale') . '/' . slugify($this->post->title) . '/' . $this->post->id . '.html" target="_blank">' . $this->post->title . '</a>';
        } else {
            return 'no-link';
        }
    }

    public function getFilenameHtml()
    {
        // Get picture
        $out = '<img src="' . resize($this->filename, 'small') . '" style="width:auto; height:90px;">';

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
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
    public function getFilenameFromOldPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];

        // Fix path
        $value = str_replace('uploads/pictures/', '', $value);
        $value = str_replace('pictures/', '', $value);
        $value = 'pictures/' . $value;

        if (!Storage::exists($value)) {
            $value = null;
        }

        return $value;
    }

    public function getFilenameAttribute()
    {
        // OLD PATH
        $value = $this->getFilenameFromOldPath();
        if (!empty($value)) {
            return $value;
        }

        // NEW PATH
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];

        if (!Storage::exists($value)) {
            $value = config('larapen.core.picture.default');
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFilenameAttribute($value)
    {
        $attribute_name = 'filename';

        if (empty($this->post)) {
            $this->attributes[$attribute_name] = null;
        }

        // Get ad details
        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $this->post_id)->first();
        if (empty($post)) {
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // Path
        $destination_path = 'files/' . strtolower($post->country_code) . '/' . $post->id;

        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            if (!str_contains($this->filename, config('larapen.core.picture.default'))) {
                Storage::delete($this->filename);
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
        try {
            // Get file extention
            if (!is_string($value)) {
                $extension = $value->getClientOriginalExtension();
            } else {
                $matches = [];
                preg_match('#data:image/([^;]+);base64#', $value, $matches);
                $extension = (isset($matches[1]) && !empty($matches[1])) ? $matches[1] : 'png';
            }

            // Image default sizes
            $width = (int)config('larapen.core.picture.size.width', 1000);
            $height = (int)config('larapen.core.picture.size.height', 1000);

            // Make the image
            $image = Image::make($value)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($extension, config('larapen.core.picture.quality', 100));

            // Check and load Watermark plugin
            $plugin = load_installed_plugin('watermark');
            if (!empty($plugin)) {
                $image = call_user_func($plugin->class . '::apply', $image);
                if (empty($image)) {
                    $this->attributes[$attribute_name] = null;

                    return false;
                }
            }

        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // Generate a filename.
        $filename = md5($value . time()) . '.' . $extension;

        // Store the image on disk.
        Storage::put($destination_path . '/' . $filename, $image->stream());

        // Save the path to the database
        $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
    }
}
