<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'province_id',
    'name_region',
    'total_population_region'
  ];

  public function province()
  {
    return $this->belongsTo(Province::class, 'province_id', 'id')->withDefault();
  }
}
