<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepUrl extends Model
{
  protected $fillable = [
    'advance_options',
    'pixel',
    'amazon_aff_id',
    'active_rule',
    'active_position',
    'rotation_option',
    'amazon_aff_id'
  ];
}
