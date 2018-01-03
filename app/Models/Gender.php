<?php

namespace App\Models;

use App\Models\Traits\TranslatedTrait;

class Gender extends BaseModel
{
    use TranslatedTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gender';
    
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
    protected $fillable = ['name', 'translation_lang', 'translation_of'];
    public $translatable = ['name'];
    
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
