<?php

namespace App\Models;

use App\Models\Scopes\VerifiedScope;
use App\Models\Traits\CountryTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Route;
use Jenssegers\Date\Date;

class User extends BaseUser
{
	use CountryTrait, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    protected $appends = ['created_at_ta'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    
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
        'country_code',
        'user_type_id',
        'gender_id',
        'name',
        'about',
        'phone',
        'phone_hidden',
        'email',
        'username',
        'password',
        'remember_token',
        'is_admin',
        'disable_comments',
        'receive_newsletter',
        'receive_advice',
        'ip_addr',
        'provider',
        'provider_id',
		'email_token',
		'phone_token',
		'verified_email',
		'verified_phone',
        'blocked',
        'closed',
    ];
    
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_login_at', 'deleted_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
    
        // Don't apply the ActiveScope when:
        // - User forgot its Password
        // - User changes its Email or Phone
        if (
            !str_contains(Route::currentRouteAction(), 'Auth\ForgotPasswordController') &&
            !str_contains(Route::currentRouteAction(), 'Auth\ResetPasswordController') &&
            !session()->has('emailOrPhoneChanged')
        ) {
            static::addGlobalScope(new VerifiedScope());
        }
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function routeNotificationForNexmo()
    {
        return phoneFormatInt($this->phone, $this->country_code);
    }

    public function routeNotificationForTwilio()
    {
        $phone = phoneFormatInt($this->phone, $this->country_code);
        if (!starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        return $phone;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if (Input::filled('email') || Input::filled('phone')) {
            if (Input::filled('email')) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        } else {
            if (!empty($this->email)) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        }

        try {
            $this->notify(new ResetPasswordNotification($this, $token, $field));
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    public function getNameHtml()
    {
        // Pre URL (locale)
        $preUri = '';
        if (!(config('laravellocalization.hideDefaultLocaleInURL') == true && config('app.locale') == config('applang.abbr'))) {
            $preUri = config('app.locale') . '/';
        }

        // Get user search URL
        if (config('larapen.core.multi_countries_website')) {
            $url = url($preUri . trans('routes.v-search-user', ['countryCode' => strtolower($this->country_code), 'id' => $this->id]));
        } else {
            $url = url($preUri . trans('routes.v-search-user', ['id' => $this->id])) . '/?d=' . $this->country_code;
        }

        if (isset($this->posts) and $this->posts->count() > 0) {
            return '<a href="' . $url .'" target="_blank">' . $this->name . '</a>';
        } else {
            return $this->name;
        }
    }

    public function getCountryHtml()
    {
        $iconPath = 'images/flags/16/' . strtolower($this->country_code) . '.png';
        if (file_exists(public_path($iconPath))) {
            $out = '';
            $out .= '<a href="' . url('/') . '?d=' . $this->country_code . '" target="_blank">';
            $out .= '<img src="' . url($iconPath) . getPictureVersion() . '" data-toggle="tooltip" title="' . $this->country_code . '">';
            $out .= '</a>';
            return $out;
        } else {
            return $this->country_code;
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
    
    public function messages()
    {
        return $this->hasManyThrough(Message::class, Post::class, 'user_id', 'post_id');
    }
    
    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id');
    }
    
    public function savedSearch()
    {
        return $this->hasMany(SavedSearch::class, 'user_id');
    }
    
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeVerified($builder)
    {
        $builder->where(function($query) {
            $query->where('verified_email', 1)->where('verified_phone', 1);
        });
        
        return $builder;
    }
    
    public function scopeUnverified($builder)
    {
        $builder->where(function($query) {
            $query->where('verified_email', 0)->orWhere('verified_phone', 0);
        });
        
        return $builder;
    }
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getCreatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
        //echo $value->format('l d F Y H:i:s').'<hr>'; exit();
        //echo $value->formatLocalized('%A %d %B %Y %H:%M').'<hr>'; exit(); // Multi-language

        return $value;
    }
    
    public function getUpdatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }
    
    public function getLastLoginAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }
    
    public function getDeletedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }
    
    public function getCreatedAtTaAttribute($value)
    {
        if (!isset($this->attributes['created_at']) and is_null($this->attributes['created_at'])) {
            return null;
        }

        $value = Date::parse($this->attributes['created_at']);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
        $value = $value->ago();

        return $value;
    }
    
    public function getEmailAttribute($value)
    {
        if (
            isDemoAdmin() &&
            Request::segment(2) != 'password'
        ) {
            if (\Auth::check()) {
                if (\Auth::user()->id != 1) {
                    $value = hideEmail($value);
                }
            }
            
            return $value;
        } else {
            return $value;
        }
    }
    
    public function getPhoneAttribute($value)
    {
        $countryCode = config('country.code');
        if (isset($this->country_code) && !empty($this->country_code)) {
            $countryCode = $this->country_code;
        }
        
        $value = phoneFormatInt($value, $countryCode);
        
        return $value;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
