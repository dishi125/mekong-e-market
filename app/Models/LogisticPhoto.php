<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogisticPhoto
 * @package App\Models
 * @version February 6, 2020, 3:56 am UTC
 *
 * @property integer logistic_company_id
 * @property string image
 */
class LogisticPhoto extends Model
{
    use SoftDeletes;

    public $table = 'logistic_photos';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'logistic_company_id',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'logistic_company_id' => 'integer',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
