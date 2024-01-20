<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Banner
 * @package App\Models
 * @version February 4, 2020, 4:48 am UTC
 *
 * @property string name
 * @property string contact
 * @property string email
 * @property string start_date
 * @property string banner_link
 * @property string duration
 * @property string duration_type
 * @property string banner_photo
 */
class Banner extends Model
{
    use SoftDeletes;

    public $table = 'banners';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'contact',
        'email',
        'banner_link',
        'banner_photo',
        'start_date',
        'price',
        'duration',
        'duration_type',
        'location',
        'status',
        'type',
        'thumbnail_img',
        'duration_digit'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'contact' => 'string',
        'email' => 'string',
        'start_date' => 'string',
        'banner_link' => 'string',
        'banner_photo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function bannerPackage(){
        return $this->belongsTo(BannerPackage::class,'banner_package_id');
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->start_date, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function getDisplayDurationAttribute(){
        return CommonHelper::convertSecondToDuration(Carbon::now(),$this->duration,$this->duration_type);
    }
    public function getstartAttribute($value){
        return strtotime($this->start_date);
    }
    public function getendAttribute($value){
        return $this->start + (86400 * $this->duration);
    }
}
