<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomUrl extends Model
{
    //
    protected $fillable = [
      'id',
      'advance_options',
      'active_rule'
    ];
}
