<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomUrl extends Model
{
    //
    protected $fillable = [
      'id',
      'uuid',
      'link_name',
      'dest_url',
      'tracking_url',
      'advance_options',
      'pixel',
      'campaign',
      'max_hit_day',
      'fallback_url',
      'active_rule'
    ];
}
