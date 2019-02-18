<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/passers', function() {
    $crawler = Goutte::request('GET', 'http://www.pshs.edu.ph/nce2018/');
    $crawler->filter('.container_list div .border_list')->each(function ($node) {
        dump($node->text());
    });
});
