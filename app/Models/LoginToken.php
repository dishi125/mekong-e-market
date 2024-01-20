<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LoginToken
 * @package App\Models
 * @version May 12, 2020, 12:16 pm UTC
 *
 * @property integer user_id
 * @property string token
 * @property integer device_type
 */
class LoginToken extends Model
{
    use SoftDeletes;

    public $table = 'login_tokens';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'token',
        'device_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'token' => 'string',
        'device_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
