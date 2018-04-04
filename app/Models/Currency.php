<?php

namespace App\Models;


/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @property string|null $html_entity From Github : An array of currency symbols as HTML entities
 * @property string|null $font_arial
 * @property string|null $font_code2000
 * @property string|null $unicode_decimal
 * @property string|null $unicode_hex
 * @property int|null $in_left
 * @property int|null $decimal_places Currency Decimal Places - ISO 4217
 * @property string|null $decimal_separator
 * @property string|null $thousand_separator
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @property mixed $symbol
 * @property-read \App\Models\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereDecimalPlaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereFontArial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereFontCode2000($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereHtmlEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereInLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUnicodeDecimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUnicodeHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
