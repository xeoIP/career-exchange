<?php

namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Prologue\Alerts\Facades\Alert;

class Setting extends BaseModel
{
    protected $table    = 'settings';
    protected $guarded  = ['id'];
    protected $fillable = ['id', 'key', 'name', 'value', 'description', 'field', 'parent_id', 'lft', 'rgt', 'depth', 'active'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
    }

    public function getValueHtml()
    {
        if (str_contains($this->field, 'checkbox'))
        {
            return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'value', $this->value);
        }
        else if ($this->key == 'app_logo')
        {
            $out = '<img src="' . Storage::url($this->value) . getPictureVersion() . '" alt="' . $this->value . '" style="width:228px; height:auto;"><br>';
            $out .= '[<a href="' . url(config('larapen.admin.route_prefix', 'admin').'/setting/' . $this->id . '/edit') . '">Edit</a>]';

            return $out;
        }
        else if ($this->key == 'app_favicon')
        {
            $out = '<img src="' . Storage::url($this->value) . getPictureVersion() . '" alt="' . $this->value . '" style="width:32px; height:auto;"><br>';
            $out .= '[<a href="' . url(config('larapen.admin.route_prefix', 'admin').'/setting/' . $this->id . '/edit') . '">Edit</a>]';

            return $out;
        }
        else
        {
            $out = str_limit(htmlspecialchars($this->value, ENT_QUOTES), 50);

            // Check and Get Plugins settings vars
            $out = plugin_setting_value_html($this, $out);

            return $out;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($builder)
    {
        return $builder->where('active', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getValueAttribute($value)
    {
        if (
            isDemoAdmin() &&
            !in_array(Request::segment(2), ['password', 'login'])
        ) {
            $hiddenValues = [
                'recaptcha_public_key',
                'recaptcha_private_key',
                'mail_password',
                'mailgun_secret',
                'mandrill_secret',
                'ses_key',
                'ses_secret',
                'sparkpost_secret',
                'stripe_secret',
                'paypal_username',
                'paypal_password',
                'paypal_signature',
                'facebook_client_id',
                'facebook_client_secret',
                'google_client_id',
                'google_client_secret',
                'google_maps_key',
                'twitter_client_id',
                'twitter_client_secret',
            ];
            
            if (in_array($this->attributes['key'], $hiddenValues)) {
                $value = '************************';
            }
        }

        if ($this->key == 'app_logo')
        {
            $value = str_replace('uploads/', '', $value);
            if (!Storage::exists($value)) {
                $value = config('larapen.core.logo');
            }
        }

        if ($this->key == 'app_favicon')
        {
            if (!Storage::exists($value)) {
                $value = config('larapen.core.favicon');
            }
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    // Set Value
    public function setValueAttribute($value)
    {
        // Set logo
        if ($this->attributes['key'] == 'app_logo')
        {
            return $this->setLogo($value);
        }
        else if ($this->attributes['key'] == 'app_favicon')
        {
            return $this->setFavicon($value);
        }
        else if ($this->attributes['key'] == 'posts_review_activation')
        {
            // If ads review activation enable, then update all current ads
            if ((int)$value == 1) {
                Post::where('reviewed', '!=', 1)->update(['reviewed' => 1]);
            }
            $this->attributes['value'] = $value;
        }
        else
        {
            $this->attributes['value'] = $value;

            // Check and Get Plugins settings vars
            $this->attributes['value'] = plugin_set_setting_value($value, $this);
        }
    }

    // Set Logo
    private function setLogo($value)
    {
        $attribute_name = 'value';
        $destination_path = 'app/logo';

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            if (!str_contains($this->value, config('larapen.core.logo'))) {
                Storage::delete($this->value);
            }

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            try {
                // Get file extention
                $extension = (is_png($value)) ? 'png' : 'jpg';

                // Make the image (Size: 454x80)
                $image = Image::make($value)->resize(454, 80, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($extension, 100);
            } catch (\Exception $e) {
                Alert::error($e->getMessage())->flash();
                $this->attributes[$attribute_name] = null;

                return false;
            }

            // Generate a filename.
            $filename = uniqid('logo-') . '.' . $extension;

            // Store the image on disk.
            Storage::put($destination_path . '/' . $filename, $image->stream());

            // Save the path to the database
            $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
        }
    }

    // Set Favicon
    private function setFavicon($value)
    {
        $attribute_name = 'value';
        $destination_path = 'app/ico';

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            if (!str_contains($this->value, config('larapen.core.favicon'))) {
                Storage::delete($this->value);
            }

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            try {
                // Get file extention
                $extension = (is_png($value)) ? 'png' : 'jpg';

                // Make the image (Size: 32x32)
                $image = Image::make($value)->resize(32, 32, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($extension, 100);
            } catch (\Exception $e) {
                Alert::error($e->getMessage())->flash();
                $this->attributes[$attribute_name] = null;

                return false;
            }

            // Save the file on server
            $filename = uniqid('ico-') . '.' . $extension;
            Storage::put($destination_path . '/' . $filename, $image->stream());

            // Save the path to the database
            $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
        }
    }
}
