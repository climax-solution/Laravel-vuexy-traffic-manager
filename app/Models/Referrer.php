<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referrer extends Model
{
    protected $fillable = [
      'id',
      'action',
      'domain_type',
      'domain_reg',
      'domain_name',
      'table_name',
      'item_id'
    ];
}
