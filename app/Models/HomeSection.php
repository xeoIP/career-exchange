<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;

class HomeSection extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_sections';
    
    protected $fakeColumns = ['options'];
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
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
    protected $fillable = ['name', 'method', 'options', 'parent_id', 'lft', 'rgt', 'depth', 'active'];
    
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
    
    protected $casts = [
        'options' => 'array',
    ];
    
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
    
    public function getNameHtml()
    {
        $out = '';
        
        $url = url(config('larapen.admin.route_prefix', 'admin') . '/home_section/' . $this->id . '/edit');
        $out .= '<a href="' . $url . '">' . $this->name . '</a>';
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
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
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
