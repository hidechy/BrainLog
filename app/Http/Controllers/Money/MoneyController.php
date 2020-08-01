<?php

namespace App\Http\Controllers\Money;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;

class MoneyController extends Controller
{
    public $Utility;
    public function __construct()
    {
        $this->Utility = new Utility;
    }

    public function index($yearmonth = null)
    {
        if (!empty($yearmonth)) {
            list($gYear, $gMonth) = explode("-", $yearmonth);
        }

        $thisMonthYear = (!empty($gYear)) ? $gYear : date("Y");
        $thisMonthMonth = (!empty($gMonth)) ? $gMonth : date("m");

        $result = DB::table('t_money')->where('year', '=', $thisMonthYear)->where('month', '=', $thisMonthMonth)->orderBy('day')->get();

        $yAry = [];
        $yNum = [];
        $youbi = ['日', '月', '火', '水', '木', '金', '土'];
        $monthEnd = date("t", strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01"));
        for ($i = 1; $i <= $monthEnd; $i++) {
            $yAry[$thisMonthYear . "-" . $thisMonthMonth . "-" . sprintf("%02d", $i)] = $youbi[date("w", strtotime($thisMonthYear . "-" . $thisMonthMonth . "-" . $i))];
            $yNum[$thisMonthYear . "-" . $thisMonthMonth . "-" . sprintf("%02d", $i)] = date("w", strtotime($thisMonthYear . "-" . $thisMonthMonth . "-" . $i));
        }

        $column = [];
        $data = [];
        $bm_all = 0;

        if (isset($result[0])) {
            $beforeMonthEnd = date("Y-m-d", strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") - 1);

            $listData = $this->makeListData($result, $beforeMonthEnd);
            $column = $listData[0];
            $data = $listData[1];
            $bm_all = $listData[2];
        }

        ///////////////////////////////////
        $target_day = $thisMonthYear . "-" . $thisMonthMonth . "-01";

        $prevMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") > strtotime("2014-06")) {
            $prevMonth = date("Y-m", strtotime($target_day . "-1 month"));
        }

        $nextMonth = "0";
        if (strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01") < strtotime(date("Y-m") . "-01")) {
            $nextMonth = date("Y-m", strtotime($target_day . "+1 month"));
        }
        ///////////////////////////////////

        //------------------//
        $holiday = [];
        $file = public_path() . "/mySetting/holiday.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {continue;}
            $holiday[] = trim($v);
        }
        sort($holiday);
        //------------------//

        ////////////////////////////////////////////
        $_mt = [];
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    $_mt[$date] = $spend;
                }
            }
        }
        ////////////////////////////////////////////

        $DailySpend = "";
        $monthEnd = date("t", strtotime($thisMonthYear . "-" . $thisMonthMonth . "-01"));

        $DisplayKoumoku = [];
        $MoneyTotal = [];
        $lastDay = 0;
        for ($i = 1; $i <= $monthEnd; $i++) {
            $DailySpend .= "<div style='background: #ddffdd;'>" . sprintf("%02d", $i) . "</div>";

            if (isset($_mt[$thisMonthYear . '-' . $thisMonthMonth . '-' . sprintf("%02d", $i)])) {
                $DailySpend .= "<div>" . $_mt[$thisMonthYear . '-' . $thisMonthMonth . '-' . sprintf("%02d", $i)] . "</div>";

                $spent = [];
                $DailySpend .= "<table border='0' cellspacing='2' cellpadding='2'>";

                //----------------------------------------------------//
                $result3 = DB::table('t_salary')
                    ->where('year', '=', $thisMonthYear)
                    ->where('month', '=', $thisMonthMonth)
                    ->where('day', '=', sprintf("%02d", $i))
                    ->orderBy('id')
                    ->get();

                if (isset($result3)) {
                    foreach ($result3 as $v) {
                        $DailySpend .= "<tr><td>収入</td><td>" . $v->salary . "<td></tr>";
                        $spent[] = ($v->salary * -1);
                    }
                }
                //----------------------------------------------------//

                //----------------------------------------------------//
                $result = DB::table('t_dailyspend')
                    ->where('year', '=', $thisMonthYear)
                    ->where('month', '=', $thisMonthMonth)
                    ->where('day', '=', sprintf("%02d", $i))
                    ->orderBy('id')
                    ->get();

                if (isset($result)) {
                    foreach ($result as $v) {
                        $DailySpend .= "<tr><td>" . $v->koumoku . "</td><td>" . $v->price . "</td></tr>";
                        $spent[] = $v->price;
                        $DisplayKoumoku[$v->koumoku][] = $v->price;
                    }
                }
                //----------------------------------------------------//

                //----------------------------------------------------//
                $result2 = DB::table('t_credit')
                    ->where('year', '=', $thisMonthYear)
                    ->where('month', '=', $thisMonthMonth)
                    ->where('day', '=', sprintf("%02d", $i))
                    ->orderBy('id')
                    ->get();

                if (isset($result2)) {
                    foreach ($result2 as $v) {
                        $DailySpend .= "<tr><td>" . $v->item . "</td><td>" . $v->price . "<td></tr>";
                        $spent[] = $v->price;
                        $DisplayKoumoku[$v->item][] = $v->price;
                    }
                }
                //----------------------------------------------------//

                $DailySpend .= "</table>";

                $DailySpend .= "<div>" . ($_mt[$thisMonthYear . '-' . $thisMonthMonth . '-' . sprintf("%02d", $i)] - array_sum($spent)) . "</div>";

                $lastDay = $i;
            } else {
                $DailySpend .= "<div>0</div>";
            }
        }

        $_DisplayKoumoku = [];
        foreach ($DisplayKoumoku as $koumoku => $v) {
            $_DisplayKoumoku[$koumoku] = array_sum($v);
        }

        $thisMonthSpendTotal = array_sum($_DisplayKoumoku);

//2019年9月までの場合は表示しない
        $unix_thisMonth = strtotime($thisMonthYear . '-' . $thisMonthMonth . "-01");
        $unix_freelancestart = strtotime('2019-10-01');
        if ($unix_thisMonth < $unix_freelancestart) {
            $DailySpend = "";
            $_DisplayKoumoku = [];
        }

        $thisMonth = $thisMonthYear . "-" . $thisMonthMonth;

        return view('money.index')
            ->with('year', $thisMonthYear)
            ->with('month', $thisMonthMonth)
            ->with('column', $column)
            ->with('data', $data)
            ->with('bm_all', $bm_all)
            ->with('yAry', $yAry)
            ->with('yNum', $yNum)
            ->with('prevMonth', $prevMonth)
            ->with('nextMonth', $nextMonth)
            ->with('holiday', $holiday)
            ->with('DailySpend', $DailySpend)
            ->with('thisMonth', $thisMonth)
            ->with('DisplayKoumoku', $_DisplayKoumoku)
            ->with('lastDay', $lastDay)
            ->with('thisMonthSpendTotal', $thisMonthSpendTotal);
    }

    public function input()
    {
        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", strtotime($today) - 1);

        $oneBefore = DB::table('t_money')->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->take(1)->get();

        $ob_date = "";
        $ob_yen = [];

        $date_diff = ['today' => 0, 'yesterday' => 0];

        $ob_sum = 0;

        if (isset($oneBefore[0])) {

            //--------------------//
            $ob_date = $oneBefore[0]->year . "-" . $oneBefore[0]->month . "-" . $oneBefore[0]->day;

            $unix_today = strtotime($today);
            $unix_yesterday = strtotime($yesterday);
            $unix_onebefore = strtotime($ob_date);

            $date_diff['today'] = (($unix_today - $unix_onebefore) > 86400) ? 1 : 0;
            $date_diff['yesterday'] = (($unix_yesterday - $unix_onebefore) > 86400) ? 1 : 0;
            //--------------------//

            foreach ($oneBefore[0] as $k => $v) {
                if (preg_match("/^yen/", $k)) {$ob_yen[$k] = $v;}
            }

            $lineSum = $this->Utility->makeLineSum($oneBefore[0]);
            $sum = $lineSum[0];
            $ob_sum = array_sum($sum);
        }

        $appUrl = "http://" . $_SERVER['HTTP_HOST'] . "/BrainLog/public";

        return view('money.input')
            ->with('today', $today)
            ->with('yesterday', $yesterday)
            ->with('ob_date', $ob_date)
            ->with('ob_yen', $ob_yen)
            ->with('date_diff', $date_diff)
            ->with('ob_sum', $ob_sum)
            ->with('appUrl', $appUrl);
    }

    public function multiinput()
    {
        $data = [];

        foreach ($_POST as $k => $v) {
            if (preg_match("/^yen_/", $k)) {
                if (trim($v) != "") {
                    $data[$k] = $v;
                }
            }
        }

        if (empty($data)) {
            echo "<div style='color : #ff3333; font-weight : bold;'>no yen data!!</div>";
            exit();
        }

        $lineSum = $this->Utility->makeLineSum($_POST);
        $now_sum = array_sum($lineSum[0]);

        $now_diff = ($_POST['ob_sum'] - $now_sum);

        $date_start = strtotime($_POST['ob_date']) + 86400 + 1;
        $date_end = strtotime($_POST['input_date']) + 1;
        $select_date = [];
        for ($i = $date_start; $i <= $date_end; $i += 86400) {
            $select_date[] = date("Y-m-d", $i);
        }

        $YENTYPE = [];
        $inputYen = [];
        foreach ($_POST as $k => $v) {
            if (preg_match("/^yen_(.+)/", $k, $m)) {
                list(, $YENTYPE[]) = $m;
                $inputYen[] = $k . ":" . $v;
            }
        }

        return view('money.multiinput')
            ->with('now_diff', $now_diff)
            ->with('select_date', $select_date)
            ->with('ob_sum', $_POST['ob_sum'])
            ->with('YENTYPE', implode("/", $YENTYPE))
            ->with('inputYen', implode("/", $inputYen));
    }

    public function multiinsert()
    {
        $ary1 = [];
        foreach ($_POST['date_select'] as $k => $v) {
            if (trim($_POST['spend_money'][$k]) == "") {continue;}
            $ary1[$v][] = $_POST['spend_money'][$k];
        }

        $ary2 = [];
        foreach ($ary1 as $k => $v) {
            $ary2[$k] = array_sum($v);
        }

        $dateAry = array_keys($ary2);
        sort($dateAry);

        $ary3 = [];
        $money_start = $_POST['ob_sum'];
        foreach ($dateAry as $date) {
            $ary3[$date] = ($money_start - $ary2[$date]);
            $money_start -= $ary2[$date];
        }

        $YENTYPE = explode("/", $_POST['YENTYPE']);

        $ary4 = [];
        foreach ($ary3 as $k => $v) {
            list($year, $month, $day) = explode("-", $k);
            $ary4[$k]['year'] = $year;
            $ary4[$k]['month'] = $month;
            $ary4[$k]['day'] = $day;

            $yenCount = $this->getYenCount($v, $YENTYPE);
            foreach ($yenCount as $k2 => $v2) {$ary4[$k]['yen_' . $k2] = $v2;}
        }

        $maxDate = max($dateAry);
        $ex_inputYen = explode("/", $_POST['inputYen']);
        foreach ($ex_inputYen as $v) {
            list($cYen, $cVal) = explode(":", $v);
            $ary4[$maxDate][$cYen] = $cVal;
        }

        $oneBefore = DB::table('t_money')->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->take(1)->get();

        foreach ($oneBefore[0] as $k => $v) {
            if (preg_match("/^bank/", $k)) {
                foreach ($dateAry as $v2) {$ary4[$v2][$k] = $v;}
            }

            if (preg_match("/^pay/", $k)) {
                foreach ($dateAry as $v2) {$ary4[$v2][$k] = $v;}
            }
        }

        foreach ($dateAry as $k => $v) {
            $ary4[$v]['created_at'] = date("Y-m-d H:i:s");
            $ary4[$v]['updated_at'] = date("Y-m-d H:i:s");
        }

        //--------------------------------------------------//
        $data = [];
        foreach ($dateAry as $k => $v) {
            $data[$k] = $ary4[$v];
        }

        DB::table('t_money')->insert($data);
        //--------------------------------------------------//

        return redirect('/money/index');
    }

    public function singleinput()
    {
        $data = [];

        foreach ($_POST as $k => $v) {
            if (preg_match("/^yen_/", $k)) {
                if (trim($v) != "") {
                    $data[$k] = $v;
                }
            }
        }

        if (empty($data)) {
            echo "<div style='color : #ff3333; font-weight : bold;'>no yen data!!</div>";
            exit();
        }

        list($data['year'], $data['month'], $data['day']) = explode("-", $_POST['input_date']);

        $oneBefore = DB::table('t_money')->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->take(1)->get();

        foreach ($oneBefore[0] as $k => $v) {
            if (preg_match("/^bank/", $k)) {$data[$k] = $v;}
            if (preg_match("/^pay/", $k)) {$data[$k] = $v;}
        }

        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");

        DB::table('t_money')->insert($data);

        return redirect('/money/index');
    }

    public function bank()
    {
        $result = DB::table('t_money')->orderBy('year')->orderBy('month')->orderBy('day')

            ->get(['year', 'month', 'day', 'bank_a', 'bank_b', 'bank_c', 'bank_d', 'pay_a', 'pay_b', 'pay_c']);

        $data = [];
        if (isset($result[0])) {
            $bankA = [];
            $bankB = [];
            $bankC = [];
            $bankD = [];
            $payA = [];
            $payB = [];
            $payC = [];

            foreach ($result as $v) {
                $bankA[$v->bank_a][] = $v->year . "-" . $v->month . "-" . $v->day;
                $bankB[$v->bank_b][] = $v->year . "-" . $v->month . "-" . $v->day;
                $bankC[$v->bank_c][] = $v->year . "-" . $v->month . "-" . $v->day;
                $bankD[$v->bank_d][] = $v->year . "-" . $v->month . "-" . $v->day;
                $payA[$v->pay_a][] = $v->year . "-" . $v->month . "-" . $v->day;
                $payB[$v->pay_b][] = $v->year . "-" . $v->month . "-" . $v->day;
                $payC[$v->pay_c][] = $v->year . "-" . $v->month . "-" . $v->day;
            }

            $bankAry = ['bankA', 'bankB', 'bankC', 'bankD', 'payA', 'payB', 'payC'];

            foreach ($bankAry as $v) {
                $i = 0;
                foreach ($$v as $k2 => $v2) {
                    if (trim($k2) == "") {continue;}
                    $data[$v][sprintf("%04d", $i)] = $v2[0] . " : " . number_format($k2);
                    $i++;
                }
            }
        }

        return view('money.bank')
            ->with('data', $data);
    }

    public function bankinput()
    {
        try {
            DB::beginTransaction();

            foreach ($_POST['bankradio'] as $k => $v) {
                list($year, $month, $day) = explode("-", $_POST['bankdate'][$k]);

                $result = DB::table('t_money')->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->get(['id']);

                if (isset($result[0])) {
                    $SQL = " update t_money set " . $v . " = " . $_POST['bankmoney'][$k] . " where id >= " . $result[0]->id . "; ";
                    DB::statement($SQL);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect('/money/bank');
    }

    public function summary()
    {
        $result = DB::table('t_money')->groupBy('year')->groupBy('month')->get(['year', 'month']);

        $data = [];
        $end_all_money = [];
        if (isset($result[0])) {
            foreach ($result as $v) {
                $monthEnd = date("t", strtotime($v->year . "-" . $v->month . "-01"));
                $result_end = DB::table('t_money')->where('year', '=', $v->year)->where('month', '=', $v->month)->where('day', '=', $monthEnd)->get();
                $end_all = 0;
                if (isset($result_end[0])) {
                    $lineSum = $this->Utility->makeLineSum($result_end[0]);
                    $sum = $lineSum[0];
                    $bank = $lineSum[1];
                    $pay = $lineSum[2];
                    $end_all = array_sum($sum) + array_sum($bank) + array_sum($pay);
                }

                $ary = [];
                $ary[] = $v->year;
                $ary[] = $v->month;
                $ary[] = $monthEnd;
                $ary[] = $end_all;

                $salary__ = 0;
                $result_salary = DB::table('t_salary')->where('year', '=', $v->year)->where('month', '=', $v->month)->get(['salary']);
                if (isset($result_salary[0])) {
                    $sal = [];
                    foreach ($result_salary as $v2) {
                        $sal[] = $v2->salary;
                    }

                    $salary__ = array_sum($sal);
                }

                $ary[] = $salary__;

                $data[] = implode("|", $ary);

                $end_all_money[] = $end_all;
            }
        }

        $spend = [];
        $spend[0] = 0;
        for ($i = 1; $i < count($data); $i++) {
            $spend[$i] = ($end_all_money[$i - 1] - $end_all_money[$i]) * -1;
        }

        ////////////////////////////////////////////////////
        $credit = [];
        $SQL = " select year,month,sum(price) as sum from t_credit group by year,month; ";
        $result = DB::select($SQL);
        if (isset($result[0])) {
            foreach ($result as $v) {
                $credit[$v->year][$v->month] = $v->sum;
            }
        }
        ////////////////////////////////////////////////////

        return view('money.summary')
            ->with('data', $data)
            ->with('spend', $spend)
            ->with('credit', $credit);
    }

    public function salary()
    {
        $result = DB::table('t_salary')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        $data = [];
        if (isset($result[0])) {
            foreach ($result as $k => $v) {
                $data[$k]['year'] = $v->year;
                $data[$k]['month'] = $v->month;
                $data[$k]['day'] = $v->day;
                $data[$k]['company'] = $v->company;
                $data[$k]['salary'] = $v->salary;
            }
        }

        return view('money.salary')
            ->with('data', $data);
    }

    public function salaryinput()
    {
        $data = [];
        list($data['year'], $data['month'], $data['day']) = explode("-", $_POST['salary_date']);
        $data['company'] = $_POST['salary_company'];
        $data['salary'] = $_POST['salary_money'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        DB::table('t_salary')->insert($data);
        return redirect('/money/salary');
    }

    public function credit()
    {
        $result = DB::table('t_credit')->orderBy('year')->orderBy('month')->orderBy('day')->orderBy('item')->get();

        $_itemAry = [];

        $data = [];
        $__data2 = [];
        if (isset($result[0])) {
            foreach ($result as $v) {
                $data[trim($v->year)][trim($v->month)][] = "(" . trim($v->day) . ") " . trim($v->item) . "  " . number_format(trim($v->price)) . "円";

                $__data2[trim($v->year)][trim($v->month)][] = trim($v->price);

                $_itemAry[trim($v->item)] = "";
            }
        }

        $data2 = [];
        if (!empty($__data2)) {
            foreach ($__data2 as $k => $v) {
                foreach ($v as $k2 => $v2) {
                    $data2[$k][$k2] = number_format(array_sum($v2));
                }
            }
        }

        $itemAry = array_keys($_itemAry);
        sort($itemAry);

        return view('money.credit')
            ->with('data', $data)
            ->with('data2', $data2)
            ->with('itemAry', $itemAry);
    }

    public function creditinsert()
    {
        if (isset($_POST['credit'])) {
            $ex_credit = explode("\n", $_POST['credit']);
            $data = [];
            $i = 0;
            foreach ($ex_credit as $v) {
                if (trim($v) == "") {continue;}

//list($date, $data[$i]['item'], $data[$i]['price']) = explode("/", trim($v));
                //list($data[$i]['year'], $data[$i]['month'], $data[$i]['day']) = explode("-", trim($date));

                list($data[$i]['year'], $month, $day, $data[$i]['item'], $data[$i]['price'], $data[$i]['bank']) = explode("\t", trim($v));

                $data[$i]['month'] = sprintf("%02d", $month);
                $data[$i]['day'] = sprintf("%02d", $day);

                $data[$i]['created_at'] = date("Y-m-d H:i:s");
                $data[$i]['updated_at'] = date("Y-m-d H:i:s");

                $i++;
            }

            if (!empty($data)) {
                DB::table("t_credit")->insert($data);
            }
        }

        return redirect('/money/credit');
    }

    public function moneyjogai()
    {
        $ret = [];
        $yenCount = $this->getYenCount($_POST['jogai_money'], explode("/", $_POST['yenType']));
        foreach ($yenCount as $k => $v) {$ret[] = "yen_" . $k . "|" . $v;}
        echo implode("/", $ret);
    }

    public function repair()
    {
        $appUrl = "http://" . $_SERVER['HTTP_HOST'] . "/BrainLog/public";
        return view('money.repair')
            ->with('appUrl', $appUrl);
    }

    public function repairsearch()
    {
        $ret = "";

        $date_from = (trim($_POST['basedate']) != "") ? trim($_POST['basedate']) : date("Y-m-d");
        $date_to = date("Y-m-d", strtotime($date_from) + (86400 * $_POST['datenum']));

        $search_from = ($_POST['datenum'] >= 0) ? $date_from : $date_to;
        $search_to = ($_POST['datenum'] >= 0) ? $date_to : $date_from;

        /////////////////////////
        $beforeDate = date("Y-m-d", strtotime($search_from) - 86400);
        list($year, $month, $day) = explode("-", $beforeDate);
        $result2 = DB::table('t_money')->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->get();
        $lineSum = $this->Utility->makeLineSum($result2[0]);
        $sagakuBase = array_sum($lineSum[0]);
        $beforeMoney = array_sum($lineSum[0]);
        $ret .= "beforeMoney:" . $beforeMoney . ";";
        /////////////////////////

        $data = [];
        $l = 0;
        for ($i = strtotime($search_from); $i <= strtotime($search_to); $i += 86400) {
            $date = date("Y-m-d", $i);
            list($year, $month, $day) = explode("-", $date);
            $result = DB::table('t_money')->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->get();
            $ary = [0 => 'date:' . $date];
            if (isset($result[0])) {
                $j = 1;
                foreach ($result[0] as $column => $v) {
                    if (preg_match("/^yen_/", $column)) {
                        $ary[$j] = $column . ":" . $v;
                        $j++;
                    }
                }

                $lineSum = $this->Utility->makeLineSum($result[0]);
                $ary[] = "sum:" . array_sum($lineSum[0]);

                $ary[] = "sagaku:" . ($sagakuBase - array_sum($lineSum[0]));
                $sagakuBase = array_sum($lineSum[0]);
            }

            $data[$date] = implode("|", $ary);

            $l++;
        }

        $ret .= implode("/", $data);

        $ret .= ";totalSpend:" . ($beforeMoney - $sagakuBase);

        echo $ret;
    }

    public function repairinput()
    {
        if (isset($_POST['param'])) {
            foreach ($_POST['param'] as $k => $v) {
                if ((isset($v['sagaku'])) and ($v['sagaku'] != 0)) {
                    $update = [];
                    foreach ($v as $column => $v2) {
                        if (preg_match("/^yen_/", $column)) {
                            $update[$column] = $v2;
                        }
                    }

                    list($year, $month, $day) = explode("-", $v['date']);

                    DB::table('t_money')->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->update($update);
                }
            }
        }

        return redirect('/money/index');
    }

    public function history()
    {
        $data = "";
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                $param = [];
                $_mt = [];
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}

                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    list($year, $month, $day) = explode("-", $date);

                    $param[$year * 1][$month * 1][$day * 1] = $youbi . "|" . $total . "|" . $spend;

                    $_mt[$year * 1][$month * 1][$day * 1] = $spend;
                }

                $monthTotal = [];

                $over3000 = [];

                foreach ($_mt as $year => $v) {
                    foreach ($v as $month => $v2) {
                        foreach ($v2 as $day => $_ms) {

                            if ($day == 1) {$ary = [];}

                            $ary[] = $_ms;

                            $monthTotal[$year][$month][$day] = array_sum($ary);

                            if ($_ms >= 3000) {$over3000[$year][$month][] = $day;}

                        }
                    }
                }

                if (!empty($param)) {
                    $param2 = [];
                    for ($year = 2014; $year <= date("Y"); $year++) {
                        for ($month = 1; $month <= 12; $month++) {
                            for ($day = 1; $day <= 31; $day++) {
                                if (checkdate($month, $day, $year)) {
                                    $param2[$year][$day][$month] = (isset($param[$year][$month][$day])) ? $param[$year][$month][$day] : "||";
                                } else {
                                    $param2[$year][$day][$month] = "||";
                                }
                            }
                        }
                    }

                    if (!empty($param2)) {

                        //------------------//
                        $holiday = [];
                        $file = public_path() . "/mySetting/holiday.data";
                        $content = file_get_contents($file);
                        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
                        foreach ($ex_content as $v) {
                            if (trim($v) == "") {continue;}
                            $holiday[] = trim($v);
                        }
                        sort($holiday);
                        //------------------//

                        $MonthTotal = [];
                        foreach ($param as $year => $v) {
                            foreach ($v as $month => $v2) {
                                $param3 = [];
                                foreach ($v2 as $day => $daydata) {
                                    list(, , $_spend) = explode("|", $daydata);
                                    $param3[] = $_spend;
                                }
                                $MonthTotal[$year][$month * 1] = array_sum($param3);
                            }
                        }

                        foreach ($param2 as $year => $v) {
                            $data .= "<div class='div_year'>" . $year . "</div>";
                            $data .= "<table id='table_history'>";
                            $data .= "<tr>";
                            $data .= "<td class='td_date' style='border : 1px solid #ffffff; background : #ffffff;'></td>";
                            for ($i = 1; $i <= 12; $i++) {$data .= "<td class='td_month' style='text-align : center;'>" . sprintf("%02d", $i) . "</td>";}
                            $data .= "</tr>";
                            foreach ($v as $day => $v2) {
                                $data .= "<tr>";
                                $data .= "<td class='td_date' style='text-align : center;'>" . sprintf("%02d", $day) . "</td>";
                                $month = 1;
                                foreach ($v2 as $v3) {
                                    list($youbi, $total, $spend) = explode("|", $v3);

                                    $bgColor = "";

                                    if ($month % 2 == 0) {$bgColor = "background : #ffffcc;";}

                                    if (in_array(sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day), $holiday)) {
                                        $bgColor = "background : #ffcccc;";
                                    }

                                    switch ($youbi) {
                                        case 0:
                                            $bgColor = "background : #ffcccc;";
                                            break;
                                        case 6:
                                            $bgColor = "background : #ccccff;";
                                            break;
                                    }

                                    if (trim($total) == "") {$bgColor = "";}

                                    $data .= "<td style='" . $bgColor . "'>";
                                    $data .= "<div>";
                                    $data .= (trim($total) != "") ? number_format($total) : $total;
                                    $data .= "</div>";

                                    $divBg = "";
                                    if (trim($spend) != "") {
                                        $divBg = (trim($spend) * 1 >= 3000) ? "background : #ff3333; color : #ffffff;" : "";
                                    }
                                    $data .= "<div style='" . $divBg . "'>";
                                    $data .= (trim($spend) != "") ? number_format($spend) : $spend;
                                    $data .= "</div>";

                                    $data .= "<div>";
                                    $data .= (isset($monthTotal[$year][$month][$day])) ? number_format($monthTotal[$year][$month][$day]) : "";
                                    $data .= "</div>";

                                    $data .= "<div>";
                                    $data .= (isset($monthTotal[$year][$month][$day])) ? number_format(floor($monthTotal[$year][$month][$day] / $day)) : "";
                                    $data .= "</div>";

                                    $data .= "</td>";

                                    $month++;
                                }
                                $data .= "</tr>";
                            }
                            $data .= "<tr>";
                            $data .= "<td></td>";
                            for ($i = 1; $i <= 12; $i++) {
                                $_monthTotal = (isset($MonthTotal[$year][$i])) ? $MonthTotal[$year][$i] : "";
                                $data .= ($_monthTotal > 0) ? "<td style='background : #ff3333; color : #ffffff;'>" : "<td>";
                                $data .= (trim($_monthTotal) != "") ? number_format($_monthTotal) : "";
                                $data .= "</td>";
                            }

                            if (isset($MonthTotal[$year])) {
                                $_monthTotal = array_sum($MonthTotal[$year]);
                                $data .= ($_monthTotal > 0) ? "<td style='background : #ff3333; color : #ffffff;'>" : "<td>";
                                $data .= number_format($_monthTotal);
                                $data .= "</td>";
                            } else {
                                $data .= "<td><br></td>";
                            }

                            $data .= "</tr>";

                            $data .= "<tr>";
                            $data .= "<td>3000↑</td>";
                            for ($i = 1; $i <= 12; $i++) {
                                $data .= "<td style='text-align : center;'>";
                                $data .= (isset($over3000[$year][$i])) ? count($over3000[$year][$i]) : "<br>";
                                $data .= "</td>";
                            }
                            $data .= "</tr>";

                            $data .= "</table>";
                        }
                    }
                }
            }
        }

        return view('money.history')
            ->with('data', $data);
    }

    public function graph($yearmonth = null)
    {
        $data = "";
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";

        $data = [];
        $YM = (trim($yearmonth) != "") ? $yearmonth : date("Y-m");
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                $param = [];
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    $ex_v = explode("|", trim($v));
                    $param[trim($ex_v[0])] = trim($ex_v[2]);
                }

                foreach ($param as $date => $total) {
                    if (preg_match("/^" . $YM . "/", $date)) {
                        $data[$date] = $total;
                    }
                }
            }
        }

        if (empty($data)) {exit();}

        $min = min($data) - 30000;
        $max = max($data) + 30000;

        ///////////////////////////////////
        $prevMonth = "0";
        if (strtotime($YM . "-01") > strtotime("2014-06")) {
            $prevMonth = date("Y-m", strtotime($YM . "-01" . "-1 month"));
        }

        $nextMonth = "0";
        if (strtotime($YM . "-01") < strtotime(date("Y-m") . "-01")) {
            $nextMonth = date("Y-m", strtotime($YM . "-01" . "+1 month"));
        }
        ///////////////////////////////////

        return view('money.graph')
            ->with('YM', $YM)
            ->with('data', $data)
            ->with('graphdata', implode(",", $data))
            ->with('min', $min)
            ->with('max', $max)
            ->with('prevMonth', $prevMonth)
            ->with('nextMonth', $nextMonth);
    }

    private function makeListData($result, $beforeMonthEnd)
    {

        //-----------------------------------//（前月末の合計金額）
        list($bYear, $bMonth, $bDay) = explode("-", $beforeMonthEnd);
        $result2 = DB::table('t_money')->where('year', '=', $bYear)->where('month', '=', $bMonth)->where('day', '=', $bDay)->get();
        $sum = [];
        $bank = [];
        if (isset($result2[0])) {
            $lineSum = $this->Utility->makeLineSum($result2[0]);
            $sum = $lineSum[0];
            $bank = $lineSum[1];
            $pay = $lineSum[2];
        }
        $bm_all = array_sum($sum) + array_sum($bank) + array_sum($pay);
        //-----------------------------------//（前月末の合計金額）

        $column = [];
        $data = [];

        $total = 0;

        foreach ($result as $k => $v) {

            //column
            if ($k == 0) {
                $column = ['day'];

                foreach ($v as $k2 => $v2) {
                    if (preg_match("/^yen_(.+)/", $k2, $m)) {
                        list(, $yen) = $m;
                        $column[] = $yen;

                        if ($yen == 1) {$column[] = "sum";}

                    }

                    if (preg_match("/^bank/", $k2)) {
                        $column[] = $k2;
                    }

                    if (preg_match("/^pay/", $k2)) {
                        $column[] = $k2;
                    }
                }

//$column[] = "sum";
                $column[] = "all";
                $column[] = "spend";
                $column[] = "total";
                $column[] = "average";
            }

            //data
            $data[($v->day * 1)]['day'] = $v->day;

            foreach ($v as $k2 => $v2) {
                if (preg_match("/^yen_(.+)/", $k2, $m)) {
                    list(, $yen) = $m;
                    $data[($v->day * 1)][$yen] = $v2;
                }

                if (preg_match("/^bank/", $k2)) {
                    $data[($v->day * 1)][$k2] = $v2;
                }

                if (preg_match("/^pay/", $k2)) {
                    $data[($v->day * 1)][$k2] = $v2;
                }
            }

            $lineSum = $this->Utility->makeLineSum($v);
            $sum = $lineSum[0];
            $bank = $lineSum[1];
            $pay = $lineSum[2];

            $data[($v->day * 1)]['sum'] = array_sum($sum);
            $data[($v->day * 1)]['all'] = array_sum($sum) + array_sum($bank) + array_sum($pay);

            $data[($v->day * 1)]['spend'] = ($k > 0) ? ($data[($v->day * 1) - 1]['all'] - $data[($v->day * 1)]['all']) : ($bm_all - $data[($v->day * 1)]['all']);

            $total += $data[($v->day * 1)]['spend'];
            $data[($v->day * 1)]['total'] = $total;

            $data[($v->day * 1)]['average'] = floor($data[($v->day * 1)]['total'] / ($v->day * 1));
        } //foreach ($result as $k=>$v)

        return [$column, $data, $bm_all];
    }

    private function getYenCount($yen, $YENTYPE)
    {
        $ret = array();

        foreach ($YENTYPE as $v) {
            if ($v == 2000) {
                $ret[$v] = 0;
                continue;
            }

            $ret[$v] = 0;

            while ($yen >= $v) {
                $yen -= $v;
                $ret[$v]++;
            }
        }

        return $ret;
    }

    public function weeklydisp($ymd)
    {

        $prev = (strtotime($ymd) > strtotime("2018-08-19")) ? 1 : 0;
        $prev_sunday = date("Y-m-d", strtotime($ymd) - (86400 * 7));

        $next = (strtotime($ymd) + (86400 * 7) < strtotime(date("Ymd"))) ? 1 : 0;
        $next_sunday = date("Y-m-d", strtotime($ymd) + (86400 * 7));

        ////////////////////////////////////////////
        $_mt = [];
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    $_mt[$date] = $spend;
                }
            }
        }
        ////////////////////////////////////////////

        ////////////////////////////////////////////
        $salary = [];
        $result = DB::table('t_salary')->get(['year', 'month', 'day', 'salary']);
        foreach ($result as $v) {
            $salary[$v->year . "-" . $v->month . "-" . $v->day] = $v->salary;
        }
        ////////////////////////////////////////////

        $Spend = [];
        $_koumoku = [];
        for ($i = strtotime($ymd); $i < (strtotime($ymd) + (86400 * 7)); $i += 86400) {
            $result = DB::table('t_dailyspend')
                ->where('year', '=', date("Y", $i))->where('month', '=', date("m", $i))->where('day', '=', date("d", $i))
                ->get(['price', 'koumoku', 'flag']);
            if (isset($result[0])) {
                foreach ($result as $v) {
                    $Spend[date("Y-m-d", $i)][$v->koumoku][] = $v->price;
                    $koumoku[$v->koumoku] = "";
                }
            }
        }

        ////////////////////////////////////////////
        $kakeiKoumoku = [];
        $file = "/var/www/html/BrainLog/public/mySetting/KakeiKoumoku.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    if (!isset($koumoku[trim($v)])) {continue;}
                    $kakeiKoumoku[] = trim($v);
                }
            }
        }
        ////////////////////////////////////////////

        $CreditItems = [];
        $youbi = ['日', '月', '火', '水', '木', '金', '土'];
        $dispTable = "";
        $dispTable .= "<table border='0' cellspacing='2' cellpadding='5' id='dispTable'>";
        $dispTable .= "<tr>";
        $dispTable .= "<td colspan='5' style='border : 0px;'></td>";
        foreach ($kakeiKoumoku as $v) {
            $dispTable .= "<td class='midashi'>" . $v . "</td>";
        }
        $dispTable .= "</tr>";

        for ($i = strtotime($ymd); $i < (strtotime($ymd) + (86400 * 7)); $i += 86400) {
            $dispTable .= "<tr>";
            $dispTable .= "<td>" . date("Y-m-d", $i) . "</td>";
            $dispTable .= "<td>" . $youbi[date("w", $i)] . "</td>";
            $spent = (isset($_mt[date("Y-m-d", $i)])) ? $_mt[date("Y-m-d", $i)] : 0;
            $sal = (isset($salary[date("Y-m-d", $i)])) ? $salary[date("Y-m-d", $i)] : 0;
            $dispTable .= "<td class='align_right bg_green1'>" . ($spent + $sal) . "</td>";

            $result = DB::table('t_credit')
                ->where('year', '=', date("Y", $i))
                ->where('month', '=', date("m", $i))
                ->where('day', '=', date("d", $i))
                ->get(['year', 'month', 'day', 'item', 'price']);
            $credit = 0;
            if (isset($result[0])) {
                $cre = [];
                foreach ($result as $v) {
                    $CreditItems[$v->year . "-" . $v->month . "-" . $v->day][] = $v->item . " : " . $v->price;
                    $cre[] = $v->price;
                }
                $credit = array_sum($cre);
            }
            $dispTable .= "<td class='align_right bg_green2'>" . $credit . "</td>";

            $dispTable .= "<td class='align_right bg_green2'>";
            $dispTable .= ($spent - $credit + $sal);
            $dispTable .= "</td>";

            foreach ($kakeiKoumoku as $v) {
                $background = ($v == "プラス") ? "background : #eeeeff;" : "";
                $dispTable .= "<td class='align_right' style='" . $background . "'>";
                $dispTable .= (isset($Spend[date("Y-m-d", $i)][$v])) ? array_sum($Spend[date("Y-m-d", $i)][$v]) : "";
                $dispTable .= "</td>";
            }

            $dispTable .= "</tr>";
        }
        $dispTable .= "</table>";

        return view('money.weeklydisp')
            ->with('ymd', $ymd)
            ->with('dispTable', $dispTable)

            ->with('Spend', $Spend)
            ->with('CreditItems', $CreditItems)

            ->with('prev', $prev)
            ->with('prev_sunday', $prev_sunday)
            ->with('next', $next)
            ->with('next_sunday', $next_sunday);
    }

    public function weeklyinput($ymd)
    {

        $prev = (strtotime($ymd) > strtotime("2018-08-19")) ? 1 : 0;
        $prev_sunday = date("Y-m-d", strtotime($ymd) - (86400 * 7));

        $next = (strtotime($ymd) + (86400 * 7) < strtotime(date("Ymd"))) ? 1 : 0;
        $next_sunday = date("Y-m-d", strtotime($ymd) + (86400 * 7));

        ////////////////////////////////////////////
        $_mt = [];
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    $_mt[$date] = $spend;
                }
            }
        }
        ////////////////////////////////////////////

        ////////////////////////////////////////////
        $salary = [];
        $result = DB::table('t_salary')->get(['year', 'month', 'day', 'salary']);
        foreach ($result as $v) {
            $salary[$v->year . "-" . $v->month . "-" . $v->day] = $v->salary;
        }
        ////////////////////////////////////////////

        ////////////////////////////////////////////
        $kakeiKoumoku = [];
        $file = "/var/www/html/BrainLog/public/mySetting/KakeiKoumoku.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    $kakeiKoumoku[] = trim($v);
                }
            }
        }
        ////////////////////////////////////////////

        $data = [];
        $data2 = [];
        for ($i = strtotime($ymd); $i < (strtotime($ymd) + (86400 * 7)); $i += 86400) {
            $j = 0;
            foreach ($kakeiKoumoku as $koumoku) {
                $result = DB::table('t_dailyspend')
                    ->where('year', '=', date("Y", $i))
                    ->where('month', '=', date("m", $i))
                    ->where('day', '=', date("d", $i))
                    ->where('koumoku', '=', $koumoku)
                    ->where('flag', '=', 'daily')
                    ->get(['price']);
                if (isset($result[0])) {
                    $aaa = [];
                    foreach ($result as $v) {$aaa[] = $v->price;}
                    $data[date("Y-m-d", $i)][$j]['price'] = array_sum($aaa);
                    $data[date("Y-m-d", $i)][$j]['koumoku'] = $koumoku;
                    $j++;
                }
            }

            $result2 = DB::table('t_dailyspend')
                ->where('year', '=', date("Y", $i))
                ->where('month', '=', date("m", $i))
                ->where('day', '=', date("d", $i))
                ->where('flag', '=', 'credit')
                ->get(['price', 'koumoku']);

            if (isset($result2[0])) {
                foreach ($result2 as $v2) {
                    $data2[date("Y-m-d", $i)][$v2->price] = $v2->koumoku;
                }
            }
        }
//print_r($data);
        //print_r($data2);

        $youbi = ['日', '月', '火', '水', '木', '金', '土'];
        $hiduke = [];
        $inputTable = "";
        $inputTable .= "<table border='0' cellspacing='2' cellpadding='5' id='inputTable'>";

        $inputTable .= "<tr>";
        $inputTable .= "<td style='border : 0px;' colspan='5'></td>";
        $inputTable .= "<td style='border : 0px; background : #ffcccc;'>";
        $inputTable .= "<input type='checkbox' id='all_chk' checked>";
        $inputTable .= "</td>";
        $inputTable .= "</tr>";

        for ($i = strtotime($ymd); $i < (strtotime($ymd) + (86400 * 7)); $i += 86400) {
            $inputTable .= "<tr>";
            $inputTable .= "<td>" . date("Y-m-d", $i) . "</td>";
            $inputTable .= "<td>" . $youbi[date("w", $i)] . "</td>";
            $spent = (isset($_mt[date("Y-m-d", $i)])) ? $_mt[date("Y-m-d", $i)] : 0;
            $sal = (isset($salary[date("Y-m-d", $i)])) ? $salary[date("Y-m-d", $i)] : 0;
            $inputTable .= "<td class='align_right'>" . ($spent + $sal) . "</td>";

            $result = DB::table('t_credit')
                ->where('year', '=', date("Y", $i))
                ->where('month', '=', date("m", $i))
                ->where('day', '=', date("d", $i))
                ->get(['year', 'month', 'day', 'item', 'price']);
            $credit = 0;
            if (isset($result[0])) {
                $cre = [];
                foreach ($result as $v) {
                    $CreditItems[$v->year . "-" . $v->month . "-" . $v->day][] = $v->item . " : " . $v->price;
                    $cre[] = $v->price;
                }
                $credit = array_sum($cre);
            }
            $inputTable .= "<td class='align_right'>" . $credit . "</td>";

            $inputTable .= "<td class='align_right'>";
            $inputTable .= "<div id='dailytotal_" . date("Y-m-d", $i) . "'>" . ($spent - $credit + $sal) . "</div>";
            $inputTable .= "<div id='sagaku_" . date("Y-m-d", $i) . "' class='sagaku_div'>" . ($spent - $credit + $sal) . "</div>";
            $inputTable .= "</td>";

            $inputTable .= "<td>";
            $inputTable .= "<input type='checkbox' id='change_" . date("Y-m-d", $i) . "' name='change_" . date("Y-m-d", $i) . "' value='1' checked>";
            $inputTable .= "</td>";

            for ($j = 0; $j < 7; $j++) {
                $inputTable .= "<td>";
                $tb_id = "price_" . date("Y-m-d", $i) . ":" . $j;
                $tb_name = "price[" . date("Y-m-d", $i) . ":" . $j . "]";
                $inputTable .= "<input type='text' id='" . $tb_id . "' name='" . $tb_name . "' value='";
                $inputTable .= (isset($data[date("Y-m-d", $i)][$j]['price'])) ? $data[date("Y-m-d", $i)][$j]['price'] : "";
                $inputTable .= "' class='price_tb'>";
                $inputTable .= "<select id='koumoku_" . date("Y-m-d", $i) . ":" . $j . "' name='koumoku[" . date("Y-m-d", $i) . ":" . $j . "]' class='koumoku_select'>";
                $inputTable .= "<option></option>";
                foreach ($kakeiKoumoku as $v2) {
                    $selected = "";
                    if (isset($data[date("Y-m-d", $i)][$j]['koumoku'])) {
                        $selected = ($v2 == $data[date("Y-m-d", $i)][$j]['koumoku']) ? " selected" : "";
                    }
                    $inputTable .= "<option value='" . $v2 . "'";
                    $inputTable .= $selected;
                    $inputTable .= ">" . $v2 . "</option>";
                }
                $inputTable .= "</select>";
                $inputTable .= "</td>";
            }
            $inputTable .= "</tr>";

            $hiduke[] = date("Y-m-d", $i);
        }
        $inputTable .= "</table>";

        $inputTable .= "<input type='hidden' id='hiduke_all' name='hiduke_all' value='" . implode("/", $hiduke) . "'>";

        $inputTable2 = "";
        if (!empty($CreditItems)) {
            $inputTable2 .= "<hr>";
            $inputTable2 .= "<table border='0' cellspacing='2' cellpadding='5' id='inputTable2'>";
            $i = 10;
            foreach ($CreditItems as $date => $v) {
                foreach ($v as $item_price) {
                    list($item, $price) = explode(" : ", $item_price);
                    $inputTable2 .= "<tr>";
                    $inputTable2 .= "<td>" . $date . "</td>";
                    $inputTable2 .= "<td>" . $youbi[date("w", strtotime($date))] . "</td>";
                    $inputTable2 .= "<td>" . $item . "</td>";
                    $inputTable2 .= "<td class='align_right'>";
                    $inputTable2 .= $price;
                    $inputTable2 .= "<input type='hidden' id='price_" . $date . ":" . $i . "' name='price[" . $date . ":" . $i . "]' value='" . $price . "'>";
                    $inputTable2 .= "</td>";
                    $inputTable2 .= "<td>";
                    $inputTable2 .= "<select id='koumoku_" . $date . ":" . $i . "' name='koumoku[" . $date . ":" . $i . "]' class='koumoku_select'>";
                    $inputTable2 .= "<option></option>";
                    foreach ($kakeiKoumoku as $v2) {
                        $selected = "";
                        if (isset($data2[$date][$price])) {
                            $bbb = $data2[$date][$price];
                            $selected = ($bbb == $v2) ? " selected" : "";
                        }
                        $inputTable2 .= "<option value='" . $v2 . "' " . $selected . ">" . $v2 . "</option>";
                    }
                    $inputTable2 .= "</select>";
                    $inputTable2 .= "</td>";
                    $inputTable2 .= "</tr>";
                    $i++;
                }
            }
            $inputTable2 .= "</table>";
        }

        return view('money.weeklyinput')
            ->with('ymd', $ymd)
            ->with('inputTable', $inputTable)
            ->with('inputTable2', $inputTable2)
            ->with('prev', $prev)
            ->with('prev_sunday', $prev_sunday)
            ->with('next', $next)
            ->with('next_sunday', $next_sunday);
    }

    public function weeklyinsert()
    {

        $sqls = [];

        $change = [];
        foreach ($_POST as $key => $value) {
            if (preg_match("/^change_(.+)/", $key, $m)) {
                $change[$m[1]] = "";
                list($year, $month, $day) = explode("-", $m[1]);
                $sqls[] = "delete from t_dailyspend where year = '" . $year . "' and month = '" . $month . "' and day = '" . $day . "';";
                $sqls[] = "alter table t_dailyspend auto_increment = 1;";
            }
        }

        $hiduke = explode("/", $_POST['hiduke_all']);
        sort($hiduke);
        $MinDate = $hiduke[0];

        $data = [];
        foreach ($hiduke as $date) {
            if (!isset($change[$date])) {continue;}

            for ($i = 0; $i < 30; $i++) {
                if (isset($_POST['price'][$date . ':' . $i])) {
                    if (trim($_POST['price'][$date . ':' . $i]) != "" and trim($_POST['koumoku'][$date . ':' . $i]) != "") {
                        $flag = ($i <= 6) ? "daily" : "credit";
                        $data[$date][] = $_POST['price'][$date . ':' . $i] . "|" . $_POST['koumoku'][$date . ':' . $i] . "|" . $flag;
                    }
                }
            }
        }
//print_r($data);

        foreach ($data as $date => $v) {
            list($year, $month, $day) = explode("-", $date);
            foreach ($v as $onedata) {
                list($price, $koumoku, $flag) = explode("|", $onedata);

                $insert = [];
                $insert['year'] = $year;
                $insert['month'] = $month;
                $insert['day'] = $day;
                $insert['price'] = $price;
                $insert['koumoku'] = $koumoku;
                $insert['flag'] = $flag;
                $insert['created_at'] = date("Y-m-d H:i:s");
                $insert['updated_at'] = date("Y-m-d H:i:s");

                $COLS = [];
                $VALS = [];
                foreach ($insert as $col => $val) {
                    $COLS[] = $col;
                    $VALS[] = "'" . $val . "'";
                }

                $sqls[] = "insert into t_dailyspend(" . implode(" , ", $COLS) . ") values(" . implode(" , ", $VALS) . ");";
            }
        }

        foreach ($sqls as $sql) {
            DB::statement($sql);
        }

        return redirect('/money/' . $MinDate . '/weeklydisp');
    }

    public function monthlydisp($yearmonth)
    {
    }

    public function spendinput()
    {

        if (isset($_POST['spenddata'])) {
            $ex_spenddata = explode("\n", $_POST['spenddata']);

            list($year, $month, $day) = explode("/", trim($ex_spenddata[0]));
            $month = sprintf("%02d", $month);
            $day = sprintf("%02d", $day);

            DB::table('t_dailyspend')->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->delete();

            $input = [];
            $i = 0;

            foreach ($ex_spenddata as $k => $v) {
                if ($k == 0) {continue;}
                if (trim($v) == "") {continue;}

                $input[$i]['year'] = $year;
                $input[$i]['month'] = $month;
                $input[$i]['day'] = $day;

                $ex_v = explode("\t", trim($v));

                $input[$i]['koumoku'] = trim($ex_v[0]);
                $input[$i]['price'] = trim($ex_v[1]);
                $input[$i]['flag'] = "";
                $i++;
            }

            DB::table('t_dailyspend')->insert($input);
        }

        return redirect('/money/' . $_POST['thisMonth'] . '/index');

    }





    public function api($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];

        $moneydata['total'] = 0;
        $moneydata['spend'] = 0;

        //--------------//
        $result2 = DB::table('t_salary')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    if ($ymd == $date) {
                        $moneydata['total'] = $total;
                        $moneydata['spend'] = (isset($result2[0])) ? ($spend + $result2[0]->salary) : $spend;
                        break;
                    }
                }
            }
        }
        //--------------//

        $result = DB::table('t_money')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        $moneydata['data'] = [];

        $kind = [
            'yen_10000', 'yen_5000', 'yen_2000', 'yen_1000',
            'yen_500', 'yen_100', 'yen_50', 'yen_10', 'yen_5', 'yen_1',
            'bank_a', 'bank_b', 'bank_c', 'bank_d',
            'pay_a', 'pay_b', 'pay_c'
        ];

        $hand = [];
        foreach ($kind as $v) {
            if ((isset($result[0]->$v)) and ($result[0]->$v > 0)) {
                $moneydata['data'][$v] = $result[0]->$v;

                if (preg_match("/^yen_(.+)/", $v, $m)) {
                    $hand[] = ($m[1] * $result[0]->$v);
                }
            } else {
                $moneydata['data'][$v] = 0;
            }
        }

        $moneydata['hand'] = array_sum($hand);

        $moneydata['items'] = [];

        $result3 = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        $result4 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        foreach ($result3 as $v) {
            $moneydata['items'][] = $v->koumoku . "　" . number_format($v->price);
        }

        foreach ($result4 as $v) {
            $moneydata['items'][] = $v->item . "　" . number_format($v->price);
        }

        return view('money.api')
            ->with('moneydata', $moneydata);
    }





    public function samedayapi($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];
        $moneydata['sameday'] = [];

        //--------------//
        $result2 = DB::table('t_salary')
            ->get();

        $salaryData = [];
        foreach ($result2 as $v) {
            $salaryData[$v->year . "-" . $v->month . "-" . $v->day] = $v->salary;
        }

        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        $YM = "2014-06";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    list($data_year, $data_month, $data_day) = explode("-", $date);

                    if ($data_day > $day) {continue;}

                    if (($data_year . "-" . $data_month) != $YM) {
                        $ary = [];
                    }

                    $ary[] = $spend;

                    if (isset($salaryData[$date])) {
                        $ary[] = $salaryData[$date];
                    }

                    $moneydata['sameday'][$data_year . "-" . $data_month] = "　" . number_format(array_sum($ary));
                    $YM = $data_year . "-" . $data_month;
                }
            }
        }
        //--------------//

        $moneydata2 = $moneydata;
        unset($moneydata);
        $keys = array_keys($moneydata2['sameday']);
        rsort($keys);
        foreach ($keys as $v) {

$ary = [];
$ary['date'] = $v;
$ary['sum'] = $moneydata2['sameday'][$v];
$ary['bg'] = (strtotime($v) < strtotime("2019-10-01")) ? 0 : 1;
$moneydata['data'][] = $ary;

        }

/*
print_r($moneydata);

Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [date] => 2020-05
                    [sum] => 　149,268
                )

            [1] => Array
                (
                    [date] => 2020-04
                    [sum] => 　150,075
                )

*/

        return view('money.api')
            ->with('moneydata', $moneydata);
    }





    public function spenditemapi($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];

        $result3 = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        $result4 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        $ary = [];
        $price = [];

        foreach ($result3 as $v) {
            if ($v->day > $day) {continue;}
            $ary[$v->year . "-" . $v->month . "-" . $v->day][] = $v->koumoku . "　" . number_format($v->price);
            $price[$v->year . "-" . $v->month . "-" . $v->day][] = $v->price;
        }

        foreach ($result4 as $v) {
            if ($v->day > $day) {continue;}
            $ary[$v->year . "-" . $v->month . "-" . $v->day][] = $v->item . "　" . number_format($v->price);
            $price[$v->year . "-" . $v->month . "-" . $v->day][] = $v->price;
        }

        foreach ($ary as $_ymd => $v) {
//$moneydata['data'][$_ymd] = "　" . number_format(array_sum($price[$_ymd])) . "\n" . implode("\n", $v);

$ary = [];
$ary['date'] = $_ymd;
$ary['sum'] = number_format(array_sum($price[$_ymd]));
$ary['item'] = implode("\n", $v);
$moneydata['data'][] = $ary;

        }

        if (!isset($moneydata['data'])) {
            $moneydata['data'] = "nodata";
        }

/*
print_r($moneydata);

Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [date] => 2020-05-01
                    [sum] => 27,487
                    [item] => 食費　862
国民年金基金　26,625
                )

            [1] => Array
                (
                    [date] => 2020-05-02
                    [sum] => 0
                    [item] => 食費　0
                )

*/

        return view('money.api')
            ->with('moneydata', $moneydata);

    }





    public function monthlistapi($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];

        //--------------//
        $result2 = DB::table('t_salary')
            ->get();

        $ary = [];
        foreach ($result2 as $v) {
            $ary[$v->year . "-" . $v->month][] = $v->salary;
        }

        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {continue;}

                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    list($data_year, $data_month, $data_day) = explode("-", $date);

                    $ary[$data_year . "-" . $data_month][] = $spend;
                }
            }
        }
        //--------------//

        $keys = array_keys($ary);
        rsort($keys);
        foreach ($keys as $v) {
//$moneydata['data'][$v] = "　" . number_format(array_sum($ary[$v]));

$ary2 = [];
$ary2['date'] = $v;
$ary2['sum'] = number_format(array_sum($ary[$v]));
$ary2['bg'] = (strtotime($v) < strtotime("2019-10-01")) ? 0 : 1;
$moneydata['data'][] = $ary2;

        }

/*
print_r($moneydata);

Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [date] => 2020-05
                    [sum] => 134,055
                )

            [1] => Array
                (
                    [date] => 2020-04
                    [sum] => 510,231
                )
*/

        return view('money.api')
            ->with('moneydata', $moneydata);
    }





    public function monthitemapi($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];

        $result3 = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        $result4 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        $ary = [];
        $price = [];
        $total = [];

        foreach ($result3 as $v) {
            $ary[$v->year . "-" . $v->month . "-" . $v->day][] = $v->koumoku . "　" . number_format($v->price);
            $price[$v->year . "-" . $v->month . "-" . $v->day][] = $v->price;
            $total[] = $v->price;
        }

        foreach ($result4 as $v) {
            $ary[$v->year . "-" . $v->month . "-" . $v->day][] = $v->item . "　" . number_format($v->price);
            $price[$v->year . "-" . $v->month . "-" . $v->day][] = $v->price;
            $total[] = $v->price;
        }

        $moneydata['total'] = number_format(array_sum($total));

        foreach ($ary as $_ymd => $v) {
//$moneydata['data'][$_ymd] = "　" . number_format(array_sum($price[$_ymd])) . "\n" . implode("\n", $v);

$ary2 = [];
$ary2['date'] = $_ymd;
$ary2['sum'] = number_format(array_sum($price[$_ymd]));
$ary2['item'] = implode("\n", $v);
$moneydata['data'][] = $ary2;

        }

        if (!isset($moneydata['data'])) {
            $moneydata['data'] = "nodata";
        }

/*
print_r($moneydata);

Array
(
    [total] => 134,055
    [data] => Array
        (
            [0] => Array
                (
                    [date] => 2020-05-01
                    [sum] => 27,487
                    [item] => 食費　862
国民年金基金　26,625
                )

            [1] => Array
                (
                    [date] => 2020-05-02
                    [sum] => 0
                    [item] => 食費　0
                )
*/

        return view('money.api')
            ->with('moneydata', $moneydata);

    }





    public function monthkoumokuapi($ymd)
    {

        list($year, $month, $day) = explode("-", $ymd);

        $moneydata = [];

        $result3 = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('id')
            ->get();

        $result4 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('id')
            ->get();

        $price = [];
        $total = [];

        foreach ($result3 as $v) {
            $price[$v->koumoku][] = $v->price;
            $total[] = $v->price;
        }

        foreach ($result4 as $v) {
            $price[$v->item][] = $v->price;
            $total[] = $v->price;
        }

        $_total = array_sum($total);
        $moneydata['total'] = number_format($_total);

        $ary = [];
        $existData = false;
        $j=0;
        foreach ($price as $koumoku => $v) {
            $sum = array_sum($v);

/*
            $str = "";
            $str .= $koumoku;
            $str .= "|";
            $str .= $sum;
            $str .= "|";
            $str .= ($koumoku == "プラス") ? "" : "（" . ceil($sum / $_total * 100) . "%）";
            $ary[] = $str;
*/

$ary[$j]['item'] = $koumoku;
$ary[$j]['sum'] = $sum;
$ary[$j]['percentage'] = ($koumoku == "プラス") ? "" : "（" . ceil($sum / $_total * 100) . "%）";

            $j++;

            $existData = true;
        }

        if ($existData){
            $moneydata['data'] = $ary;
        }else{
            $moneydata['data'] = "nodata";
        }

/*
print_r($moneydata);

Array
(
    [total] => 134,055
    [data] => Array
        (
            [0] => Array
                (
                    [item] => 食費
                    [sum] => 6738
                    [percentage] => （6%）
                )

            [1] => Array
                (
                    [item] => 交通費
                    [sum] => 2183
                    [percentage] => （2%）
                )
*/

        return view('money.api')
            ->with('moneydata', $moneydata);

    }



    public function onedayinputapi($data)
    {

        list($date, $yen) = explode(":", $data);
        list($year, $month, $day) = explode("-", $date);
        list($yen_10000, $yen_5000, $yen_2000, $yen_1000, $yen_500, $yen_100, $yen_50, $yen_10, $yen_5, $yen_1) = explode("|", $yen);

        $result2 = DB::table('t_money')
        ->where('year', '=', $year)
        ->where('month', '=', $month)
        ->where('day', '=', $day)
        ->get(['id']);

        if (isset($result2[0])){
            //update
            $update = [];
            $update['yen_10000'] = $yen_10000;
            $update['yen_5000'] = $yen_5000;
            $update['yen_2000'] = $yen_2000;
            $update['yen_1000'] = $yen_1000;
            $update['yen_500'] = $yen_500;
            $update['yen_100'] = $yen_100;
            $update['yen_50'] = $yen_50;
            $update['yen_10'] = $yen_10;
            $update['yen_5'] = $yen_5;
            $update['yen_1'] = $yen_1;

            DB::table('t_money')->where('id', '=', $result2[0]->id)->update($update);
        }else{
            //insert
            $insert = [];

            $insert['year'] = $year;
            $insert['month'] = $month;
            $insert['day'] = $day;

            $insert['yen_10000'] = $yen_10000;
            $insert['yen_5000'] = $yen_5000;
            $insert['yen_2000'] = $yen_2000;
            $insert['yen_1000'] = $yen_1000;
            $insert['yen_500'] = $yen_500;
            $insert['yen_100'] = $yen_100;
            $insert['yen_50'] = $yen_50;
            $insert['yen_10'] = $yen_10;
            $insert['yen_5'] = $yen_5;
            $insert['yen_1'] = $yen_1;

            $yesterday = date("Y-m-d", strtotime($date) - 1);
            list($y_year, $y_month, $y_day) = explode("-", $yesterday);
            $oneBefore = DB::table('t_money')
                ->where('year', '=', $y_year)
                ->where('month', '=', $y_month)
                ->where('day', '=', $y_day)
                ->get();

            foreach (['bank_a', 'bank_b', 'bank_c', 'bank_d', 'pay_a', 'pay_b', 'pay_c'] as $copy){
                $insert[$copy] = $oneBefore[0]->$copy;
            }

            DB::table('t_money')->insert($insert);
        }
    }



    public function monthscoreapi()
    {
        
        $data = [];
        $totalAry = [];
        $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            if (!empty($ex_content)) {
                foreach ($ex_content as $k=>$v) {
                    
                    if (trim($v) == "") {continue;}
                    
                    list($date, $youbi, $total, $spend) = explode("|", trim($v));
                    list($data_year, $data_month, $data_day) = explode("-", $date);
                    
                    $totalAry[$date] = $total;

                    if ((int)$data_day == 1){
                        $data[$data_year . "-" . $data_month]['startdate'] = date("Y-m-d", strtotime($date) - 86400);
                    }

                    $end_day = date("t", strtotime($data_year . "-" . $data_month . "-01"));
                    if ($data_day == $end_day){
                        $data[$data_year . "-" . $data_month]['end'] = $total;
                    }
                }
            }
        }

        $ymAry = [];
        foreach ($data as $ym=>$value){
            $data[$ym]['start'] = (isset($totalAry[$value['startdate']])) ? $totalAry[$value['startdate']] : 0;
            $ymAry[] = $ym;
        }

        $data2 = $data;
        $data = [];

        rsort($ymAry);

        $i=0;
        foreach ($ymAry as $ym){
            $data['data'][$i]['ym'] = $ym;
            $data['data'][$i]['start'] = $data2[$ym]['start'];
            $data['data'][$i]['end'] = (isset($data2[$ym]['end'])) ? $data2[$ym]['end'] : "";
            $data['data'][$i]['score'] = (isset($data2[$ym]['end'])) ? ($data2[$ym]['start'] - $data2[$ym]['end']) * -1 : "";
            $i++;
        }

        return view('money.api')
            ->with('moneydata', $data);
    }



    public function bankapi($bank)
    {
        $moneydata = [];

        $result = DB::table('t_money')
            ->where('year', '=', '2019')
            ->where('month', '=', '10')
            ->where('day', '=', '01')
            ->get(['id']);

        $result2 = DB::table('t_money')
            ->where('id', '>=', $result[0]->id)
            ->orderBy('id')
            ->get(['year', 'month', 'day', 'bank_a', 'bank_b', 'bank_c', 'bank_d', 'pay_a', 'pay_b', 'pay_c']);

        $bkYen = 0;
        foreach ($result2 as $k=>$v){
            $moneydata['data'][$k]['date'] = trim($v->year) . "-" . trim($v->month) . "-" . trim($v->day);
            $moneydata['data'][$k]['yen'] = $v->$bank;
            $moneydata['data'][$k]['mark'] = ($v->$bank == $bkYen) ? 0 : 1;
            $bkYen = $v->$bank;
        }

        return view('money.api')
            ->with('moneydata', $moneydata);
    }

}
