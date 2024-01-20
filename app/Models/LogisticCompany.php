<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogisticCompany
 * @package App\Models
 * @version February 5, 2020, 6:43 am UTC
 *
 * @property string name
 * @property string reg_no
 * @property string id_no
 * @property string contact
 * @property string email
 * @property integer state_id
 * @property integer area_id
 * @property string description
 * @property string nursery
 * @property integer exporter_status
 * @property string profile
 * @property integer status
 */
class LogisticCompany extends Model
{
    use SoftDeletes;

    public $table = 'logistic_companies';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'reg_no',
        'id_no',
        'contact',
        'email',
        'state_id',
        'area_id',
        'description',
        'address',
        'nursery',
        'exporter_status',
        'profile',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'reg_no' => 'string',
        'id_no' => 'string',
        'contact' => 'string',
        'email' => 'string',
        'state_id' => 'integer',
        'area_id' => 'integer',
        'description' => 'string',
        'nursery' => 'string',
        'exporter_status' => 'integer',
        'profile' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getDisplayJoinDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function logistic_photos(){
        return $this->hasMany(LogisticPhoto::class,'logistic_company_id','id');
    }

    public function state(){
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function getAppLogisticPhotosAttribute(){

        $images = array();
        foreach ($this->logistic_photos as $logistic_photo){
            $temp = array();
            $temp['image'] = $logistic_photo->image ? url('public/' . $logistic_photo->image) : '';
            array_push($images,$temp);
        }
        return $images;
    }
}
