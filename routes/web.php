<?php

use App\Models\Feature;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('policy', function(){
    $feature = Feature::first();

    if(!auth()->user()->can('update', $feature)){
        abort('403');
    }

    $feature->update(['name' => 'New feature Name']);

    echo($feature->name);
});