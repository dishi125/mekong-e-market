<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CreditPackage
 * @package App\Models
 * @version February 6, 2020, 6:27 am UTC
 *
 * @property number amount
 * @property number credit
 * @property integer status
 */
class CreditPackage extends Model
{
    use SoftDeletes;

    public $table = 'credit_packages';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'amount',
        'credit',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'amount' => 'double',
        'credit' => 'double',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'amount' => 'required',
        'credit' => 'required'
    ];

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
