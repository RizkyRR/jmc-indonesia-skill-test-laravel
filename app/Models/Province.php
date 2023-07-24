<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name_province'
  ];

  public function region()
  {
    return $this->hasMany(Region::class, 'province_id', 'id');
  }
}
