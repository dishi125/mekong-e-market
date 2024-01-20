<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class State
 * @package App\Models
 * @version February 1, 2020, 5:26 am UTC
 *
 * @property string name
 * @property integer status
 */
class State extends Model
{
    use SoftDeletes;

    public $table = 'states';


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

        "name.required"=>"Please Enter State Name."
    ];
    public function area()
    {
        return $this->hasMany(Area::class,"state_id",'id');
    }


}
