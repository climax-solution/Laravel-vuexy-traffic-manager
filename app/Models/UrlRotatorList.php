<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRotatorList extends Model
{
  protected $fillable = [
    'id',
    'uuid',
    'parent_id',
    'dest_url',
    'weight',
    'max_hit_day',
    'spoof_referrer',
    'spoof_type',
    'deep_link',
    'request_id'
  ];
}
