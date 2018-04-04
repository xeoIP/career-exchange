<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;

/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $display_name
 * @property string|null $description
 * @property int|null $has_ccbox
 * @property string|null $countries Countries codes separated by comma.
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $depth
 * @property int|null $active
 * @property-read \App\Models\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereCountries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereHasCcbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentMethod whereRgt($value)
 * @mixin \Eloquent
 */
class PaymentMethod extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_methods';
    
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
    protected $fillable = ['id', 'name', 'display_name', 'description', 'has_ccbox', 'countries', 'active', 'lft', 'rgt', 'depth'];
    
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
    
    public function getCountriesHtml()
    {
        $out = strtoupper(__t('All'));
        if (isset($this->countries) && !empty($this->countries)) {
            $countriesCropped = str_limit($this->countries, 50, ' [...]');
            $out = '<div title="' . $this->countries . '">' . $countriesCropped . '</div>';
        }
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function payment()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
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
    public function getCountriesAttribute($value)
    {
        // Get a custom display value
        $value = str_replace(',', ', ', strtoupper($value));
        $value = strtoupper($value);
        
        return $value;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setCountriesAttribute($value)
    {
        // Get the MySQL right value
        $value = preg_replace('/(,|;)\s*/', ',', $value);
        $value = strtolower($value);
        
        // Check if the entry is removed
        if (empty($value) || $value == strtolower(__t('All'))) {
            $value = null;
        }
        
        $this->attributes['countries'] = $value;
        
        return $value;
    }
}
