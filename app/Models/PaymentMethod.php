<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;

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
