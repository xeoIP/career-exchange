<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserEducation
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $university
 * @property string|null $degree
 * @property int|null $degree_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereDegreeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereUniversity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEducation whereUserId($value)
 * @mixin \Eloquent
 */
class UserEducation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_educations';

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
    protected $fillable = ['user_id', 'university', 'degree', 'degree_date'];

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
        'updated_at'
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
