<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Boolean;

class CreditTransaction extends Model
{
    use SoftDeletes;

    public $table = 'credit_transactions';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'type',
        'user_profile_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'id'=>'integer',
        'type'=>'boolean',
        'user_profile_id'=>'integer',
        'amount'=>'double',
        'status'=>'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class, 'id', "user_profile_id");
    }

    public function userCreditTransaction()
    {
        return $this->belongsTo(MyPackage::class, 'credit_transaction_id', "id");
    }
}
