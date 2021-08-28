<?php
Route::get('/', function () {
    return redirect('/article/index');
});



//[article]
Route::get('/article/index', 'Article\ArticleController@index');
Route::get('/article/{yearmonth}/index', 'Article\ArticleController@index');
Route::get('/article/{dispdate}/display', 'Article\ArticleController@display');
Route::get('/article/{dispdate}/edit', 'Article\ArticleController@edit');
Route::post('/article/confirm', 'Article\ArticleController@confirm');
Route::post('/article/input', 'Article\ArticleController@input');
Route::post('/article/datejump', 'Article\ArticleController@datejump');
Route::get('/article/search', 'Article\ArticleController@search');
Route::post('/article/searchresult', 'Article\ArticleController@searchresult');
Route::get('/article/{tag}/searchresult', 'Article\ArticleController@searchresult');
Route::get('/article/{dispdate}/photo', 'Article\ArticleController@photo');
Route::post('/article/photoupload', 'Article\ArticleController@photoupload');
Route::post('/article/photojump', 'Article\ArticleController@photojump');
Route::get('/article/multiinput', 'Article\ArticleController@multiinput');
Route::post('/article/multiinsert', 'Article\ArticleController@multiinsert');
Route::post('/article/photochange', 'Article\ArticleController@photochange');
Route::get('/article/future', 'Article\ArticleController@future');
Route::post('/article/photorotate', 'Article\ArticleController@photorotate');
Route::get('/article/{yearmonth}/taging', 'Article\ArticleController@taging');
Route::post('/article/taginput', 'Article\ArticleController@taginput');
Route::post('/article/articlemerge', 'Article\ArticleController@articlemerge');
Route::get('/article/traindateapi', 'Article\ArticleController@traindateapi');
Route::get('/article/{yearmonth}/trainmonthdataapi', 'Article\ArticleController@trainmonthdataapi');
Route::get('/article/{ymd}/traindataapi', 'Article\ArticleController@traindataapi');
Route::get('/article/kotowazaapi', 'Article\ArticleController@kotowazaapi');

Route::get('/article/YahooUranaiGet', 'Article\ArticleController@YahooUranaiGet');
Route::get('/article/LeoFortuneGet', 'Article\ArticleController@LeoFortuneGet');


//[money]
Route::get('/money/index', 'Money\MoneyController@index');
Route::get('/money/{yearmonth}/index', 'Money\MoneyController@index');
Route::get('/money/input', 'Money\MoneyController@input');
Route::post('/money/multiinput', 'Money\MoneyController@multiinput');
Route::post('/money/multiinsert', 'Money\MoneyController@multiinsert');
Route::post('/money/singleinput', 'Money\MoneyController@singleinput');
Route::get('/money/bank', 'Money\MoneyController@bank');
Route::post('/money/bankinput', 'Money\MoneyController@bankinput');
Route::get('/money/summary', 'Money\MoneyController@summary');
Route::get('/money/salary', 'Money\MoneyController@salary');
Route::post('/money/salaryinput', 'Money\MoneyController@salaryinput');
Route::get('/money/credit', 'Money\MoneyController@credit');
Route::post('/money/creditinsert', 'Money\MoneyController@creditinsert');
Route::post('/money/moneyjogai', 'Money\MoneyController@moneyjogai');
Route::get('/money/repair', 'Money\MoneyController@repair');
Route::post('/money/repairsearch', 'Money\MoneyController@repairsearch');
Route::post('/money/repairinput', 'Money\MoneyController@repairinput');
Route::get('/money/history', 'Money\MoneyController@history');
Route::get('/money/graph', 'Money\MoneyController@graph');
Route::get('/money/{yearmonth}/graph', 'Money\MoneyController@graph');
Route::get('/money/{ymd}/weeklydisp', 'Money\MoneyController@weeklydisp');
Route::get('/money/{ymd}/weeklyinput', 'Money\MoneyController@weeklyinput');
Route::post('/money/weeklyinsert', 'Money\MoneyController@weeklyinsert');
Route::get('/money/{yearmonth}/monthlydisp', 'Money\MoneyController@monthlydisp');
Route::post('/money/spendinput', 'Money\MoneyController@spendinput');
Route::post('/money/timeplaceinput', 'Money\MoneyController@timeplaceinput');
Route::get('/money/{yearmonth}/itemsummary', 'Money\MoneyController@itemsummary');
Route::get('/money/{yearmonth}/api', 'Money\MoneyController@api');
Route::get('/money/{yearmonth}/samedayapi', 'Money\MoneyController@samedayapi');
Route::get('/money/{yearmonth}/spenditemapi', 'Money\MoneyController@spenditemapi');
Route::get('/money/{yearmonth}/monthlistapi', 'Money\MoneyController@monthlistapi');
Route::get('/money/{yearmonth}/monthitemapi', 'Money\MoneyController@monthitemapi');
Route::get('/money/{yearmonth}/monthkoumokuapi', 'Money\MoneyController@monthkoumokuapi');
Route::get('/money/{data}/onedayinputapi', 'Money\MoneyController@onedayinputapi');
Route::get('/money/monthscoreapi', 'Money\MoneyController@monthscoreapi');
Route::get('/money/{bank}/bankapi', 'Money\MoneyController@bankapi');
Route::get('/money/creditdatainput', 'Money\MoneyController@creditdatainput');
Route::post('/money/creditdatamodify', 'Money\MoneyController@creditdatamodify');
Route::post('/money/creditdatainputexecute', 'Money\MoneyController@creditdatainputexecute');

Route::get('/money/golddatalist', 'Money\MoneyController@golddatalist');
Route::get('/money/golddatainput', 'Money\MoneyController@golddatainput');
Route::post('/money/golddatainputexecute', 'Money\MoneyController@golddatainputexecute');

Route::get('/money/mercaridatalist', 'Money\MoneyController@mercaridatalist');
Route::get('/money/mercaridatainput', 'Money\MoneyController@mercaridatainput');
Route::post('/money/mercaridatainputexecute', 'Money\MoneyController@mercaridatainputexecute');

Route::get('/money/funddatalist', 'Money\MoneyController@funddatalist');
Route::get('/money/funddatainput', 'Money\MoneyController@funddatainput');
Route::post('/money/funddatainputexecute', 'Money\MoneyController@funddatainputexecute');

Route::get('/money/balancesheetlist', 'Money\MoneyController@balancesheetlist');
Route::get('/money/balancesheetinput', 'Money\MoneyController@balancesheetinput');
Route::post('/money/balancesheetinputexecute', 'Money\MoneyController@balancesheetinputexecute');

Route::post('/money/makeMoneyTotalList', 'Money\MoneyController@makeMoneyTotalList');

Route::get('/money/rsdatalist', 'Money\MoneyController@rsdatalist');
Route::get('/money/rsdatainput', 'Money\MoneyController@rsdatainput');
Route::post('/money/rsdatainputexecute', 'Money\MoneyController@rsdatainputexecute');

//[other]
Route::get('/other/tuning', 'Other\OtherController@tuning');
Route::get('/other/holiday', 'Other\OtherController@holiday');
Route::post('/other/holidayinput', 'Other\OtherController@holidayinput');
Route::get('/other/user', 'Other\OtherController@user');
Route::post('/other/userinput', 'Other\OtherController@userinput');
Route::get('/other/weather', 'Other\OtherController@weather');
Route::get('/other/tag', 'Other\OtherController@tag');
Route::post('/other/taginput', 'Other\OtherController@taginput');
Route::get('/other/seiyuu', 'Other\OtherController@seiyuu');
Route::post('/other/seiyuuinput', 'Other\OtherController@seiyuuinput');
Route::post('/other/seiyuuarticle', 'Other\OtherController@seiyuuarticle');
Route::get('/other/work', 'Other\OtherController@work');
Route::post('/other/workinput', 'Other\OtherController@workinput');
Route::get('/other/shokureki', 'Other\OtherController@shokureki');
Route::match(['get', 'post'] , '/other/souvenir', 'Other\OtherController@souvenir');
Route::get('/other/kinmu', 'Other\OtherController@kinmu');
Route::get('/other/{yearmonth}/kinmu', 'Other\OtherController@kinmu');
Route::post('/other/kinmuinput', 'Other\OtherController@kinmuinput');
Route::get('/other/{yearmonth}/weathermonthapi', 'Other\OtherController@weathermonthapi');
Route::get('/other/kabukaapi', 'Other\OtherController@kabukaapi');
Route::get('/other/{str}/kabukaselectapi', 'Other\OtherController@kabukaselectapi');

Route::get('/other/route', 'Other\OtherController@route');
Route::post('/other/routemap', 'Other\OtherController@routemap');











// //[affi]
// Route::get('/affi/index', 'Affi\AffiController@index');
// Route::get('/affi/input', 'Affi\AffiController@input');
// Route::post('/affi/datainput', 'Affi\AffiController@datainput');
// Route::get('/affi/{link_number}/detail', 'Affi\AffiController@detail');
// Route::post('/affi/a8input', 'Affi\AffiController@a8input');
// Route::post('/affi/yahooinput', 'Affi\AffiController@yahooinput');
// Route::post('/affi/yahooretry', 'Affi\AffiController@yahooretry');



// //[temple]
// Route::get('/temple/index', 'Temple\TempleController@index');
// Route::get('/temple/input', 'Temple\TempleController@input');
// Route::post('/temple/datainput', 'Temple\TempleController@datainput');
// Route::post('/temple/selectphoto', 'Temple\TempleController@selectphoto');
// Route::post('/temple/callphoto', 'Temple\TempleController@callphoto');



//[movie]
Route::get('/movie/input', 'Movie\MovieController@input');
Route::post('/movie/datainput', 'Movie\MovieController@datainput');
Route::get('/movie/api', 'Movie\MovieController@api');



//[anken]
Route::get('/anken/index', 'Anken\AnkenController@index');
Route::get('/anken/create', 'Anken\AnkenController@create');
Route::post('/anken/store', 'Anken\AnkenController@store');
Route::get('/anken/edit', 'Anken\AnkenController@edit');
Route::post('/anken/update', 'Anken\AnkenController@update');



//[url]
Route::get('/url/index', 'Url\UrlController@index');
