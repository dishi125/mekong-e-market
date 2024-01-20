<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
/**
 * Class MainCategory
 * @package App\Models
 * @version January 31, 2020, 6:39 am UTC
 *
 * @property string name
 * @property integer status
 */
class MainCategory extends Model
{
    use SoftDeletes;

    public $table = 'main_categories';

    protected $dates = ['deleted_at'];



    public $fillable = [
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
        'name' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];
    public static $messages=[

        "name.required"=>"Please Enter Main Category."
    ];
    public function subcategories_dropdown()
    {
        return $this->hasMany(SubCategory::class,"main_category_id",'id')->where('status',1);
    }
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class,"main_category_id",'id');
    }
    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
