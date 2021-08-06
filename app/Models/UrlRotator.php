<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRotator extends Model
{
  protected $fillable = [
    'id',
    'advance_options',
    'rotation_option',
    'active_position',
    'active_rule'
  ];
}
