<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
  protected $fillable = [
    'id',
    'uuid',
    'link_name',
    'dest_url',
    'tracking_url',
    'campaign',
    'max_hit_day',
    'take_count',
    'pixel',
    'item_id',
    'table_name',
    'fallback_url',
  ];
}
