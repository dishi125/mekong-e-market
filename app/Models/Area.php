<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\ToModel;


/**
 * Class Area
 * @package App\Models
 * @version February 1, 2020, 5:54 am UTC
 *
 * @property integer state_id
 * @property string name
 * @property integer status
 */
class Area extends Model implements ToModel
{
    use SoftDeletes;

    public $table = 'areas';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'state_id',
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
        'state_id' => 'integer',
        'name' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'state_id' => 'required',
        'name' => 'required'
    ];

    public static $messages=[

        "name.required"=>"Please Enter State Name."
    ];
    public function state()
    {
        return $this->hasOne(State::class,"id","state_id");
    }
    public function model(array $row)
    {
        return new Area([
            'state_id' => $row[0],
            'name'    => $row[1],
            'status' => $row[2],
            'updated_at'=>$row[3],
            'created_at'=>$row[4]

        ]);
    }
}
