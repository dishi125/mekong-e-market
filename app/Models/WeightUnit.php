<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WeightUnit
 * @package App\Models
 * @version May 15, 2020, 8:16 am UTC
 *
 * @property string unit
 */
class WeightUnit extends Model
{
    use SoftDeletes;

    public $table = 'weight_units';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'unit',
        'credit_per_transaction'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'unit' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
