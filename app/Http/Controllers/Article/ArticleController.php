<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;
use Input;

class ArticleController extends Controller
{
    public $Utility;
    public $user;

    public function __construct()
    {

        $sql = "SET sql_mode = '';";
        DB::statement($sql);

        $this->Utility = new Utility;

        //---------------------------------//
        $file = public_path() . "/mySetting/user.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $this->user = mb_convert_encoding($content, "utf8", "sjis-win");
        }
        //---------------------------------//
    }

    public function index($yearmonth = null)
    {
        $useDevice = ($this->Utility->wp_is_mobile() == false) ? "pc" : "mobile";

        /////////////////////////////////
        $file = public_path() . "/mySetting/weather.data";
        $weatherFileUpdateDate = date("Y-m-d", filemtime($file));
        if ($weatherFileUpdateDate != date("Y-m-d")) {
            return redirect('/other/weather');
        }
        /////////////////////////////////

        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }
            $holiday[] = trim($v);
        }
        sort($holiday);
        //------------------//

        $calStartYear = 0;
        $calEndYear = 0;
        $calStartMonth = 0;
        $calEndMonth = 0;

        $ym_flag = 0;

        if (!empty($yearmonth)) {
            list($calStartYear, $calStartMonth) = explode("-", $yearmonth);
            $ym_flag = 1;
        } else {
            $calStartYear = date("Y");

            switch (date("m")) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                    $calStartMonth = 1;
                    break;
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12:
                    $calStartMonth = 7;
                    break;
            }
        }

        if ($calStartMonth == 1) {
            $prevStartYear = ($calStartYear - 1);
            $prevStartMonth = 7;
            $nextStartYear = $calStartYear;
            $nextStartMonth = 7;
        } else if ($calStartMonth == 7) {
            $prevStartYear = $calStartYear;
            $prevStartMonth = 1;
            $nextStartYear = ($calStartYear + 1);
            $nextStartMonth = 1;
        }

        ////////////////////////////////////////////////////////////
        $calEndYear = $calStartYear;
        $calEndMonth = ($calStartMonth + (6 - 1));

        $calStartDate = $calStartYear . "-" . $calStartMonth . "-01";
        $calStartWNum = date("w", strtotime($calStartDate));

        $calEndEnd = date("t", strtotime($calEndYear . "-" . $calEndMonth . "-01"));
        $calEndDate = $calEndYear . "-" . sprintf("%02d", $calEndMonth) . "-" . $calEndEnd;

        $calStartDate_unix = strtotime($calStartDate);
        $calEndDate_unix = strtotime($calEndDate);

        //---------------//
        $moneyGetYm_ = [];
        //---------------//

        $calDate = [];
        $j = 0;
        for ($i = $calStartDate_unix; $i <= $calEndDate_unix; $i += 86400) {
            $calDate[($calStartWNum + $j)] = date("Y-m-d", $i);
            $j++;

            $moneyGetYm_[date("Y-m", $i)] = "";
        }
        ////////////////////////////////////////////////////////////

        $prevYm = $prevStartYear . "-" . sprintf("%02d", $prevStartMonth);
        $nextYm = $nextStartYear . "-" . sprintf("%02d", $nextStartMonth);

        $sevenDaysAgo = date("Y-m-d", (strtotime(date("Y-m-d")) - (86400 * 7)));

        //---------------//
        $spend = [];
        $allMoney = [];
        $date_money = [];

        $oneBefore_year = date("Y", (strtotime($calStartDate) - 86400));
        $oneBefore_month = date("m", (strtotime($calStartDate) - 86400));
        $oneBefore_day = date("d", (strtotime($calStartDate) - 86400));
        $oneBefore = DB::table('t_money')
            ->where('year', '=', $oneBefore_year)
            ->where('month', '=', $oneBefore_month)
            ->where('day', '=', $oneBefore_day)
            ->get();

        $oneBefore_date = $oneBefore_year . "-" . $oneBefore_month . "-" . $oneBefore_day;

        if (isset($oneBefore[0])) {
            $lineSum = $this->Utility->makeLineSum($oneBefore[0]);
            $sum = $lineSum[0];
            $bank = $lineSum[1];
            $pay = $lineSum[2];
            $date_money[0] = $oneBefore_date . "|" . (array_sum($sum) + array_sum($bank) + array_sum($pay));
        }

        if (!empty($moneyGetYm_)) {
            $moneyGetYm = array_keys($moneyGetYm_);
            $i = 1;
            foreach ($moneyGetYm as $v) {
                list($sYear, $sMonth) = explode("-", $v);

                //------------------------//
                $result = DB::table('t_money')
                    ->where('year', '=', $sYear)
                    ->where('month', '=', $sMonth)
                    ->orderBy('day')
                    ->get();

                if (isset($result[0])) {
                    foreach ($result as $v2) {
                        $lineSum = $this->Utility->makeLineSum($v2);
                        $sum = $lineSum[0];
                        $bank = $lineSum[1];
                        $pay = $lineSum[2];
                        $_date = $v2->year . "-" . $v2->month . "-" . $v2->day;
                        $date_money[$i] = $_date . "|" . (array_sum($sum) + array_sum($bank) + array_sum($pay));
                        $i++;
                    }
                }
                //------------------------//
            }
        }

        if (!empty($date_money)) {
            for ($i = 1; $i < count($date_money); $i++) {
                if (isset($date_money[$i - 1]) and isset($date_money[$i])) {
                    list(, $zenjitsu) = explode("|", $date_money[$i - 1]);
                    list($date, $all) = explode("|", $date_money[$i]);
                    $spend[$date] = ($zenjitsu - $all);
                    $allMoney[$date] = $all;
                }
            }
        }
        //---------------//

        //----------------------------//
        $article_num = [];
        if (!empty($moneyGetYm)) {

            $articleTables = $this->Utility->getArticleTable();

            $ary1 = [];
            foreach ($moneyGetYm as $v) {
                list($sYear, $sMonth) = explode("-", $v);

                ///////////////////////////////////////////////////
                $table = "t_article" . $sYear;

                if (!in_array($table, $articleTables)) {
                    $SQL = " CREATE TABLE `" . $table . "` ( " .
                        " `id` int(10) unsigned NOT NULL AUTO_INCREMENT, " .
                        " `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL, " .
                        " `month` varchar(2) COLLATE utf8_unicode_ci NOT NULL, " .
                        " `day` varchar(2) COLLATE utf8_unicode_ci NOT NULL, " .
                        " `num` int(11) NOT NULL, " .
                        " `article` text COLLATE utf8_unicode_ci NOT NULL, " .
                        " `hide` varchar(1) COLLATE utf8_unicode_ci NOT NULL, " .
                        " `tag` varchar(30) COLLATE utf8_unicode_ci NOT NULL, " .
                        " `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', " .
                        " `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', " .
                        " PRIMARY KEY (`id`) " .
                        " ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ";

                    DB::statement($SQL);
                }
                ///////////////////////////////////////////////////

                if ($this->user == "hidechy") {
                    $result = DB::table($table)
                        ->where('year', '=', $sYear)
                        ->where('month', '=', $sMonth)
                        ->orderBy('day')
                        ->get();
                } else {
                    $result = DB::table($table)
                        ->where('year', '=', $sYear)
                        ->where('month', '=', $sMonth)
                        ->where('hide', '=', 0)
                        ->orderBy('day')
                        ->get();
                }

                if (isset($result[0])) {
                    foreach ($result as $v2) {
                        $ary1[$v2->year . "-" . $v2->month . "-" . $v2->day][] = $v2->article;
                    }
                }
            }

            if (!empty($ary1)) {
                foreach ($ary1 as $date => $v) {
                    $article_num[$date] = count($v);
                }
            }
        }
        //----------------------------//

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        $WorkTime = [];

        $startId = "";
        for ($i = 0; $i < 200; $i++) {
            if (isset($calDate[$i])) {
                list($year, $month, $day) = explode("-", $calDate[$i]);
                $result = DB::table('t_worktime')
                    ->where('year', '=', $year)
                    ->where('month', '=', $month)
                    ->where('day', '=', $day)
                    ->get(['id']);

                if (isset($result[0])) {
                    $startId = $result[0]->id;
                    break;
                }
            }
        }

        $endId = "";
        for ($i = 200; $i > 0; $i--) {
            if (isset($calDate[$i])) {
                list($year, $month, $day) = explode("-", $calDate[$i]);
                $result = DB::table('t_worktime')
                    ->where('year', '=', $year)
                    ->where('month', '=', $month)
                    ->where('day', '=', $day)
                    ->get(['id']);

                if (isset($result[0])) {
                    $endId = $result[0]->id;
                    break;
                }
            }
        }

        $result = DB::table('t_worktime')
            ->where('id', '>=', $startId)
            ->where('id', '<=', $endId)
            ->get(['year', 'month', 'day', 'work_start', 'work_end']);

        if (isset($result[0])) {
            foreach ($result as $v) {
                $ws = date("H:i", strtotime($v->work_start));
                $we = date("H:i", strtotime($v->work_end));
                $WorkTime[$v->year . "-" . $v->month . "-" . $v->day] = $ws . "-" . $we;
            }
        }
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        /////--------------------------------/////
        $fortune_good = [];
        $fortune_bad = [];

        list($year, ,) = explode("-", $calStartDate);

        $result = DB::table("t_article" . $year)
            ->where('article', 'like', '%吉日-高島易団%')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $ex_v = explode("\n", $v->article);
            $_goodline = 0;
            $_badline = 0;
            foreach ($ex_v as $lineno => $linevalue) {
                if (preg_match("/吉日-高島易団/", trim($linevalue))) {
                    $_goodline = $lineno;
                }
                if (preg_match("/注意日-高島易団/", trim($linevalue))) {
                    $_badline = $lineno;
                }
            }

            $ex_good_value = explode("、", $ex_v[$_goodline + 1]);
            foreach ($ex_good_value as $day) {
                $fortune_good[$v->year . "-" . $v->month . "-" . sprintf("%02d", strtr($day, ['日' => '']))] = '';
            }

            $ex_bad_value = explode("、", $ex_v[$_badline + 1]);
            foreach ($ex_bad_value as $day) {
                $fortune_bad[$v->year . "-" . $v->month . "-" . sprintf("%02d", strtr($day, ['日' => '']))] = '';
            }
        }
        /////--------------------------------/////

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>//
        $sanpai = [];//参拝
        $result = DB::table('t_temple')->get();
        foreach ($result as $v) {
            $sanpai[$v->year . "-" . $v->month . "-" . $v->day] = "";
        }
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>//

        return view('article.index')
            ->with('ym_flag', $ym_flag)
            ->with('calDate', $calDate)
            ->with('prevYm', $prevYm)
            ->with('nextYm', $nextYm)
            ->with('sevenDaysAgo', $sevenDaysAgo)
            ->with('holiday', $holiday)
            ->with('allMoney', $allMoney)
            ->with('spend', $spend)
            ->with('user', $this->user)
            ->with('article_num', $article_num)
            ->with('useDevice', $useDevice)
            ->with('WorkTime', $WorkTime)
            ->with('fortune_good', $fortune_good)
            ->with('fortune_bad', $fortune_bad)
            ->with('sanpai', $sanpai);
    }

    public function display($dispdate = null)
    {
        $useDevice = ($this->Utility->wp_is_mobile() == false) ? "pc" : "mobile";

        list($year, $month, $day) = explode("-", $dispdate);

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>//
        $result = DB::table('t_timeplace')
            ->where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->orderBy('time')->get();

        $timeTable = "";
        $timeTable .= "<table>";
        foreach ($result as $v) {
            $bg = ($v->place == "移動中") ? 'background: #ffcccc;' : 'background: #ffffff;';
            $timeTable .= "<tr style='" . $bg . "'>";
            $timeTable .= "<td style='border: 1px solid #cccccc; padding: 2px;'>" . $v->time . "</td>";
            $timeTable .= "<td style='border: 1px solid #cccccc; padding: 2px;'>" . $v->place . "</td>";
            $timeTable .= "<td style='border: 1px solid #cccccc; padding: 2px; text-align: right;'>" . number_format($v->price) . "</td>";
            $timeTable .= "</tr>";
        }
        $timeTable .= "</table>";
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>//

        //---------------------------------------// credit
        $credit = [];
        $result = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        if (isset($result[0])) {
            foreach ($result as $v) {
                $credit[] = $v->item . "　(" . number_format($v->price) . "円)";
            }
        }
        //---------------------------------------// credit

        $table = "t_article" . $year;

        ///////////////////////////////////////////////////
        $SQL = " CREATE TABLE IF NOT EXISTS `" . $table . "` ( " .
            " `id` int(10) unsigned NOT NULL AUTO_INCREMENT, " .
            " `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL, " .
            " `month` varchar(2) COLLATE utf8_unicode_ci NOT NULL, " .
            " `day` varchar(2) COLLATE utf8_unicode_ci NOT NULL, " .
            " `num` int(11) NOT NULL, " .
            " `article` text COLLATE utf8_unicode_ci NOT NULL, " .
            " `hide` varchar(1) COLLATE utf8_unicode_ci NOT NULL, " .
            " `tag` varchar(30) COLLATE utf8_unicode_ci NOT NULL, " .
            " `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', " .
            " `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', " .
            " PRIMARY KEY (`id`) " .
            " ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ";

        DB::statement($SQL);
        ///////////////////////////////////////////////////

        if ($this->user == "hidechy") {
            $result = DB::table($table)
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->orderBy('num')
                ->get();
        } else {
            $result = DB::table($table)
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->where('hide', '=', 0)
                ->orderBy('num')
                ->get();
        }

        $data = [];
        if (isset($result[0])) {
            foreach ($result as $k => $v) {
                $data[$k]['tag'] = $v->tag;
                $data[$k]['article'] = $v->article;
            }
        }

        $prevDate = date("Y-m-d", (strtotime($dispdate) - 86400));
        $nextDate = date("Y-m-d", (strtotime($dispdate) + 86400));

        //------------------//
        $weather = [];
        $file = public_path() . "/mySetting/weather.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }
            $ex_v = explode("|", trim($v));
            $weather[trim($ex_v[0])] = trim($ex_v[1]);
        }
        ksort($weather);
        $todayWeather = (isset($weather[$dispdate])) ? $weather[$dispdate] : "";
        //------------------//

        //-------------------------------------------//
        $todayMoney = "";
        $prevMoney = "";
        $beforeMonthMoney = "";

        $diff_month = "";
        $diff_day = "";

        $average = "";

        $result = DB::table('t_money')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        if (isset($result[0])) {
            $lineSum = $this->Utility->makeLineSum($result[0]);
            $sum = $lineSum[0];
            $bank = $lineSum[1];
            $pay = $lineSum[2];
            $todayMoney = (array_sum($sum) + array_sum($bank) + array_sum($pay));

            list($prevY, $prevM, $prevD) = explode("-", $prevDate);

            $result2 = DB::table('t_money')
                ->where('year', '=', $prevY)
                ->where('month', '=', $prevM)
                ->where('day', '=', $prevD)
                ->get();

            if (isset($result2[0])) {
                $lineSum = $this->Utility->makeLineSum($result2[0]);
                $sum = $lineSum[0];
                $bank = $lineSum[1];
                $pay = $lineSum[2];
                $prevMoney = (array_sum($sum) + array_sum($bank) + array_sum($pay));
            }

            $diff_day = ($prevMoney - $todayMoney);

            $beforeMonthEnd = date("Y-m-d", strtotime($year . "-" . $month . "-01") - 1);
            list($bmY, $bmM, $bmD) = explode("-", $beforeMonthEnd);

            $result3 = DB::table('t_money')
                ->where('year', '=', $bmY)
                ->where('month', '=', $bmM)
                ->where('day', '=', $bmD)
                ->get();

            if (isset($result3[0])) {
                $lineSum = $this->Utility->makeLineSum($result3[0]);
                $sum = $lineSum[0];
                $bank = $lineSum[1];
                $pay = $lineSum[2];
                $beforeMonthMoney = (array_sum($sum) + array_sum($bank) + array_sum($pay));
            }

            $diff_month = ($beforeMonthMoney - $todayMoney);

            $average = floor($diff_month / $day);
        }
        //-------------------------------------------//

        //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        $folderPhoto = [];
        if (!empty($dispdate)) {
            list($year, $month, $day) = explode("-", $dispdate);

            $folderPath = public_path() . "/UPPHOTO";
            $folderPath .= "/" . $year;
            $folderPath .= "/" . $dispdate;

            $folderPhoto = $this->Utility->getFolderPhoto($folderPath);
            sort($folderPhoto);
        }
        //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

        //-----------------------------------------//
        $uranai = "";

        $file = public_path() . "/mySetting/uranai.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    $ex_v = explode("|", trim($v));
                    if ($dispdate == $ex_v[0]) {
                        array_shift($ex_v);
                        $ary = [];
                        foreach ($ex_v as $v2) {
                            $ary[] = strtr($v2, ['。' => '。<br>']);
                        }
                        $uranai = implode("<br>", $ary);
                        break;
                    }
                }
            }
        }
        //-----------------------------------------//

        $appUrl = "http://" . $_SERVER['HTTP_HOST'] . "/BrainLog/public";


        return view('article.display')
            ->with('dispdate', $dispdate)
            ->with('data', $data)
            ->with('prevDate', $prevDate)
            ->with('nextDate', $nextDate)
            ->with('weather', $todayWeather)
            ->with('todayMoney', $todayMoney)
            ->with('diff_month', $diff_month)
            ->with('diff_day', $diff_day)
            ->with('average', $average)
            ->with('useDevice', $useDevice)
            ->with('folderPath', $folderPath)
            ->with('folderPhoto', $folderPhoto)
            ->with('credit', $credit)
            ->with('uranai', $uranai)
            ->with('appUrl', $appUrl)
            ->with('timeTable', $timeTable);
    }

    public function edit($dispdate = null)
    {
        list($year, $month, $day) = explode("-", $dispdate);

        $table = "t_article" . $year;

        if ($this->user == "hidechy") {
            $result = DB::table($table)
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->orderBy('num')
                ->get();
        } else {
            $result = DB::table($table)
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->where('hide', '=', 0)
                ->orderBy('num')
                ->get();
        }

        $data = [];
        if (isset($result[0])) {
            foreach ($result as $k => $v) {
                $data[] = $v->article;
            }
        }

        return view('article.edit')
            ->with('dispdate', $dispdate)
            ->with('data', $data);
    }

    public function confirm()
    {
        list($year, $month, $day) = explode("-", $_POST['date']);

        $ex_article = explode("\n", $_POST['article']);

        $data = [];
        if (!empty($ex_article)) {

            $ary = [];
            $i = 0;
            foreach ($ex_article as $v) {
                if (trim($v) == "") {
                    continue;
                }

                if ((trim($v) == "@") and (strlen(trim($v)) == 1)) {
                    $i++;
                    continue;
                }

                if ((trim($v) == "#") and (strlen(trim($v)) == 1)) {
                    $ary[$i][] = "[" . date("H:i") . "]";
                } else {
                    $ary[$i][] = $v;
                }
            }

            if (!empty($ary)) {
                foreach ($ary as $k => $v) {
                    $data[$k]['year'] = $year;
                    $data[$k]['month'] = $month;
                    $data[$k]['day'] = $day;
                    $data[$k]['num'] = $k;
                    $data[$k]['article'] = implode("\n", $v);
                }
            }
        }

        //-----------------------------------------//
        $tag = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    $tag[] = trim($v);
                }
            }
        }
        //-----------------------------------------//

        $tagval = [];
        $table = "t_article" . $year;
        $result = DB::table($table)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->where('tag', '!=', '')
            ->get(['article', 'tag']);

        if (isset($result[0])) {
            foreach ($result as $v) {
                $ex_article = explode("\n", $v->article);
                $tagval[trim($ex_article[0])] = $v->tag;
            }
        }

        return view('article.confirm')
            ->with('data', $data)
            ->with('user', $this->user)
            ->with('tag', $tag)
            ->with('input_year', $year)
            ->with('input_month', $month)
            ->with('input_day', $day)
            ->with('tagval', $tagval);
    }

    public function input()
    {
        DB::beginTransaction();

        try {
            $table = "t_article" . $_POST['input_year'];

            if ($this->user == "hidechy") {
                DB::table($table)
                    ->where('year', '=', $_POST['input_year'])
                    ->where('month', '=', $_POST['input_month'])
                    ->where('day', '=', $_POST['input_day'])
                    ->delete();
            } else {
                DB::table($table)
                    ->where('year', '=', $_POST['input_year'])
                    ->where('month', '=', $_POST['input_month'])
                    ->where('day', '=', $_POST['input_day'])
                    ->where('hide', '=', 0)
                    ->delete();
            }

            DB::statement('ALTER TABLE ' . $table . ' AUTO_INCREMENT = 1;');

            //---------------------------------------------//
            if (isset($_POST['article'])) {
                $data = [];

                $ary1 = [];

                for ($i = 0; $i < 100; $i++) {
                    if (isset($_POST['del'][$i])) {
                        continue;
                    }

                    if (!isset($_POST['article'][$i])) {
                        continue;
                    }

                    $tmp = [];

                    if (!empty($_POST['move_date'][$i])) {
                        list($mYear, $mMonth, $mDay) = explode("-", trim($_POST['move_date'][$i]));
                        $tmp[0] = $mYear;
                        $tmp[1] = $mMonth;
                        $tmp[2] = $mDay;
                    } else {
                        $tmp[0] = $_POST['input_year'];
                        $tmp[1] = $_POST['input_month'];
                        $tmp[2] = $_POST['input_day'];
                    }

                    $tmp[3] = (isset($_POST['hide'][$i])) ? 1 : 0;

                    $tag = "";
                    if (!empty($_POST['tag_select'][$i])) {
                        $tag = $_POST['tag_select'][$i];
                    } else if (!empty($_POST['tag_text'][$i])) {
                        $tag = $_POST['tag_text'][$i];
                    }
                    $tmp[4] = $tag;

                    $tmp[5] = trim($_POST['article'][$i]);

                    $ary1[$_POST['num'][$i]][] = implode(";", $tmp);
                }

                if (!empty($ary1)) {
                    $maxNum = [];
                    if (!empty($_POST['move_date'])) {
                        foreach ($_POST['move_date'] as $v) {
                            if (!empty($v)) {
                                list($mYear, $mMonth, $mDay) = explode("-", $v);
                                $result = DB::table('t_article' . $mYear)
                                    ->where('year', '=', $mYear)
                                    ->where('month', '=', $mMonth)
                                    ->where('day', '=', $mDay)
                                    ->orderBy('num', 'desc')
                                    ->take(1)
                                    ->get(['num']);
                                $maxNum[$v] = (isset($result[0])) ? $result[0]->num : 0;
                            }
                        }
                    }

                    $maxKey = max(array_keys($ary1));

                    $j = 0;
                    $l = 0;
                    for ($i = 0; $i <= $maxKey; $i++) {
                        if (isset($ary1[$i])) {
                            foreach ($ary1[$i] as $v) {
                                list($year, $month, $day, $hide, $tag, $article) = explode(";", $v);
                                $data[$j]['year'] = $year;
                                $data[$j]['month'] = $month;
                                $data[$j]['day'] = $day;
                                $data[$j]['hide'] = $hide;
                                $data[$j]['tag'] = $tag;
                                $data[$j]['article'] = $article;

                                if (isset($maxNum[$year . "-" . $month . "-" . $day])) {
                                    $data[$j]['num'] = ($maxNum[$year . "-" . $month . "-" . $day] + $l);
                                    $l++;
                                } else {
                                    $data[$j]['num'] = $j;
                                }

                                $data[$j]['created_at'] = date("Y-m-d H:i:s");
                                $data[$j]['updated_at'] = date("Y-m-d H:i:s");
                                $j++;
                            }
                        }
                    }

                    //-----------------------------------------------------------// copy
                    if (isset($_POST['copy_add_cnt'])) {
                        $dataCnt = count($data);
                        $addCnt = 0;
                        $otherYearData = [];
                        $otherCnt = 0;
                        foreach ($_POST['copy_add_cnt'] as $order => $cnt) {
                            if (trim($cnt) != "" and $cnt > 0) {
                                for ($i = 1; $i <= $cnt; $i++) {
                                    $copyDate_y = date("Y", (strtotime($data[$order]['year'] . "-" . $data[$order]['month'] . "-" . $data[$order]['day']) + (86400 * $i)));
                                    $copyDate_m = date("m", (strtotime($data[$order]['year'] . "-" . $data[$order]['month'] . "-" . $data[$order]['day']) + (86400 * $i)));
                                    $copyDate_d = date("d", (strtotime($data[$order]['year'] . "-" . $data[$order]['month'] . "-" . $data[$order]['day']) + (86400 * $i)));

                                    $result = DB::table('t_article' . $copyDate_y)
                                        ->where('year', '=', $copyDate_y)
                                        ->where('month', '=', $copyDate_m)
                                        ->where('day', '=', $copyDate_d)
                                        ->get(['id']);

                                    if ($_POST['input_year'] == $copyDate_y) {
                                        $data[$dataCnt + $addCnt]['year'] = $copyDate_y;
                                        $data[$dataCnt + $addCnt]['month'] = $copyDate_m;
                                        $data[$dataCnt + $addCnt]['day'] = $copyDate_d;
                                        $data[$dataCnt + $addCnt]['hide'] = $data[$order]['hide'];
                                        $data[$dataCnt + $addCnt]['tag'] = $data[$order]['tag'];
                                        $data[$dataCnt + $addCnt]['article'] = $data[$order]['article'];
                                        $data[$dataCnt + $addCnt]['num'] = (isset($result[0])) ? count($result) : 0;
                                        $data[$dataCnt + $addCnt]['created_at'] = $data[$order]['created_at'];
                                        $data[$dataCnt + $addCnt]['updated_at'] = $data[$order]['updated_at'];
                                        $addCnt++;
                                    } else {
                                        $ary = [];
                                        $ary['year'] = $copyDate_y;
                                        $ary['month'] = $copyDate_m;
                                        $ary['day'] = $copyDate_d;
                                        $ary['hide'] = $data[$order]['hide'];
                                        $ary['tag'] = $data[$order]['tag'];
                                        $ary['article'] = $data[$order]['article'];
                                        $ary['num'] = (isset($result[0])) ? count($result) : 0;
                                        $ary['created_at'] = $data[$order]['created_at'];
                                        $ary['updated_at'] = $data[$order]['updated_at'];
                                        $otherYearData[$copyDate_y][$otherCnt] = $ary;
                                        $otherCnt++;
                                    }
                                }
                            }
                        }
                    }
                    //-----------------------------------------------------------// copy

                    //>>>>>>>>>// 別年への記事移動
                    $keep_data = $data;
                    $data = [];

                    $AnotherYearData = [];

                    preg_match("/t_article(.+)/", $table, $m);
                    list(, $BaseYear) = $m;

                    foreach ($keep_data as $__data_no => $__data) {
                        if ($__data['year'] == $BaseYear) {
                            $data[$__data_no] = $__data;
                        } else {
                            $AnotherYearData[$__data['year']][$__data_no] = $__data;
                        }
                    }
                    //>>>>>>>>>// 別年への記事移動

                    DB::table($table)->insert($data);

                    //>>>>>>>>>// 別年への記事移動
                    if (!empty($AnotherYearData)) {
                        foreach ($AnotherYearData as $aYear => $aData) {
                            $aTable = "t_article" . $aYear;
                            DB::table($aTable)->insert($aData);
                        }
                    }
                    //>>>>>>>>>// 別年への記事移動

                    if (!empty($otherYearData)) {
                        foreach ($otherYearData as $oYear => $v) {
                            $oTable = "t_article" . $oYear;
                            foreach ($v as $v2) {
                                DB::table($oTable)->insert($v2);
                            }
                        }
                    }

                    /////////////////////////////////////////////////////////
                    if (isset($_POST['tag_text'])) {
                        $tag_ = [];

                        $file = public_path() . "/mySetting/tag.data";
                        $content = file_get_contents($file);

                        if (!empty($content)) {
                            $ex_content = explode("\n", $content);
                            if (!empty($ex_content)) {
                                foreach ($ex_content as $v) {
                                    if (trim($v) == "") {
                                        continue;
                                    }
                                    $tag_[trim($v)] = "";
                                }
                            }
                        }

                        foreach ($_POST['tag_text'] as $v) {
                            $tag_[trim($v)] = "";
                        }

                        $tag = array_keys($tag_);
                        sort($tag);

                        file_put_contents($file, implode("\n", $tag));
                    }
                    /////////////////////////////////////////////////////////
                }
            }
            //---------------------------------------------//

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        return redirect("/article/" . $_POST['input_year'] . "-" . $_POST['input_month'] . "-" . $_POST['input_day'] . "/display");
    }

    public function datejump()
    {
        return redirect("/article/" . $_POST['jumpdate'] . "/display");
    }

    public function search()
    {
        /////////////////////////////////////////////////////////
        $tag_article = [];

        $ary1 = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $articleTables = $this->Utility->getArticleTable();

                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    foreach ($articleTables as $table) {
                        $result = DB::table($table)
                            ->where('tag', '=', trim($v))
                            ->get(['id']);

                        if (isset($result[0])) {
                            $ary1[trim($v)][] = count($result);
                        } else {
                            $ary1[trim($v)][] = 0;
                        }
                    }
                }
            }
        }

        if (!empty($ary1)) {
            foreach ($ary1 as $tag => $v) {
                $tag_article[$tag] = array_sum($v);
            }
        }
        /////////////////////////////////////////////////////////

        return view('article.search')
            ->with('tag_article', $tag_article);
    }

    public function searchresult($word = null)
    {
        $articleTables = $this->Utility->getArticleTable();
        $data = [];

        $__word = "";
        if (isset($word)) {
            //タグ検索
            $i = 0;
            foreach ($articleTables as $table) {
                $result = DB::table($table)
                    ->where('tag', '=', $word)
                    ->orderBy('year')
                    ->orderBy('month')
                    ->orderBy('day')
                    ->get(['year', 'month', 'day', 'article', 'num']);

                if (isset($result[0])) {
                    foreach ($result as $v) {
                        $data[$word][$i]['year'] = $v->year;
                        $data[$word][$i]['month'] = $v->month;
                        $data[$word][$i]['day'] = $v->day;
                        $data[$word][$i]['article'] = $v->article;
                        $data[$word][$i]['num'] = $v->num;
                        $i++;
                    }
                }
            }

            $__word = trim($word);
        } else {
            //ワード検索
            if (isset($_POST['word'])) {
                $ex_word = explode(";", strtr($_POST['word'], ['　' => ';', ' ' => ';']));
                if (!empty($ex_word)) {
                    $i = 0;
                    foreach ($ex_word as $v) {
                        $search_word = "%" . trim($v) . "%";
                        foreach ($articleTables as $table) {
                            $result = DB::table($table)
                                ->where('article', 'like', $search_word)
                                ->orderBy('year')
                                ->orderBy('month')
                                ->orderBy('day')
                                ->get(['year', 'month', 'day', 'article', 'num']);

                            if (isset($result[0])) {
                                foreach ($result as $v2) {
                                    $data[$v][$i]['year'] = $v2->year;
                                    $data[$v][$i]['month'] = $v2->month;
                                    $data[$v][$i]['day'] = $v2->day;
                                    $data[$v][$i]['article'] = $v2->article;
                                    $data[$v][$i]['num'] = $v2->num;
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }

            $__word = trim($_POST['word']);
        }

        return view('article.searchresult')
            ->with('data', $data)
            ->with('searchword', $__word);
    }

    public function photo($dispdate = null)
    {
        $folderPhoto = [];
        if (!empty($dispdate)) {
            list($year, $month, $day) = explode("-", $dispdate);

            $folderPath = public_path() . "/UPPHOTO";
            $folderPath .= "/" . $year;
            $folderPath .= "/" . $dispdate;

            $folderPhoto = $this->Utility->getFolderPhoto($folderPath);
            sort($folderPhoto);
        }

        //////////////////////////////////////////////////////////////
        $allPhoto = $this->Utility->getFolderPhoto();
        $photoDate = [];
        foreach ($allPhoto as $year => $v) {
            foreach ($v as $date => $v2) {
                $photoDate[] = $date;
            }
        }

        $prevDate = "";
        $nextDate = "";

        foreach ($photoDate as $date) {
            if (strtotime($date) < strtotime($dispdate)) {
                $prevDate = $date;
            }
        }

        $reverse = $photoDate;
        rsort($reverse);

        foreach ($reverse as $date) {
            if (strtotime($date) > strtotime($dispdate)) {
                $nextDate = $date;
            }
        }
        //////////////////////////////////////////////////////////////

        return view('article.photo')
            ->with('dispdate', $dispdate)
            ->with('folderPath', $folderPath)
            ->with('folderPhoto', $folderPhoto)
            ->with('photoDate', $photoDate)
            ->with('prevDate', $prevDate)
            ->with('nextDate', $nextDate);
    }

    public function photoupload()
    {
        /////////////////////////////////////////////////////////
        list($year, $month, $day) = explode("-", $_POST['dispdate']);

        $folderPath = public_path() . "/UPPHOTO";
        $folderPath .= "/" . $year;

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777);
            chmod($folderPath, 0777);
        }

        $folderPath .= "/" . $_POST['dispdate'];

        $fileCnt = 0;
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777);
            chmod($folderPath, 0777);
        } else {
            $folderPhoto = $this->Utility->getFolderPhoto($folderPath);
            $fileCnt = count($folderPhoto);
        }

        $image = Input::file('image');

        foreach ($image as $k => $v) {
            $extension = $v->getClientOriginalExtension();
            $fileName = $_POST['dispdate'] . "_" . sprintf('%03d', ($fileCnt + $k + 1)) . "_l." . $extension;

            $move = $v->move($folderPath, $fileName);
        }
        /////////////////////////////////////////////////////////

        if (isset($_POST['return_here'])) {
            return redirect("/article/" . $_POST['dispdate'] . "/photo");
        } else {
            return redirect("/article/" . $_POST['dispdate'] . "/display");
        }
    }

    public function photojump()
    {
        if (trim($_POST['tb_jumpdate']) == "") {
            return redirect("/article/" . $_POST['jumpdate'] . "/photo");
        } else {
            return redirect("/article/" . $_POST['tb_jumpdate'] . "/photo");
        }
    }

    public function photoedit()
    {
        $Command = [];

        list($date, , $extension) = explode("_", $_POST['photoName'][0]);
        list($year) = explode("-", $date);

        $photoDir = "/var/www/html/BrainLog/public/UPPHOTO";
        $folderPath = $photoDir . "/" . $year . "/" . $date;

        $_deleteKeys = (isset($_POST['photoDelete'])) ? array_keys($_POST['photoDelete']) : [];

        if (!empty($_deleteKeys)) {
            foreach ($_deleteKeys as $v) {
                $Command[] = "rm -rf " . $folderPath . "/" . $_POST['photoName'][$v];
            }
        }

        $_order = [];
        if (isset($_POST['photoOrder'])) {
            foreach ($_POST['photoOrder'] as $k => $v) {

                if (in_array($k, $_deleteKeys, true)) {
                    continue;
                }

                if (trim($v) != "") {
                    $_order[$v][] = $k;
                } else {
                    $_order[999][] = $k;
                }
            }
        }

        $_order2 = [];

        if (!empty($_order)) {
            foreach ($_order as $num => $v) {
                if ($num == 999) {
                    continue;
                }
                foreach ($v as $ord) {
                    $_order2[] = $ord;
                }
            }
        }

        if (!empty($_order[999])) {
            foreach ($_order[999] as $ord) {
                $_order2[] = $ord;
            }
        }

        if (!empty($_order2)) {
            foreach ($_order2 as $newnum => $v) {
                $oldpath = $folderPath . "/" . $_POST['photoName'][$v];
                $newpath = $folderPath . "/" . $date . "_" . sprintf("%03d", $newnum) . "_" . $extension;

                $Command[] = "mv " . $oldpath . " " . $newpath;
            }
        }

        if (!empty($Command)) {
            foreach ($Command as $v) {
                system($v);
            }
        }

        return redirect("/article/" . $date . "/photo");
    }

    public function photochange()
    {
        preg_match("/UPPHOTO\/(.+)/", $_POST['src'], $m);
        list($year, $ymd, $thisFile) = explode("/", $m[1]);

        $photoDir = "/var/www/html/BrainLog/public/UPPHOTO/" . $year . "/" . $ymd;

        $photoList = $this->Utility->getFileList($photoDir);

        $fileNum = -1;
        foreach ($photoList as $k => $v) {
            if ($v == ($photoDir . "/" . $thisFile)) {
                $fileNum = $k;
                break;
            }
        }

        $pickNum = 0;
        switch ($_POST['flag']) {
            case "back":
                $pickNum = ($fileNum - 1);
                if ($pickNum < 0) {
                    $pickNum = 0;
                }
                break;
            case "next":
                $pickNum = ($fileNum + 1);
                if ($pickNum > (count($photoList) - 1)) {
                    $pickNum = (count($photoList) - 1);
                }
                break;
        }

        $photoPath = $photoList[$pickNum];

        echo strtr($photoPath, ['/var/www/html/' => 'http://toyohide.work/']);
    }

    public function photorotate()
    {
        $photoDir = "/var/www/html/BrainLog/public/UPPHOTO/{YEAR}/{YMD}";

        if (isset($_POST['photoDelete'])) {
            foreach ($_POST['photoDelete'] as $photonum => $v) {
                $photoName = $_POST['photoName'][$photonum];

                preg_match("/(.+)_[0-9]{3}_l/", $photoName, $m);
                list(, $YMD) = $m;
                list($YEAR, $month, $day) = explode("-", $YMD);

                $photoPath = strtr($photoDir, ['{YEAR}' => $YEAR, '{YMD}' => $YMD]);
                $photoPath .= "/" . $photoName;

                echo $photoPath;
                echo "<hr>";

                $exif_data = exif_read_data($photoPath);

                //print $exif_data['Orientation'];

                print_r($exif_data);

                echo "<hr>";

            }
        }
    }

    public function multiinput()
    {
        //-----------------------------------------//
        $tag = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    $tag[] = trim($v);
                }
            }
        }
        //-----------------------------------------//

        return view('article.multiinput')
            ->with('tag', $tag);
    }

    public function multiinsert()
    {
        if (!empty($_POST['insert_article'])) {

            foreach ($_POST['insert_article'] as $k => $v) {
                if (trim($v) == "") {
                    continue;
                }

                list($year, $month, $day) = explode("-", $_POST['insert_date'][$k]);

                $result = DB::table('t_article' . $year)
                    ->where('year', '=', $year)
                    ->where('month', '=', $month)
                    ->where('day', '=', $day)
                    ->get(['id']);

                $data = [];
                $data['year'] = $year;
                $data['month'] = $month;
                $data['day'] = $day;
                $data['tag'] = $_POST['insert_tag'][$k];
                $data['article'] = $_POST['insert_article'][$k];
                $data['num'] = (isset($result[0])) ? count($result) : 0;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] = date("Y-m-d H:i:s");

                DB::table('t_article' . $year)->insert($data);
            }
        }

        return redirect("/article/index");
    }

    public function future()
    {
        $ta = [];
        $result = DB::select("show tables;");
        foreach ($result as $v) {
            if (preg_match("/^t_article(.+)/", $v->Tables_in_brain, $m)) {
                if ($m[1] < date("Y")) {
                    continue;
                }
                $ta[$v->Tables_in_brain] = "";
            }
        }
        $table_article = array_keys($ta);

        sort($table_article);

        $article = [];
        foreach ($table_article as $v) {
            preg_match("/^t_article(.+)/", $v, $m);

            if ($m[1] == date("Y")) {
                $result = DB::table($v)
                    ->orderBy('year')
                    ->orderBy('month')
                    ->orderBy('day')
                    ->get();

                foreach ($result as $v2) {
                    if (strtotime($v2->year . "-" . $v2->month . "-" . $v2->day) >= strtotime(date("Y-m-d"))) {
                        $article[$v2->year . "-" . $v2->month . "-" . $v2->day][] = $v2->article;
                    }
                }
            } else {
                $result = DB::table($v)
                    ->orderBy('year')
                    ->orderBy('month')
                    ->orderBy('day')
                    ->get();

                foreach ($result as $v2) {
                    $article[$v2->year . "-" . $v2->month . "-" . $v2->day][] = $v2->article;
                }
            }
        }

        return view('article.future')
            ->with('article', $article);
    }

    public function taging($yearmonth)
    {

        //------------------------------------------------//
        $articleY = [];
        $result = DB::select("show tables;");
        foreach ($result as $v) {
            if (preg_match("/^t_article([0-9]{4})/", $v->Tables_in_brain, $m)) {
                $articleY[] = $m[1];
            }
        }
        sort($articleY);
        //print_r($articleY);

        $startYM = $articleY[0] . "-01";
        $endYM = $articleY[count($articleY) - 1] . "-12";
        //var_dump($yearmonth);
        //var_dump($startYM);
        //var_dump($endYM);

        $prevMonth = "0";
        $prev = date('Y-m', strtotime(($yearmonth . "-01") . "-1 month"));
        if (strtotime($startYM . "-01") <= strtotime($prev)) {
            $prevMonth = $prev;
        }
        //var_dump($prevMonth);

        $nextMonth = "0";
        $next = date('Y-m', strtotime(($yearmonth . "-01") . "+1 month"));
        if (strtotime($next) <= strtotime($endYM . "-01")) {
            $nextMonth = $next;
        }
        //var_dump($nextMonth);
        //------------------------------------------------//

        $monthEnd = date("t", strtotime($yearmonth . "-01"));
        list($year, $month) = explode("-", $yearmonth);

        $data = [];
        for ($i = 1; $i <= $monthEnd; $i++) {

            $SQL = " select article , tag , num from t_article" . $year . " where year = '" . $year . "' and month = '" . $month . "' and day = '" . sprintf("%02d", $i) . "' order by num; ";
            $result = DB::select($SQL);

            foreach ($result as $v) {
                $data[$yearmonth . "-" . sprintf("%02d", $i)][$v->num]['article'] = $v->article;
                $data[$yearmonth . "-" . sprintf("%02d", $i)][$v->num]['tag'] = $v->tag;
            }
        }

        //-----------------------------------------//
        $tag = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }
                    $tag[] = trim($v);
                }
            }
        }
        //-----------------------------------------//

        return view('article.taging')
            ->with('yearmonth', $yearmonth)
            ->with('data', $data)
            ->with('tag', $tag)
            ->with('prevMonth', $prevMonth)
            ->with('nextMonth', $nextMonth);
    }

    public function taginput()
    {

        if (isset($_POST['tag'])) {
            foreach ($_POST['tag'] as $date => $v) {
                list($year, $month, $day) = explode("-", $date);
                foreach ($v as $num => $tag) {

                    $update = [];
                    $update['tag'] = $tag;

                    DB::table('t_article' . $year)
                        ->where('year', '=', $year)
                        ->where('month', '=', $month)
                        ->where('day', '=', $day)
                        ->where('num', '=', $num)
                        ->update($update);
                }
            }
            return redirect("/article/" . $year . "-" . $month . "/taging");
        }
    }

    public function articlemerge()
    {
        list($after_year, $after_month, $after_day) = explode("-", $_POST['merge_date']);
        $after_table = "t_article" . $after_year;
        $result = DB::table($after_table)
            ->where('year', '=', $after_year)
            ->where('month', '=', $after_month)
            ->where('day', '=', $after_day)
            ->orderBy('num', 'desc')
            ->take(1)
            ->get(['num']);

        $max_num = (isset($result[0])) ? $result[0]->num : 0;

        $ex_mergedata = explode("/", $_POST['merge_data']);
        foreach ($ex_mergedata as $k => $v) {
            list($before_date, $before_num) = explode(":", $v);
            list($before_year, $before_month, $before_day) = explode("-", $before_date);
            $before_table = "t_article" . $before_year;

            $result2 = DB::table($before_table)
                ->where('year', '=', $before_year)
                ->where('month', '=', $before_month)
                ->where('day', '=', $before_day)
                ->where('num', '=', $before_num)
                ->get(['id', 'article']);

            if ($before_table != $after_table) {
                //テーブル移動が必要な場合

                $insert = [];
                $insert['year'] = $after_year;
                $insert['month'] = $after_month;
                $insert['day'] = $after_day;
                $insert['num'] = ($max_num + $k + 1);
                $insert['article'] = trim($result2[0]->article);
                DB::table($after_table)->insert($insert);

                DB::table($before_table)
                    ->where('id', '=', $result2[0]->id)
                    ->delete();
            } else {
                //日付移動だけの場合
                $update = [];
                $update['year'] = $after_year;
                $update['month'] = $after_month;
                $update['day'] = $after_day;
                $update['num'] = ($max_num + $k + 1);
                DB::table($after_table)
                    ->where('id', '=', $result2[0]->id)
                    ->update($update);
            }
        }

        return redirect("/article/" . $_POST['merge_date'] . "/display");
    }


    public function articledelete()
    {

        $ex_mergedata = explode("/", $_POST['merge_data']);
        foreach ($ex_mergedata as $k => $v) {
            list($del_date, $del_num) = explode(":", $v);
            list($del_year, $del_month, $del_day) = explode("-", $del_date);
            $del_table = "t_article" . $del_year;

            DB::table($del_table)
                ->where('year', '=', $del_year)
                ->where('month', '=', $del_month)
                ->where('day', '=', $del_day)
                ->where('num', '=', $del_num)
                ->delete();
        }

        return redirect("/article/search");
    }


    public function traindateapi()
    {
        $articleTables = $this->Utility->getArticleTable();

        $traindata = [];

        foreach ($articleTables as $table) {
            $result = DB::table($table)
                ->where('tag', '=', '電車乗車')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get(['year', 'month', 'day', 'article', 'num']);

            foreach ($result as $v) {
                $_traindata[$v->year . "-" . $v->month . "-" . $v->day] = "";
            }
        }

        $__traindata = array_keys($_traindata);
        sort($__traindata);

        foreach ($__traindata as $date) {
            if (strtotime($date) >= strtotime("2019-10-01")) {
                $traindata['data'][] = ['date' => $date];
            }
        }

        echo json_encode($traindata);
    }


    public function trainmonthdataapi($yearmonth)
    {
        list($year, $month, $day) = explode("-", $yearmonth);

        $table = "t_article" . $year;

        $result = DB::table($table)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('tag', '=', '電車乗車')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get(['year', 'month', 'day', 'article']);

        $traindata = [];

        $i = 0;
        foreach ($result as $v) {
            $traindata['data'][$i]['date'] = $v->year . "-" . $v->month . "-" . $v->day;
            $traindata['data'][$i]['article'] = $v->article;
            $i++;
        }

        //print_r($traindata);


        echo json_encode($traindata);
    }


    public function traindataapi($ymd)
    {
        list($year, $month, $day) = explode("-", $ymd);

        $table = "t_article" . $year;

        $result = DB::table($table)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->where('tag', '=', '電車乗車')
            ->get(['article']);

        $traindata['data']['article'] = "";
        if (isset($result[0])) {
            $ex_article = explode("\n", $result[0]->article);
            $traindata['data']['article'] = $ex_article;
        }

        //print_r($traindata);
        echo json_encode($traindata);
    }


    public function kotowazaapi()
    {

        //-----------------------------------------//
        $kotowaza = [];

        $file = public_path() . "/mySetting/kotowaza.data";
        $content = file_get_contents($file);

        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                $p = 0;
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    list($word, $mean) = explode("|", trim($v));

                    $kotowaza['data'][$p]['no'] = $p;
                    $kotowaza['data'][$p]['word'] = $word;
                    $kotowaza['data'][$p]['mean'] = $mean;

                    $p++;
                }
            }
        }
        //-----------------------------------------//

        echo json_encode($kotowaza);
    }

}
