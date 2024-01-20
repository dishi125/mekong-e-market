<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ContactUs
 * @package App\Models
 * @version May 26, 2020, 10:56 am UTC
 *
 * @property integer user_profile_id
 * @property string email
 * @property string message
 */
class ContactUs extends Model
{
    use SoftDeletes;

    public $table = 'contactuses';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_profile_id',
        'email',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_profile_id' => 'integer',
        'email' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function user() {
        return $this->belongsTo(UserProfile::class,'user_profile_id','id');
    }

    public function getCreateDateAttribute() {
        return Carbon::parse($this->created_at,'UTC')->timezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
