<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckCode extends Model
{
    protected $table="check_code";

    protected $fillable = ['email','token','numberHourSend','numberCheck'];
}
