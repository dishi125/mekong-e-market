<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;
    public $table = 'reports';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'post_id',
        'message',
        'logisctic_company_id'
    ];
}
