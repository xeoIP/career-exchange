<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserSkill
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int|null $experience
 * @property int $is_additional
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereIsAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserSkill whereUserId($value)
 * @mixin \Eloquent
 */
class UserSkill extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_skills';

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
    protected $fillable = ['name', 'user_id', 'is_additional', 'experience'];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

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
