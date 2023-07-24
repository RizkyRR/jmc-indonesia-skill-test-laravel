<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('activeMenu')) {
  function activeMenu($uri = '')
  {
    $active = '';

    if (Request::is($uri . '/*') || Request::is($uri)) {
      $active = 'active';
    }

    return $active;
  }
}
