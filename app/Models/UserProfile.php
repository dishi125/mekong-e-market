<?php

namespace App\Models;

use App\Enums\Type;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class UserProfiles
 * @package App\Models
 * @version February 5, 2020, 8:30 am UTC
 *
 * @property string name
 * @property string email
 * @property string password
 * @property string profile_pic
 * @property string phone_no
 * @property string user_type
 * @property integer main_category_id
 * @property integer company_name
 * @property integer company_reg_no
 * @property string company_tel_no
 * @property string job_description
 * @property integer state_id
 * @property integer area_id
 */
class UserProfile extends Model
{
    use SoftDeletes;

    public $table = 'user_profiles';
    protected $hidden = ['password'];
    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'email',
        'password',
        'profile_pic',
        'phone_no',
        'user_type',
        'main_category_id',
        'company_name',
        'company_reg_no',
        'company_tel_no',
        'state_id',
        'area_id',
        'address',
        'company_email',
        'document',
        'job_description',
        'preferred_status',
        'is_approved_status',
        'parent_id',
        'package_id',
        'is_seen_preferred',
        'is_preferred_approved'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'profile_pic' => 'string',
        'phone_no' => 'string',
        'user_type' => 'string',
        'main_category_id' => 'integer',
        'company_name' => 'string',
        'company_reg_no' => 'string',
        'company_tel_no' => 'string',
        'state_id' => 'integer',
        'area_id' => 'integer',
        'address'=>'string',
        'company_email'=>'string',
        'document'=>'string',
        'preferred_status'=>'integer',
        'is_approved_status'=>'integer',
        'parent_id'=>"integer",
        'package_id'=>'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];


    public function state()
    {
        return $this->hasOne(State::class,'id',"state_id");
    }

    public function area()
    {
        return $this->hasOne(Area::class,'id',"area_id");
    }

    public function maincategory()
    {
        return $this->hasOne(MainCategory::class,'id',"main_category_id");
    }
    public function subcategory()
    {
        return $this->hasOne(SubCategory::class, 'id', "sub_category_id");
    }
    public function usertype()
    {
        return $this->hasone(Type::class,"user_type");
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }

    public function sellerRating(){
        return $this->hasMany(Rating::class,'seller_id','id');
    }

    public function getSellerRate(){
        return $this->hasMany(Rating::class, 'seller_id')
                        ->select('seller_id', DB::raw('AVG(rate) as rate'))
                        ->groupBy('seller_id');
    }

    public function getSellerReview(){
        return $this->hasMany(Rating::class, 'seller_id')
                        ->select('seller_id', DB::raw('count(*) as review'))
                        ->whereNotNull('ratings.review')
                        ->where('ratings.review','!=','')
                        ->groupBy('seller_id');
    }

    public function getSellerRatingAttribute(){
        return isset($this->getSellerRate[0]->rate) ? round($this->getSellerRate[0]->rate,2) : 0;
    }

    public function getSellerReviewAttribute(){
        return isset($this->getSellerReview[0]->review) ? $this->getSellerReview[0]->review : 0;
    }

    public function getProfilePicAttribute($value){
        $image = '';
        if($value){
            $image = url('public/' . $value);
        }
        return $image;
    }

    public function getDocumentAttribute($value){
        $doc = '';
        if($value){
            $doc = url('public/' . $value);
        }
        return $doc;
    }

    public function notificationUserTypeWise()
    {
        return $this->belongsTo(Notification::class,'user_type','type_id');
    }

//    public function getCompanyAddressAttribute(){
//
//        $address = '';
//        if(isset($this->address)){
//            $address .= $this->address;
//        }
//        if(isset($this->area)){
//            $address .= ', '.$this->area->name;
//        }
//        if(isset($this->address)){
//            $address .= ', '.$this->state->name;
//        }
//        return trim($address);
//    }
}
