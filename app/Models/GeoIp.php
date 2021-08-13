<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoIp extends Model
{
    protected $fillable = [
      'id',
      'table_name',
      'item_id',
      'country_list',
      'country_group',
      'action',
      'status'
    ];
}
