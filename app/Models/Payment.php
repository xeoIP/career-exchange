<?php

namespace App\Models;


class Payment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';
    
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
    protected $fillable = ['post_id', 'package_id', 'payment_method_id', 'transaction_id'];
    
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
    public function getPostTitleHtml()
    {
        if ($this->post) {
            return '<a href="/' . config('app.locale') . '/' . slugify($this->post->title) . '/' . $this->post_id . '.html" target="_blank">' . $this->post->title . '</a>';
        } else {
            return $this->post_id;
        }
    }
    
    public function getPackageNameHtml()
    {
        $package = Package::transById($this->package_id);
        if (!empty($package)) {
            return $package->name . ' (' . $package->price . ' ' . $package->currency_code . ')';
        } else {
            return $this->package_id;
        }
    }

    public function getPaymentMethodNameHtml()
    {
        $paymentMethod = PaymentMethod::find($this->payment_method_id);
        if (!empty($paymentMethod)) {
            return $paymentMethod->display_name;
        } else {
            return '--';
        }
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
    
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
