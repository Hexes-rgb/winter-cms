<?php

use Illuminate\Support\Facades\Route;
use Winter\Storm\Support\Facades\Input;

Route::any('/post', function () {
    dd(\Request::all());
});
