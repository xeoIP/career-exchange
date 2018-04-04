<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PositionRole
 *
 * @property int $id
 * @property string $name
 * @property int|null $position_id
 * @property-read \App\Models\Position|null $position
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PositionRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PositionRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PositionRole wherePositionId($value)
 * @mixin \Eloquent
 */
class PositionRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'position_roles';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'position_id'];

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

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function roles()
    {
        return $this->belongsToMany(User::class);
    }

}
