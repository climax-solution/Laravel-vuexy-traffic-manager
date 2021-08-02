<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    protected $fillable = [
      'id',
      'action',
      'device',
      'table_name',
      'item_id'
    ];
}
