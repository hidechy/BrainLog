<?php

namespace App\Http\Controllers\Other;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MyClass\Utility;

class OtherController extends Controller
{

    var $Utility;
    function __construct() {

$sql = "SET sql_mode = '';";
DB::statement($sql);

        $this->Utility = new Utility;
    }



    public function tuning()
    {
        return view('other.tuning');
    }



    public function holiday()
    {
        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf8" , "sjis-win"));
        foreach ($ex_content as $v){
            if (trim($v) == ""){continue;}
            $holiday[] = trim($v);
        }
        sort($holiday);
        //------------------//

        return view('other.holiday')
            ->with('holiday' , $holiday);
    }



    public function holidayinput()
    {
        $file = public_path() . "/mySetting/holiday.data";
        file_put_contents($file , $_POST['holiday']);
        return redirect('/article/index');
    }



    public function user()
    {
        $user = "";

        $file = public_path() . "/mySetting/user.data";
        $content = file_get_contents($file);

        if (!empty($content)){
            $user = mb_convert_encoding($content , "utf8" , "sjis-win");
        }

        return view('other.user')
            ->with('user' , $user);
    }



    public function userinput()
    {
        $file = public_path() . "/mySetting/user.data";
        file_put_contents($file , $_POST['user']);
        return redirect('/article/index');
    }



    public function weather()
    {
        $open_url = "http://www.jma.go.jp/jp/week/319.html";
        $content = file_get_contents ( $open_url );
        $ex_content = explode ( "\n", $content );

        $a = 0;
        $b = 0;
        foreach ( $ex_content as $k => $v ) {
            if (preg_match ( "/日付/", trim ( $v ) )) {$a = $k;}

            if (preg_match ( "/降水確率/", trim ( $v ) )) {
                $b = $k;
                break;
            }
        }

        $AAA = "";
        for($i = $a; $i < $b; $i ++) {$AAA .= trim ( $ex_content [$i] );}

        $ex_AAA = explode ( "tr", $AAA );

        $date = array ();
        $ex_AAA0 = explode ( "|", strtr ( $ex_AAA [0], array (
        "><" => ">|<",
        "> <" => ">|<"
        ) ) );

        for($i = 1; $i <= 7; $i ++) {
        $date [$i - 1] = trim ( strip_tags ( $ex_AAA0 [$i] ) );
        }

        $weather = array ();
        $ex_AAA2 = explode ( "|", strtr ( $ex_AAA [2], array (
        "><" => ">|<",
        "> <" => ">|<"
        ) ) );

        foreach ( $ex_AAA2 as $v ) {
            if (preg_match ( "/<td/", trim ( $v ) )) {
                $weather [] = trim ( strip_tags ( $v ) );
            }
        }

        if (count ( $date ) == count ( $weather )) {
            $DDD = array ();

            $open_url = public_path() . "/mySetting/weather.data";

            if (file_exists ( $open_url )) {
                $content = file_get_contents ( $open_url );
                $ex_content = explode ( "\n", $content );

                foreach ( $ex_content as $v ) {
                    if (trim ( $v ) == "") {continue;}
                    $ex_v = explode ( "|", trim ( $v ) );
                    $DDD [trim ( $ex_v [0] )] = trim ( $ex_v [1] );
                }
            }

            foreach ( $weather as $k => $v ) {
                if (trim ( $v ) == "") {continue;}
                $DDD [date ( "Y-m-d", strtotime(date("Y-m-d")) + (86400 * $k) )] = $v;
            }

            ksort ( $DDD );

            $EEE = array ();
            foreach ( $DDD as $k => $v ) {
                if (trim ( $v ) == "") {continue;}
                $EEE [] = $k . "|" . $v;
            }

            file_put_contents ( $open_url, implode ( "\n", $EEE ) );

            @chmod ( $open_url, 0777 );
        }

        return redirect('/article/index');
    }



    public function tag()
    {
        $tag = [];

        $file = public_path() . "/mySetting/tag.data";
        $content = file_get_contents($file);

        if (!empty($content)){
            $ex_content = explode("\n" , $content);
            if (!empty($ex_content)){
                foreach ($ex_content as $v){
                    if (trim($v) == ""){continue;}
                    $tag[] = trim($v);
                }
            }
        }

        return view('other.tag')
            ->with('tag' , $tag);
    }



    public function taginput()
    {
        $file = public_path() . "/mySetting/tag.data";
        file_put_contents($file , $_POST['tag']);
        return redirect('/article/index');
    }



    public function seiyuu()
    {
        $data = [];

        $cols = ['itemid' , 'itemname' , 'price' , 'url' , 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)){
            $ex_content = explode("\n" , $content);
            if (!empty($ex_content)){
                $i=0;
                foreach ($ex_content as $v){
                    if (trim($v) == ""){continue;}

                    $ex_v = explode("|" , trim($v));

                    foreach ($cols as $k2=>$v2){
                        if (!isset($ex_v[$k2])){continue;}
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }

        for ($j=$i ; $j<($i+3) ; $j++){
            $data[$j]['itemname'] = "";
            $data[$j]['url'] = "";
        }

        return view('other.seiyuu')
            ->with('data' , $data);
    }



    public function seiyuuinput()
    {
        $data = [];

        ////////////////////////////////
        $cols = ['itemid' , 'itemname' , 'price' , 'url' , 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)){
            $ex_content = explode("\n" , $content);
            if (!empty($ex_content)){
                $i=0;
                foreach ($ex_content as $k=>$v){
                    if (trim($v) == ""){continue;}

                    //削除
                    if (!empty($_POST['delete'][$k])){continue;}

                    $ex_v = explode("|" , trim($v));

                    foreach ($cols as $k2=>$v2){
                        if (!isset($ex_v[$k2])){continue;}
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }
        ////////////////////////////////

        if (!empty($_POST['url'])){
            foreach ($_POST['url'] as $k=>$v){
                if (trim($v) == ""){continue;}
                $data[$k][0] = "";
                $data[$k][1] = "";
                $data[$k][2] = "";
                $data[$k][3] = $v;
            }
        }

        $out = [];
        foreach ($data as $v){$out[] = implode("|" , $v);}

        file_put_contents($file , implode("\n" , $out));

        return redirect('/other/seiyuu');
    }



    public function seiyuuarticle()
    {
        //---------//
        $article = $_POST['article'];
        sort($article);
        //---------//

        $data = [];

        $cols = ['itemid' , 'itemname' , 'price' , 'url' , 'img'];

        $file = public_path() . "/mySetting/seiyuu.data";
        $content = file_get_contents($file);

        if (!empty($content)){
            $ex_content = explode("\n" , $content);
            if (!empty($ex_content)){
                $i=0;
                foreach ($ex_content as $k=>$v){
                    if (trim($v) == ""){continue;}

                    $ex_v = explode("|" , trim($v));

                    foreach ($cols as $k2=>$v2){
                        if (!isset($ex_v[$k2])){continue;}
                        $data[$i][$v2] = $ex_v[$k2];
                    }

                    $i++;
                }
            }
        }

        $data2 = [];
        $i=0;
        $item_base = "{ITEMNAME}　{PRICE}円";
        foreach ($data as $v){
            if (in_array($v['itemid'] , $article , true)){
                $data2[$i] = strtr($item_base , ['{ITEMNAME}' => $v['itemname'] , '{PRICE}' => $v['price']]);
                $i++;
            }
        }

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        $result = DB::table('t_article' . date("Y"))->where('year' , '=' , date("Y"))->where('month' , '=' , date("m"))->where('day' , '=' , date("d"))->get(['id']);

        $insert = [];
        $insert['year'] = date("Y");
        $insert['month'] = date("m");
        $insert['day'] = date('d');
        $insert['num'] = (!empty($result)) ? count($result) : 0;
        $insert['article'] = implode("\n" , $data2);
        $insert['tag'] = "西友";
        $insert['created_at'] = date("Y-m-d");
        $insert['updated_at'] = date("Y-m-d");

        DB::table('t_article' . date("Y"))->insert($insert);
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        return redirect("/article/" . date("Y-m-d") . "/display");
    }



    public function work()
    {
        return view('other.work');
    }



    public function workinput()
    {
        if (!empty($_POST['work'])){
            $ex_work = explode("\n" , $_POST['work']);
            array_pop($ex_work);

            $YM = trim($ex_work[0]);
            $year = substr($YM , 0 , 4);
            $month = substr($YM , 4 , 2);

            $param = [];
            foreach ($ex_work as $k=>$v){
                if ($k == 0){continue;}
                if ($k == 1){continue;}

                $ex_value = explode("\t" , trim($v));
                $ary = [];
                $j=0;
                foreach ($ex_value as $v){
                    if (trim($v) != ""){
                        $ary[$j] = trim($v);
                        $j++;
                    }
                }

                $day = sprintf("%02d" , ($k - 1));
                $param[$year . "-" . $month . "-" . $day] = $ary;
            }

            if (!empty($param)){
                $insert = [];
                foreach ($param as $date=>$v){
                    if (empty($v)){continue;}

                    list($insert[$date]['year'] , $insert[$date]['month'] , $insert[$date]['day']) = explode("-" , $date);
                    $insert[$date]['work_start'] = $v[0];
                    $insert[$date]['work_end'] = $v[1];
                }

                DB::table('t_worktime')->insert($insert);
            }
        }

        return redirect("/article/index");
    }



    public function shokureki()
    {

        $color = [];
        $color['A'] = "#E60012";
        $color['B'] = "#EB6100";
        $color['C'] = "#F39800";
        $color['D'] = "#FCC800";
        $color['E'] = "#FFF100";
        $color['F'] = "#CFDB00";
        $color['G'] = "#8FC31F";
        $color['H'] = "#22AC38";
        $color['I'] = "#009944";
        $color['J'] = "#009B6B";
        $color['K'] = "#009E96";
        $color['L'] = "#00A0C1";
        $color['M'] = "#00A0E9";
        $color['N'] = "#0086D1";
        $color['O'] = "#0068B7";
        $color['P'] = "#00479D";
        $color['Q'] = "#1D2088";
        $color['R'] = "#601986";
        $color['S'] = "#920783";
        $color['T'] = "#BE0081";
        $color['U'] = "#E4007F";
        $color['V'] = "#E5006A";
        $color['W'] = "#E5004F";
        $color['X'] = "#E60033";

        $hanrei = "";
        $file = public_path() . "/mySetting/companyHistory.data";
        $content = file_get_contents($file);
        if (!empty($content)){
            $ex_content = explode("\n" , $content);
            if (!empty($ex_content)){
                $count = count($ex_content);
                $YM = [];
                $hanrei .= "<table class='dispTable'>";
                foreach ($ex_content as $k => $v){
                    if (trim($v) == ""){continue;}
                    list($flag , $term , $company) = explode("\t" , trim($v));
                    $bgColor = "background : " . $color[$flag] . ";";
                    $hanrei .= "<tr style='" . $bgColor . "'>";
                    $hanrei .= "<td>" . $flag . "</td>";
                    $hanrei .= "<td>" . $term . "</td>";
                    $hanrei .= "<td>" . $company . "</td>";
                    $hanrei .= "</tr>";
                    if ($k == ($count - 2)){$term .= date("Y/m");}
                    list($termStart , $termEnd) = explode(" - " , $term);
                    $startDate = strtr($termStart , ['/' => '']) . "01";
                    $endDate = strtr($termEnd , ['/' => '']) . "20";
                    for ($i=strtotime($startDate) ; $i<strtotime($endDate) ; $i+=86400){
                        $YM[$flag][date("Y" , $i)][date("m" , $i)] = "";
                    }
                }
                $hanrei .= "</table>";
            }
        }

        $param = [];
        foreach ($YM as $flag => $v){
            foreach ($v as $year => $v2){
                foreach ($v2 as $month => $v3){
                    $param[$year][$month] = $flag;
                }
            }
        }

        $param2 = [];
        for ($i=1999 ; $i <= date("Y") ; $i++){
            for ($j=1 ; $j<=12 ; $j++){
                if (isset($param[$i][sprintf("%02d" , $j)])){
                    $param2[$i][$j] = $param[$i][sprintf("%02d" , $j)];
                }else{
                    $param2[$i][$j] = "";
                }
            }
        }

        $data = "";
        $data .= "<table class='dispTable'>";
        for ($i=1999 ; $i <= date("Y") ; $i++){
            $data .= "<tr>";
            for ($j=1 ; $j<=12 ; $j++){
                $bgColor = (trim($param2[$i][$j]) != "") ? "background : " . $color[$param2[$i][$j]] . ";" : "";
                $data .= "<td style='" . $bgColor . "'>";
                $data .= "<div>" . $i . "-" . sprintf("%02d" , $j) . "</div>";
                $data .= "<div>";
                $data .= (trim($param2[$i][$j]) != "") ? $param2[$i][$j] : "<br>";
                $data .= "</div>";
                $data .= "</td>";
            }
            $data .= "</tr>";
        }
        $data .= "</table>";

        return view('other.shokureki')
            ->with('data' , $data)
            ->with('hanrei' , $hanrei);
    }



    public function souvenir(Request $request)
    {
        $upload = 0;
        if (isset($_POST['upload'])){
            $path_before = $_FILES["aaaaa"]["tmp_name"];
            $path_after = "/var/www/html/souvenir/" . $_FILES["aaaaa"]["name"];
            move_uploaded_file($path_before , $path_after);

            $upload = 1;
        }

        return view('other.souvenir')
            ->with('upload' , $upload);
    }



    public function kinmu($yearmonth = null)
    {
        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf8" , "sjis-win"));
        foreach ($ex_content as $v){
            if (trim($v) == ""){continue;}
            $holiday[] = strtr(trim($v) , ['-' => '']);
        }
        sort($holiday);
        //------------------//

        if (!empty($yearmonth)){
            list($gYear , $gMonth) = explode("-" , $yearmonth);
        }

        $thisMonthYear = (!empty($gYear)) ? $gYear : date("Y");
        $thisMonthMonth = (!empty($gMonth)) ? $gMonth : date("m");

        $thisMonthEnd = date("t" , strtotime($thisMonthYear . $thisMonthMonth));

        //------------------//
        $workTime = [];
        $table_exists = [];
        $result = DB::table('t_worktime')->where('year' , '=' , $thisMonthYear)->where('month' , '=' , $thisMonthMonth)->orderBy('day')->get(['year' , 'month' , 'day' , 'work_start' , 'work_end']);
        if (!empty($result)){
            foreach ($result as $v){
                $workTime[$v->year . $v->month . $v->day]['work_start'] = date("H:i" , strtotime($v->work_start));
                $workTime[$v->year . $v->month . $v->day]['work_end'] = date("H:i" , strtotime($v->work_end));

                if ((trim($v->work_start) != "") and (trim($v->work_end) != "")){
                    $table_exists[$v->year . $v->month . $v->day] = "";
                }
            }
        }
        //------------------//

        $youbi = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];

        $calender = "";
        $calender .= "<table border='0' cellspacing='2' cellpadding='2' id='tbl_calender'>";
        $ymdAry = [];
        $workShoukei = [];
        for ($i=1 ; $i<=$thisMonthEnd ; $i++){

           if (!checkdate($thisMonthMonth , $i , $thisMonthYear)){continue;}

            $Ymd = $thisMonthYear . $thisMonthMonth . sprintf("%02d" , $i);
            $ymdAry[] = $Ymd;

            $_youbi = date("w" , strtotime($Ymd));

            $defaultStart = "9:00";
            $defaultEnd = "18:00";
            $bgColorStyle = "";
            switch ($_youbi){
                case 0:
                    $defaultStart = "";
                    $defaultEnd = "";
                    $bgColorStyle = "background : #ffcccc;";
                    break;

                case 6:
                    $defaultStart = "";
                    $defaultEnd = "";
                    $bgColorStyle = "background : #ccccff;";
                    break;
            }

            if (in_array($Ymd , $holiday , true)){
                $defaultStart = "";
                $defaultEnd = "";
                $bgColorStyle = "background : #ffcccc;";
            }

            if (isset($workTime[$Ymd]['work_start'])){$defaultStart = $workTime[$Ymd]['work_start'];}
            if (isset($workTime[$Ymd]['work_end'])){$defaultEnd = $workTime[$Ymd]['work_end'];}

            $calender .= "<tr style='" . $bgColorStyle . "'>";
            $calender .= "<td>";
            $calender .= sprintf("%02d" , $i);
            if (isset($table_exists[$Ymd])){$calender .= "*";}
            $calender .= "</td>";
            $calender .= "<td>" . $youbi[$_youbi] . "</td>";

            $calender .= "<td id='StartTime_" . $Ymd . "' class='TimeTd'>";
                $calender .= "<div id='disp_StartTime_" . $Ymd . "'>" . $defaultStart . "</div>";
                $calender .= "<input type='hidden' id='value_StartTime_" . $Ymd . "' name='StartTime[" . $Ymd . "]' value='" . $defaultStart . "'>";
            $calender .= "</td>";

            $calender .= "<td id='EndTime_" . $Ymd . "' class='TimeTd'>";
                $calender .= "<div id='disp_EndTime_" . $Ymd . "'>" . $defaultEnd . "</div>";
                $calender .= "<input type='hidden' id='value_EndTime_" . $Ymd . "' name='EndTime[" . $Ymd . "]' value='" . $defaultEnd . "'>";
            $calender .= "</td>";

            $calender .= "</tr>";

            if (strtotime($defaultEnd) - strtotime($defaultStart) > 0){
                $workShoukei[$Ymd] = strtotime($defaultEnd) - strtotime($defaultStart);
            }
        }
        $calender .= "</table>";

        ///////////////////////////////////
        $target_day = $thisMonthYear . "-" . $thisMonthMonth . "-01";

        $prevMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") > strtotime("2014-10")){
            $prevMonth = date("Y-m" , strtotime($target_day . "-1 month"));
        }

        $nextMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") < strtotime(date("Y-m") . "-01")){
            $nextMonth = date("Y-m" , strtotime($target_day . "+1 month"));
        }
        ///////////////////////////////////

        $sum_work = (array_sum($workShoukei) / 60 / 60) - count($workShoukei);
        $ex_sw = explode("." , $sum_work);
        $ary = [];
        $ary[] = $ex_sw[0] . "時間";
        if (count($ex_sw) > 1){
            $tmp = $ex_sw[1] / 100 * 60;
            $ary[] = (strlen($tmp) == 1) ? $tmp . "0分" : $tmp . "分";
        }
        $totalWorkTime = implode("" , $ary);

        return view('other.kinmu')
            ->with('thisMonthYear' , $thisMonthYear)
            ->with('thisMonthMonth' , $thisMonthMonth)
            ->with('calender' , $calender)
            ->with('ymdStr' , implode("|" , $ymdAry))
            ->with('prevMonth' , $prevMonth)
            ->with('nextMonth' , $nextMonth)
            ->with('totalWorkTime' , $totalWorkTime);
    }



    public function kinmuinput()
    {
        $jumpYear = "";
        $jumpMonth = "";

        $workTime = [];
        foreach ($_POST['StartTime'] as $date=>$time){
            if (isset($_POST['EndTime'][$date])){
                if (strtotime($date) > strtotime(date("Ymd"))){continue;}

                //両方入っている場合のみ
                if (trim($_POST['StartTime'][$date]) != "" and trim($_POST['EndTime'][$date]) != ""){
                    $workTime[$date] = $_POST['StartTime'][$date] . "|" . $_POST['EndTime'][$date];

                    $year = substr($date , 0 , 4);
                    $month = substr($date , 4 , 2);

                    $jumpYear = $year;
                    $jumpMonth = $month;
                }
            }
        }

        //いったん削除
        DB::table('t_worktime')->where('year' , '=' , $jumpYear)->where('month' , '=' , $jumpMonth)->delete();
        DB::statement("alter table t_worktime auto_increment = 1;");

        if (!empty($workTime)){
            foreach ($workTime as $date=>$v){
                list($work_start , $work_end) = explode("|" , $v);

                $year = substr($date , 0 , 4);
                $month = substr($date , 4 , 2);
                $day = substr($date , 6 , 2);

                $insert = [];
                $insert['year'] = $year;
                $insert['month'] = $month;
                $insert['day'] = $day;
                $insert['work_start'] = $work_start;
                $insert['work_end'] = $work_end;

                DB::table('t_worktime')->insert($insert);
            }
        }

        return redirect("/other/" . $jumpYear . "-" . $jumpMonth . "/kinmu");
    }



}
