<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Specie
 * @package App\Models
 * @version February 1, 2020, 3:40 am UTC
 *
 * @property integer main_category_id
 * @property integer sub_category_id
 * @property string name
 * @property integer status
 */
class Specie extends Model
{
    use SoftDeletes;

    public $table = 'species';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'main_category_id',
        'sub_category_id',
        'name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'main_category_id' => 'integer',
        'sub_category_id' => 'integer',
        'name' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'main_category_id' => 'required',
        'sub_category_id' => 'required',
        'name' => 'required'
    ];
    public static $messages=[
        "main_category_id.required"=>"Select Main Category",
        "sub_category_id.required"=>"Select Sub Category",
        "name.required"=>"Please Enter Specie ."
    ];
    public function subcategory()
    {
        return $this->hasOne(SubCategory::class,"id","sub_category_id");
    }

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
