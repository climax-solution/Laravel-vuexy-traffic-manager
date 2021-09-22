<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepUrlList extends Model
{
    protected $fillable = [
      'parent_id',
      'uuid',
      'dest_url',
      'keyword',
      'weight',
      'max_hit_day',
      'take_count',
      'request_id',
      'spoof_referrer',
      'spoof_confirm'
    ];
}
