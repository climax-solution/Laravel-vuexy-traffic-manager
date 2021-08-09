<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeywordRotator extends Model
{
    protected $fillable = [
      'id',
      'advance_options',
      'pixel',
      'active_rule',
      'active_position',
      'rotation_option'
    ];
}
