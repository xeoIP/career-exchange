<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPreference
 *
 * @property int $id
 * @property int $user_id
 * @property string $employment_type
 * @property int $work_authorization
 * @property int $usdod
 * @property int $require_sponsorship
 * @property string $additional_info
 * @property float|null $current_base_salary
 * @property float|null $current_contract_rate
 * @property float|null $target_base_salary
 * @property float|null $target_contract_rate
 * @property int $searching_status
 * @property \Carbon\Carbon $date_available
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereAdditionalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereCurrentBaseSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereCurrentContractRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereDateAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereEmploymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereRequireSponsorship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereSearchingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereTargetBaseSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereTargetContractRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereUsdod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPreference whereWorkAuthorization($value)
 * @mixin \Eloquent
 */
class UserPreference extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_preferences';

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
    protected $fillable = [
        'user_id',
        'employment_type',
        'work_authorization',
        'usdod',
        'require_sponsorship',
        'searching_status',
        'date_available',
        'locations',
        'additional_info',
        'current_base_salary',
        'current_contract_rate',
        'target_base_salary',
        'target_contract_rate',
        'current_compensation'
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'locations' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date_available'
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
