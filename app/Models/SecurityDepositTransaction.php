<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SecurityDepositTransaction
 * @package App\Models
 * @version March 25, 2020, 4:27 pm UTC
 *
 * @property integer type
 * @property integer user_profile_id
 * @property number amount
 * @property integer status
 * @property integer is_debit_by_admin
 */
class SecurityDepositTransaction extends Model
{
    use SoftDeletes;

    public $table = 'security_deposit_transactions';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type',
        'user_profile_id',
        'amount',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'user_profile_id' => 'integer',
        'amount' => 'double',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
