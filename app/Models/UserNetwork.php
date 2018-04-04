<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserNetwork
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $linkedIn
 * @property string|null $github
 * @property string|null $stackOverflow
 * @property string|null $website
 * @property string|null $resume
 * @property string|null $twitter
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereGithub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereLinkedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereStackOverflow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserNetwork whereWebsite($value)
 * @mixin \Eloquent
 */
class UserNetwork extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_networks';

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
    protected $fillable = ['user_id', 'linkedIn', 'github', 'stackOverflow', 'website', 'resume', 'twitter'];

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
