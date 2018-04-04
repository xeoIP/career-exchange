<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserExperience
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $company
 * @property string|null $title
 * @property \Carbon\Carbon|null $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserExperience whereUserId($value)
 * @mixin \Eloquent
 */
class UserExperience extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_experiences';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'company', 'title', 'current' ,'start_date', 'end_date'];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
