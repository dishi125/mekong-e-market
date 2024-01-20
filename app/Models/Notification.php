<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class notification
 * @package App\Models
 * @version February 5, 2020, 5:21 am UTC
 *
 * @property integer user_type
 * @property integer type_id
 * @property integer user_id
 * @property string title
 * @property string description
 * @property string date
 * @property integer status
 */
class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_type',
        'type_id',
        'user_id',
        'title',
        'description',
        'date',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_type' => 'integer',
        'type_id' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_type' => 'required',
        'title' => 'required',
        'description' => 'required',
        'date' => 'required'
    ];

    public function user()
    {
        return $this->hasOne(UserProfile::class,'id',"user_id");
    }

    public function userTypeWise()
    {
        return $this->hasMany(UserProfile::class,'user_type','type_id')
                        ->whereHas('notificationUserTypeWise',function ($query){
                            $query->where('user_type',1)
                                    ->where('type_id','!=',0)
                                    ->whereNull('user_id');
                        });
    }
    public function userIdWise()
    {
        return $this->hasMany(UserProfile::class,'user_type','type_id')
                        ->whereHas('notificationUserTypeWise',function ($query){
                            $query->where('user_type',2)
                                    ->where('type_id','!=',0);
                        });
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function getDisplayDateAttribute(){
        return \Carbon\Carbon::parse($this->date, "UTC")->setTimezone(env('TIME_ZONE'))->format('Y.m.d h:ia');
    }
}
