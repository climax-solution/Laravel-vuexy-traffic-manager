<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeywordRotatorList extends Model
{
    protected $fillable = [
      'id',
      'parent_id',
      'uuid',
      'keyword',
      'dest_url',
      'weight',
      'max_hit_day',
      'take_count',
      'spoof_referrer',
      'spoof_service',
      'spoof_confirm'
    ];
}
