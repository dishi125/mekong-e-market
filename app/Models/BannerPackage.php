<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BannerPackage
 * @package App\Models
 * @version February 4, 2020, 5:04 am UTC
 *
 * @property string location
 * @property string price
 * @property string duration
 * @property string duration_type
 * @property integer status
 */
class BannerPackage extends Model
{
    use SoftDeletes;

    public $table = 'banner_packages';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'location',
        'price',
        'duration',
        'duration_type',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'location' => 'string',
        'price' => 'string',
        'duration' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getDisplayDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d-m-Y');
    }

    public function getDisplayDateFormatAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function getDisplayDurationAttribute(){
        return CommonHelper::convertSecondToDuration(Carbon::now(),$this->duration,$this->duration_type);
    }

}
