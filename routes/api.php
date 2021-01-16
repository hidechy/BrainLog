<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {

    //money
    Route::post('spenditem', 'ApiController@spenditem');
    Route::post('traindata', 'ApiController@traindata');
    Route::post('timeplace', 'ApiController@timeplace');
    Route::post('moneyinsert', 'ApiController@moneyinsert');
    Route::post('monthsummary', 'ApiController@monthsummary');
    Route::post('yearsummary', 'ApiController@yearsummary');
    Route::post('uccardspend', 'ApiController@uccardspend');
    Route::post('allcardspend', 'ApiController@allcardspend');
    Route::post('carditemlist', 'ApiController@carditemlist');

    //stock
    Route::post('stockdataexists', 'ApiController@stockdataexists');
    Route::post('stockdatedata', 'ApiController@stockdatedata');
    Route::post('stockgradedata', 'ApiController@stockgradedata');
    Route::post('stockcodedata', 'ApiController@stockcodedata');
    Route::post('stockindustrylistdata', 'ApiController@stockindustrylistdata');
    Route::post('stockindustrydata', 'ApiController@stockindustrydata');
    Route::post('stockpricedata', 'ApiController@stockpricedata');
    Route::post('stockalldata', 'ApiController@stockalldata');
});
