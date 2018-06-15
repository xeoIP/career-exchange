<?php

namespace App\Models;


class Currency extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $appends = ['symbol'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    //public $timestamps = false;
    
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
        'code',
        'name',
        'html_entity',
        'font_arial',
        'font_code2000',
        'unicode_decimal',
        'unicode_hex',
        'in_left',
        'decimal_places',
        'decimal_separator',
        'thousand_separator'
    ];
    
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
    protected $dates = ['created_at', 'created_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
	public function getPositionHtml()
	{
		if ($this->in_left == 1) {
			return '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
		} else {
			return '<i class="fa fa-square-o" aria-hidden="true"></i>';
		}
	}
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_code', 'code');
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
    public function getIdAttribute($value)
    {
        return $this->attributes['code'];
    }
    
    public function getSymbolAttribute()
    {
        $symbol = $this->attributes['html_entity'];
        if (trim($symbol) == '') {
            $symbol = $this->attributes['font_arial'];
        }
        if (trim($symbol) == '') {
            $symbol = $this->attributes['font_code2000'];
        }
        if (trim($symbol) == '') {
            $symbol = $this->attributes['code'];
        }
        
        return $symbol;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setSymbolAttribute($value)
    {
        $this->attributes['font_arial'] = $value;
        $this->attributes['font_code2000'] = $value;
    }
}
