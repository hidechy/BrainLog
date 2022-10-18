<?php

use App\Models\User;
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

    //login
    Route::post("login", function () {
        $email = request()->get("email");
        $password = request()->get("password");
        $user = User::where("email", $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            $token = str_random(60);
            $user->api_token = $token;
            $user->save();
            return [
                "token" => $token,
                "user" => $user
            ];
        } else {
            return [
                "token" => '',
                "user" => null
            ];
        }
    });


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
    Route::post('homeFixData', 'ApiController@homeFixData');
    Route::post('timeplacezerousedate', 'ApiController@timeplacezerousedate');
    Route::post('monthlyspenditem', 'ApiController@monthlyspenditem');
    Route::post('monthlytraindata', 'ApiController@monthlytraindata');
    Route::post('monthlytimeplace', 'ApiController@monthlytimeplace');
    Route::post('monthlyweeknum', 'ApiController@monthlyweeknum');
    Route::post('getMonthlyBankRecord', 'ApiController@getMonthlyBankRecord');
    Route::post('getgolddata', 'ApiController@getgolddata');
    Route::post('gettraindata', 'ApiController@gettraindata');
    Route::post('mercaridata', 'ApiController@mercaridata');
    Route::post('getFundRecord', 'ApiController@getFundRecord');
    Route::post('getWellsRecord', 'ApiController@getWellsRecord');
    Route::post('getBalanceSheetRecord', 'ApiController@getBalanceSheetRecord');
    Route::post('getITFRecord', 'ApiController@getITFRecord');
    Route::post('getITFPrice', 'ApiController@getITFPrice');
    Route::post('getStockPrice', 'ApiController@getStockPrice');
    Route::post('getDataStock', 'ApiController@getDataStock');
    Route::post('getDataShintaku', 'ApiController@getDataShintaku');
    Route::post('getAllMoney', 'ApiController@getAllMoney');
    Route::post('getAllBenefit', 'ApiController@getAllBenefit');
    Route::post('getStockDetail', 'ApiController@getStockDetail');
    Route::post('getShintakuDetail', 'ApiController@getShintakuDetail');
    Route::post('monthSpendItem', 'ApiController@monthSpendItem');
    Route::post('updateBankMoney', 'ApiController@updateBankMoney');
    Route::post('getYearSpendSummay', 'ApiController@getYearSpendSummay');
    Route::post('getSamedaySpend', 'ApiController@getSamedaySpend');

    Route::post('creditDetail', 'ApiController@creditDetail');
    Route::post('getCreditDateData', 'ApiController@getCreditDateData');
    Route::post('getYearCreditSummay', 'ApiController@getYearCreditSummay');
    Route::post('getYearCreditCommonItem', 'ApiController@getYearCreditCommonItem');

    Route::post('everydaySpendSearch', 'ApiController@everydaySpendSearch');
    Route::post('setBankMove', 'ApiController@setBankMove');
    Route::post('setKeihiData', 'ApiController@setKeihiData');

    Route::post('itemDetailDisplay', 'ApiController@itemDetailDisplay');

    Route::post('getYearSpend', 'ApiController@getYearSpend');


    //money // riverpod
    Route::post('creditCompanySearch', 'ApiController@creditCompanySearch');
    Route::post('bankSearch', 'ApiController@bankSearch');

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
    Route::post('worktimesummary', 'ApiController@worktimesummary');
    Route::post('workingmonthdata', 'ApiController@workingmonthdata');
    Route::post('getGenbaWorkTime', 'ApiController@getGenbaWorkTime');

    //uranai
    Route::post('dailyuranai', 'ApiController@dailyuranai');

    Route::post('monthlyuranai', 'ApiController@monthlyuranai');
    Route::post('yearlyuranai', 'ApiController@yearlyuranai');

    Route::post('monthlyuranaidetail', 'ApiController@monthlyuranaidetail');
    Route::post('leofortune', 'ApiController@leofortune');
    Route::post('getMonthlyUranaiData', 'ApiController@getMonthlyUranaiData');

    //kotowaza
    Route::post('getkotowazacount', 'ApiController@getkotowazacount');
    Route::post('getkotowaza', 'ApiController@getkotowaza');
    Route::post('changekotowazaflag', 'ApiController@changekotowazaflag');
    Route::post('getkotowazachecktest', 'ApiController@getkotowazachecktest');

    //tarot
    Route::post('tarotcard', 'ApiController@tarotcard');
    Route::post('tarotcategory', 'ApiController@tarotcategory');
    Route::post('tarotselect', 'ApiController@tarotselect');
    Route::post('tarothistory', 'ApiController@tarothistory');
    Route::post('tarotthree', 'ApiController@tarotthree');
    Route::post('getAllTarot', 'ApiController@getAllTarot');
    Route::post('getCatTarot', 'ApiController@getCatTarot');

    //dice
    Route::post('dice', 'ApiController@dice');

    //temple
    Route::post('getAllTemple', 'ApiControllerSecond@getAllTemple');
    Route::post('getDateTemple', 'ApiControllerSecond@getDateTemple');
    Route::post('getTempleLatLng', 'ApiControllerSecond@getTempleLatLng');
    Route::post('getTempleName', 'ApiControllerSecond@getTempleName');

    //train
    Route::post('getTrain', 'ApiController@getTrain');
    Route::post('getTrainStation', 'ApiController@getTrainStation');
    Route::post('getTrainCompany', 'ApiController@getTrainCompany');
    Route::post('updateTrainFlag', 'ApiController@updateTrainFlag');

    Route::get('getTrain2', 'ApiController@getTrain2');
    Route::post('getTrainStation2', 'ApiController@getTrainStation2');
//    Route::post('getTrainCompany2', 'ApiController@getTrainCompany2');

    //walk
    Route::post('getWalkRecord', 'ApiController@getWalkRecord');
    Route::match(['get', 'post'], "getWalkRecord2", "ApiController@getWalkRecord2");

    //agent
    Route::post('getAgentName', 'ApiController@getAgentName');
    Route::post('getAgentDocument', 'ApiController@getAgentDocument');

    //youtube
    Route::post('getYoutubeList', 'ApiController@getYoutubeList');
    Route::post('bunruiYoutubeData', 'ApiController@bunruiYoutubeData');
    Route::post('getBunruiName', 'ApiController@getBunruiName');
    Route::post('getSpecialVideo', 'ApiController@getSpecialVideo');
    Route::post('getOrderedVideo', 'ApiController@getOrderedVideo');
    Route::post('getDeletedVideo', 'ApiController@getDeletedVideo');
    Route::post('updateVideoPlayedAt', 'ApiController@updateVideoPlayedAt');

    //kigo
    Route::post('getKigoSeasonRandomList', 'ApiControllerSecond@getKigoSeasonRandomList');
    Route::post('getKigoSearchedList', 'ApiControllerSecond@getKigoSearchedList');

});
