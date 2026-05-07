<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about',function(){
    return view('about');
});

Route::get('/register',function(){
    return view('register');
});
Route::get('/contact',function(){
    return view('contact');
});

Route::get('/tentang_saya',function(){
    return view('tentang_saya');
});

Route::get('/login',function(){
    return view('login');
});
