<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MyPackage extends Model
{
    use SoftDeletes;


    public $table = 'my_packages';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'credit_package_id',
        'user_profile_id',
        'credit_transaction_id',
        'transaction_id',
        'transaction_status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'=>'integer',
        'credit_package_id'=>'integer',
        'user_profile_id'=>'integer',
        'credit_transaction_id'=>'integer',
        'transaction_id'=>'string',
        'transaction_status'=>'integer',
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
    public function credit_package()
    {
        return $this->hasOne(CreditPackage::class, 'id', "credit_package_id");
    }
    public function credit_transaction()
    {
        return $this->hasOne(CreditTransaction::class, 'id', "credit_transaction_id");
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y  h.i a');
    }

    public function getBalanceTopUpAttribute(){

        $balance_credit = CreditTransaction::select(
                DB::raw('SUM(CASE
                                    WHEN type = 0 THEN amount
                                    ELSE -amount
                                    END) AS BalanceCredit'))
                ->where('user_profile_id',$this->user_profile_id)
                ->where('id','<=',$this->credit_transaction_id)
                ->pluck('BalanceCredit');

        return isset($balance_credit[0]) ? $balance_credit[0] : 0;
    }
}
