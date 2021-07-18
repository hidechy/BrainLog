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

    Route::post('getholiday', 'ApiController@getholiday');

    //money
    Route::post('getmonthstartmoney', 'ApiController@getmonthstartmoney');
    Route::post('getsalary', 'ApiController@getsalary');
    Route::post('spenditem', 'ApiController@spenditem');
    Route::post('traindata', 'ApiController@traindata');
    Route::post('timeplace', 'ApiController@timeplace');
    Route::post('moneyinsert', 'ApiController@moneyinsert');
    Route::post('moneydownload', 'ApiController@moneydownload');
    Route::post('monthsummary', 'ApiController@monthsummary');
    Route::post('yearsummary', 'ApiController@yearsummary');
    Route::post('uccardspend', 'ApiController@uccardspend');
    Route::post('allcardspend', 'ApiController@allcardspend');
    Route::post('carditemlist', 'ApiController@carditemlist');
    Route::post('amazonPurchaseList', 'ApiController@amazonPurchaseList');
    Route::post('spenditemweekly', 'ApiController@spenditemweekly');
    Route::post('timeplaceweekly', 'ApiController@timeplaceweekly');
    Route::post('seiyuuPurchaseList', 'ApiController@seiyuuPurchaseList');
    Route::post('seiyuuPurchaseItemList', 'ApiController@seiyuuPurchaseItemList');
    Route::post('dutyData', 'ApiController@dutyData');
    Route::post('yachinData', 'ApiController@yachinData');
    Route::post('timeplacezerousedate', 'ApiController@timeplacezerousedate');
    Route::post('monthlyspenditem', 'ApiController@monthlyspenditem');
    Route::post('monthlytraindata', 'ApiController@monthlytraindata');
    Route::post('monthlytimeplace', 'ApiController@monthlytimeplace');
    Route::post('monthlyweeknum', 'ApiController@monthlyweeknum');
    Route::post('getMonthlyBankRecord', 'ApiController@getMonthlyBankRecord');
    Route::post('getgolddata', 'ApiController@getgolddata');
    Route::post('gettraindata', 'ApiController@gettraindata');
    Route::post('mercaridata', 'ApiController@mercaridata');
    Route::post('getITFRecord', 'ApiController@getITFRecord');
    Route::post('getFundRecord', 'ApiController@getFundRecord');
    Route::post('getWellsRecord', 'ApiController@getWellsRecord');
    Route::post('getBalanceSheetRecord', 'ApiController@getBalanceSheetRecord');

    //stock
    Route::post('stockdataexists', 'ApiController@stockdataexists');
    Route::post('stockdatedata', 'ApiController@stockdatedata');
    Route::post('stockgradedata', 'ApiController@stockgradedata');
    Route::post('stockcodedata', 'ApiController@stockcodedata');
    Route::post('stockindustrylistdata', 'ApiController@stockindustrylistdata');
    Route::post('stockindustrydata', 'ApiController@stockindustrydata');
    Route::post('stockpricedata', 'ApiController@stockpricedata');
    Route::post('stockalldata', 'ApiController@stockalldata');

    //worktime
    Route::post('worktimemonthdata', 'ApiController@worktimemonthdata');
    Route::post('worktimeinsert', 'ApiController@worktimeinsert');
    Route::post('workinggenbaname', 'ApiController@workinggenbaname');

    //uranai
    Route::post('dailyuranai', 'ApiController@dailyuranai');
    Route::post('monthlyuranai', 'ApiController@monthlyuranai');
    Route::post('monthlyuranaidetail', 'ApiController@monthlyuranaidetail');
    Route::post('leofortune', 'ApiController@leofortune');

    //kotowaza
    Route::post('getkotowazacount', 'ApiController@getkotowazacount');
    Route::post('getkotowaza', 'ApiController@getkotowaza');
    Route::post('changekotowazaflag', 'ApiController@changekotowazaflag');
    Route::post('getkotowazachecktest', 'ApiController@getkotowazachecktest');

});
