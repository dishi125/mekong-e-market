<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SettingPage
 * @package App\Models
 * @version May 14, 2020, 11:34 am UTC
 *
 * @property string name
 * @property string description
 */
class SettingPage extends Model
{
    use SoftDeletes;

    public $table = 'setting_pages';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function getDisplayStartDateAttribute(){
        return \Carbon\Carbon::parse($this->created_at, "UTC")->setTimezone(env('TIME_ZONE'))->format('d/m/Y');
    }
}
