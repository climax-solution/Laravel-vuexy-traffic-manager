<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmptyReferrer extends Model
{
    protected $fillable = [
      'id',
      'action',
      'table_name',
      'item_id'
    ];
}
