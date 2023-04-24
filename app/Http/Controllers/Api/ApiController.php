<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    /**
     *
     */
    public function getYearSpend(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);


        $ary2 = [];
        $result2 = DB::table('t_salary')
            ->where('year', '=', $year)
            ->get();
        foreach ($result2 as $v2) {
            $date = $v2->year . "-" . $v2->month . "-" . $v2->day;
            $ary2[$date] = $v2->salary;
        }


        $ary3 = [];
        $result3 = DB::table('t_bank_move')
            ->get();
        foreach ($result3 as $v3) {
            $from_date = $v3->from_year . "-" . $v3->from_month . "-" . $v3->from_day;
            $ary3[$from_date][] = ($v3->price * -1);

            $to_date = $v3->to_year . "-" . $v3->to_month . "-" . $v3->to_day;
            $ary3[$to_date][] = ($v3->price * 1);
        }
        $ary4 = [];
        foreach ($ary3 as $date => $v3) {
            $ary4[$date] = array_sum($v3);
        }


        //-----------------------------//
        $ary5 = [];

        $result5 = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->orderBy('id')
            ->get();
        foreach ($result5 as $v5) {
            $date = $v5->year . "-" . $v5->month . "-" . $v5->day;
            $ary5[$date][] = [
                'item' => $v5->koumoku,
                'price' => $v5->price,
                'flag' => 0
            ];
        }

        $result5 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->orderBy('id')
            ->get();
        foreach ($result5 as $v5) {
            $date = $v5->year . "-" . $v5->month . "-" . $v5->day;
            $ary5[$date][] = [
                'item' => $v5->item,
                'price' => $v5->price,
                'flag' => 1
            ];
        }
        //-----------------------------//


        ////////////////////
        ///
        $ary = [];

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            if (preg_match("/^" . $year . "/", trim($v))) {

                list($date, $x, $total, $spend) = explode("|", trim($v));

                $salary = (isset($ary2[$date])) ? $ary2[$date] : 0;
                $move = (isset($ary4[$date])) ? $ary4[$date] : 0;

                $ary[] = [
                    'date' => $date,
                    'spend' => ($spend + $salary + $move),
                    'item' => (isset($ary5[$date])) ? $ary5[$date] : []
                ];

            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /**
     *
     */
    public function getmonthstartmoney()
    {

        $response = [];

        $ary = [];
        $monthEndDay = [];

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $x2) = explode("|", trim($v));
            list($year, $month, $day) = explode("-", $date);

            $monthEnd = date("t", strtotime($date));
            if ($day != $monthEnd) {
                continue;
            }

            $ary[trim($year)][trim($month)] = trim($total);
            $monthEndDay[trim($year)][trim($month)] = $day;
        }

        $ary3 = [];
        foreach ($ary as $year => $v) {

            $ary2 = [];
            for ($i = 1; $i <= 12; $i++) {
                $ary2[sprintf("%02d", $i)] = 0;
            }

            foreach ($v as $month => $v2) {
                $ary2[$month] = $v2;
            }

            $ary3[$year] = $ary2;
        }

        $ary4 = [];
        foreach ($ary3 as $year => $v) {
            for ($i = 1; $i <= 12; $i++) {
                $ary4[$year][sprintf("%02d", $i)] = 0;
            }
        }

        foreach ($ary3 as $year => $v) {
            foreach ($v as $month => $total) {
                if (isset($monthEndDay[$year][$month])) {
                    $me = $monthEndDay[$year][$month];
                    $startdate = date("Y-m-d", strtotime($year . "-" . $month . "-" . $me) + 86400);
                    $ex_startdate = explode("-", $startdate);
                    $ary4[$ex_startdate[0]][$ex_startdate[1]] = $total;
                }
            }
        }

        $ary4["2014"]["06"] = 1370938;

        foreach ($ary4 as $year => $v) {
            $money = [];
            $manen = [];
            $updown = [];
            $hikaku = 0;
            $sagaku = [];
            foreach ($v as $month => $yen) {
                $money[] = ($yen == 0) ? "-" : $yen;

                $manen[] = ($yen > 0) ? round($yen / 10000) . "万円" : "-";

                if ($yen == 0) {
                    $updown[] = "-";
                } else {
                    $updown[] = ($hikaku <= $yen) ? 1 : 0;
                }

                if ($month == "01") {
                    $sagaku[] = 0;
                } else {
                    $sa = ($yen - $hikaku);
                    if ($sa < 0) {
                        $sa *= -1;
                    }
                    $sagaku[] = round($sa / 10000) . "万円";
                }

                $hikaku = $yen;
            }

            $response[] = [
                'year' => $year,
                'price' => implode("|", $money),
                'manen' => implode("|", $manen),
                'updown' => implode("|", $updown),
                'sagaku' => implode("|", $sagaku),
            ];
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getsalary()
    {

        $response = [];

        $result = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[$v->year] = "";
        }

        $years = array_keys($ary);

        $ary2 = [];
        foreach ($years as $ye) {
            for ($i = 1; $i <= 12; $i++) {
                $ary2[$ye][sprintf("%02d", $i)] = "-";
            }
        }

        $ary3 = [];
        foreach ($result as $v) {
            $ary3[$v->year][$v->month][] = $v->salary;
        }

        foreach ($ary3 as $year => $v) {
            foreach ($v as $month => $price) {
                $ary2[$year][$month] = round(array_sum($price) / 10000) . "万円";
            }
        }

        foreach ($ary2 as $year => $v) {
            $response[] = [
                "year" => $year,
                "salary" => implode("|", $v)
            ];
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function spenditem(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        //日々の消費額
        $dailySpend = DB::table('t_dailyspend')
            ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
            ->orderBy('id')->get();

        foreach ($dailySpend as $v) {
            $cnt = count($response);
            $response[$cnt]['koumoku'] = $v->koumoku;
            $response[$cnt]['price'] = $v->price;
        }

        //クレジットでの消費額
        $credit = DB::table('t_credit')
            ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
            ->orderBy('id')->get();

        foreach ($credit as $v) {
            $cnt = count($response);
            $response[$cnt]['koumoku'] = $v->item;
            $response[$cnt]['price'] = $v->price;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function spenditemweekly(Request $request)
    {

        $response = [];

        $wnum = date("w", strtotime($request->date));
        $start = strtotime($request->date) - (86400 * $wnum);
        $end = $start + (86400 * 7);

        for ($i = $start; $i < $end; $i += 86400) {

            $date = date("Y-m-d", $i);

            list($year, $month, $day) = explode("-", $date);

            //日々の消費額
            $dailySpend = DB::table('t_dailyspend')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->orderBy('id')->get();

            //クレジットでの消費額
            $credit = DB::table('t_credit')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->orderBy('id')->get();

            $record_exists = false;

            if (isset($dailySpend[0])) {
                foreach ($dailySpend as $v) {
                    $cnt = count($response);
                    $response[$cnt]['date'] = $date;
                    $response[$cnt]['koumoku'] = $v->koumoku;
                    $response[$cnt]['price'] = $v->price;
                }

                $record_exists = true;
            }

            if (isset($credit[0])) {
                foreach ($credit as $v) {
                    $cnt = count($response);
                    $response[$cnt]['date'] = $date;
                    $response[$cnt]['koumoku'] = $v->item;
                    $response[$cnt]['price'] = $v->price;
                }

                $record_exists = true;
            }

            if ($record_exists == false) {
                $cnt = count($response);
                $response[$cnt]['date'] = $date;
                $response[$cnt]['koumoku'] = '';
                $response[$cnt]['price'] = '';
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function timeplaceweekly(Request $request)
    {

        $response = [];

        $wnum = date("w", strtotime($request->date));
        $start = strtotime($request->date) - (86400 * $wnum);
        $end = $start + (86400 * 7);

        for ($i = $start; $i < $end; $i += 86400) {

            $date = date("Y-m-d", $i);

            list($year, $month, $day) = explode("-", $date);

            $result = DB::table('t_timeplace')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->orderBy('time')->get();

            foreach ($result as $v) {
                $cnt = count($response);
                $response[$cnt]['date'] = $date;
                $response[$cnt]['time'] = $v->time;
                $response[$cnt]['place'] = $v->place;
                $response[$cnt]['price'] = $v->price;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function monthlyweeknum(Request $request)
    {

        $response = [];

        $time = strtotime($request->date);

        /*
                $response[0] = 1 + date('W', $time + 86400) - date('W', strtotime(date('Y-m', $time)) + 86400);
        */

        $response[0] = 1 + date('W', $time);

        return response()->json(['data' => $response]);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function traindata(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $table = "t_article" . $year;
        $traindata = DB::table($table)
            ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
            ->where('tag', '=', '電車乗車')
            ->orderBy('id')->get();

        foreach ($traindata as $v) {
            $cnt = count($response);
            $response[$cnt] = $v->article;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function timeplace(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_timeplace')
            ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
            ->orderBy('time')->get();

        foreach ($result as $v) {
            $cnt = count($response);
            $response[$cnt]['time'] = $v->time;
            $response[$cnt]['place'] = $v->place;
            $response[$cnt]['price'] = $v->price;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function monthlyspenditem(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        //日々の消費額
        $dailySpend = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($dailySpend as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }

            $response[$ymd][$cnt]['koumoku'] = $v->koumoku;
            $response[$ymd][$cnt]['price'] = $v->price;
            $response[$ymd][$cnt]['bank'] = 0;
        }

        //クレジットでの消費額
        $credit = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($credit as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }

            $response[$ymd][$cnt]['koumoku'] = $v->item;
            $response[$ymd][$cnt]['price'] = $v->price;
            $response[$ymd][$cnt]['bank'] = 1;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function monthlytraindata(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $table = "t_article" . $year;

        $traindata = DB::table($table)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('tag', '=', '電車乗車')
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($traindata as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }

            $response[$ymd][$cnt] = $v->article;
        }

        return response()->json(['data' => $response]);

    }

    /**
     *
     */
    public function gettraindata()
    {
        $response = [];

        //----------------------//
        $koutsuuhi = [];

        $result = DB::table('t_dailyspend')
            ->where('koumoku', '=', '交通費')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result as $v) {
            $koutsuuhi[$v->year . "-" . $v->month . "-" . $v->day] = $v->price;
        }
        //----------------------//

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        foreach ($_tables as $table) {

            $traindata = DB::table($table)
                ->where('tag', '=', '電車乗車')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($traindata as $v) {

                if ($v->year <= 2019) {
                    continue;
                }

                $ymd = $v->year . "-" . $v->month . "-" . $v->day;

                $cnt = 0;
                if (isset($response[$ymd])) {
                    $cnt = count($response[$ymd]);
                }

                $response[$ymd][$cnt] = $v->article;
            }
        }

        $response2 = [];
        for ($i = strtotime("2020-01-01"); $i <= strtotime(date("Y-m-d")); $i += 86400) {
            if (isset($response[date("Y-m-d", $i)])) {
                $str = implode("\n", $response[date("Y-m-d", $i)]);
                $str .= "|";
                $str .= (isset($koutsuuhi[date("Y-m-d", $i)])) ? $koutsuuhi[date("Y-m-d", $i)] : "";

                //----------------------//
                $ary3 = [];
                foreach ($response[date("Y-m-d", $i)] as $v3) {
                    $ex_v3 = explode("\n", trim($v3));
                    foreach ($ex_v3 as $vv3) {
                        $ex_vv3 = explode("-", trim($vv3));
                        foreach ($ex_vv3 as $vvv3) {
                            $ary3[$vvv3][] = "";
                        }
                    }
                }

                $oufuku = 1;
                foreach ($ary3 as $vvvv3) {
                    if (count($vvvv3) == 1) {
                        $oufuku = 0;
                    }
                }

                $str .= "|" . $oufuku;
                //----------------------//


                $response2[date("Y-m-d", $i)] = $str;
            } else {
                $response2[date("Y-m-d", $i)] = "";
            }
        }

        return response()->json(['data' => $response2]);
    }

    /**
     * @param Request $request
     */
    public function monthlytimeplace(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_timeplace')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        foreach ($result as $v) {
            $ymd = $v->year . "-" . $v->month . "-" . $v->day;

            $cnt = 0;
            if (isset($response[$ymd])) {
                $cnt = count($response[$ymd]);
            }

            $response[$ymd][$cnt]['time'] = $v->time;
            $response[$ymd][$cnt]['place'] = $v->place;
            $response[$ymd][$cnt]['price'] = $v->price;
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function timeplacezerousedate()
    {

        $response = [];

        $result = DB::table('t_timeplace')
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[$v->year . "-" . $v->month . "-" . $v->day][] = $v->price;
        }

        $ary2 = [];
        foreach ($ary as $date => $v) {
            if (count($v) == 1) {
                if ($v[0] == 0) {
                    $ary2[] = $date;
                }
            }
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function moneyinsert(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            $data = [
                'yen_10000' => $request->yen_10000,
                'yen_5000' => $request->yen_5000,
                'yen_2000' => $request->yen_2000,
                'yen_1000' => $request->yen_1000,
                'yen_500' => $request->yen_500,
                'yen_100' => $request->yen_100,
                'yen_50' => $request->yen_50,
                'yen_10' => $request->yen_10,
                'yen_5' => $request->yen_5,
                'yen_1' => $request->yen_1,

                'bank_a' => $request->bank_a,
                'bank_b' => $request->bank_b,
                'bank_c' => $request->bank_c,
                'bank_d' => $request->bank_d,
                'bank_e' => $request->bank_e,

                'pay_a' => $request->pay_a,
                'pay_b' => $request->pay_b,
                'pay_c' => $request->pay_c,
                'pay_d' => $request->pay_d,
                'pay_e' => $request->pay_e
            ];

            $result = DB::table('t_money')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->get(['id']);

            if (isset($result[0])) {
                //更新
                DB::table('t_money')->where('id', $result[0]->id)->update($data);
            } else {
                //新規作成
                $data['year'] = $year;
                $data['month'] = $month;
                $data['day'] = $day;

                DB::table('t_money')->insert($data);
            }

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     */
    public function moneydownload(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_money')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->get();

        if (isset($result[0])) {
            $ary = [];
            $ary['yen_10000'] = $result[0]->yen_10000;
            $ary['yen_5000'] = $result[0]->yen_5000;
            $ary['yen_2000'] = $result[0]->yen_2000;
            $ary['yen_1000'] = $result[0]->yen_1000;
            $ary['yen_500'] = $result[0]->yen_500;
            $ary['yen_100'] = $result[0]->yen_100;
            $ary['yen_50'] = $result[0]->yen_50;
            $ary['yen_10'] = $result[0]->yen_10;
            $ary['yen_5'] = $result[0]->yen_5;
            $ary['yen_1'] = $result[0]->yen_1;

            $ary['bank_a'] = $result[0]->bank_a;
            $ary['bank_b'] = $result[0]->bank_b;
            $ary['bank_c'] = $result[0]->bank_c;
            $ary['bank_d'] = $result[0]->bank_d;
            $ary['bank_e'] = $result[0]->bank_e;

            $ary['pay_a'] = $result[0]->pay_a;
            $ary['pay_b'] = $result[0]->pay_b;
            $ary['pay_c'] = $result[0]->pay_c;
            $ary['pay_d'] = $result[0]->pay_d;
            $ary['pay_e'] = $result[0]->pay_e;

            $response = implode("|", $ary);
        } else {
            $response = "-";
        }


        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function monthsummary(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $spend = DB::table('t_dailyspend')->where('year', $year)->where('month', $month)->get();
        $credit = DB::table('t_credit')->where('year', $year)->where('month', $month)->get();

        $summary2 = [];
        foreach ($spend as $v) {
            $summary2[$v->koumoku][] = $v->price;
        }
        foreach ($credit as $v) {
            $summary2[$v->item][] = $v->price;
        }

        $summary3 = [];
        $_total = [];
        foreach ($summary2 as $koumoku => $v) {
            $summary3[$koumoku]['sum'] = array_sum($v);
            $_total[] = array_sum($v);
        }
        $total = array_sum($_total);

        $summary4 = [];
        foreach ($summary3 as $koumoku => $v) {
            $summary4[$koumoku]['sum'] = $v['sum'];
            $summary4[$koumoku]['percent'] = ($v['sum'] > 0) ? number_format($v['sum'] / $total * 100, 1) : 0;
        }

        $item = $this->getItemMidashi();

        $i = 0;
        foreach ($item as $im) {
            if (isset($summary4[$im])) {
                $response[$i]['item'] = $im;
                $response[$i]['sum'] = $summary4[$im]['sum'];
//                $response[$i]['percent'] = number_format($summary4[$im]['percent'], 1);
                $response[$i]['percent'] = $summary4[$im]['percent'];
                $i++;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @return array
     */
    private function getItemMidashi()
    {
        $item = [];

        $str = "
食費
牛乳代
弁当代
住居費
交通費
支払い
credit
遊興費
ジム会費
お賽銭
交際費
雑費
教育費
機材費
被服費
医療費
美容費
通信費
保険料
水道光熱費
共済代
GOLD
投資信託
株式買付
アイアールシー
手数料
不明
メルカリ
利息
プラス
所得税
住民税
年金
国民年金基金
国民健康保険
";

        $ex_str = explode("\n", $str);
        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }
            $item[] = trim($v);
        }

        return $item;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function yearsummary(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $spend = DB::table('t_dailyspend')->where('year', $year)->get();
        $credit = DB::table('t_credit')->where('year', $year)->get();

        $summary2 = [];
        foreach ($spend as $v) {
            $summary2[$v->koumoku][] = $v->price;
        }
        foreach ($credit as $v) {
            $summary2[$v->item][] = $v->price;
        }

        $summary3 = [];
        $_total = [];
        foreach ($summary2 as $koumoku => $v) {
            $summary3[$koumoku]['sum'] = array_sum($v);
            $_total[] = array_sum($v);
        }
        $total = array_sum($_total);

        $summary4 = [];
        foreach ($summary3 as $koumoku => $v) {
            $summary4[$koumoku]['sum'] = $v['sum'];
            $summary4[$koumoku]['percent'] = number_format($v['sum'] / $total * 100, 1);
        }

        $item = $this->getItemMidashi();

        $i = 0;
        foreach ($item as $im) {
            if (isset($summary4[$im])) {
                $response[$i]['item'] = $im;
                $response[$i]['sum'] = $summary4[$im]['sum'];
                $response[$i]['percent'] = number_format($summary4[$im]['percent'], 1);
                $i++;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function uccardspend(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);
        $table = 't_article' . $year;

        $ary = [];

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%ユーシーカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $ary[$date][] = ['item' => trim($ex_val[3]), 'price' => $price, 'date' => $date, 'kind' => 'uc'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%楽天カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $ary[$date][] = ['item' => trim($ex_val[1]), 'price' => $price, 'date' => $date, 'kind' => 'rakuten'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%Amexカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $ary[$date][] = ['item' => trim($ex_val[1]), 'price' => $price, 'date' => $date, 'kind' => 'amex'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%住友カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $ary[$date][] = ['item' => trim($ex_val[1]), 'price' => $price, 'date' => $date, 'kind' => 'sumitomo'];
                }
            }
        }
        //------------------------------------------//

        $keys = array_keys($ary);
        sort($keys);

        foreach ($keys as $key) {
            foreach ($ary[$key] as $v) {
                $response[] = $v;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function allcardspend()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $ary = [];

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%ユーシーカード内訳%' ";
        }
        $sql = implode(" union all ", $_sql);
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $ary[$date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[3]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'uc',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%楽天カード内訳%' ";
        }
        $sql = implode(" union all ", $_sql);
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $ary[$date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'rakuten',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%Amexカード内訳%' ";
        }
        $sql = implode(" union all ", $_sql);
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $ary[$date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'amex',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%住友カード内訳%' ";
        }
        $sql = implode(" union all ", $_sql);
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $ary[$date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'sumitomo',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        $keys = array_keys($ary);
        sort($keys);

        foreach ($keys as $key) {
            foreach ($ary[$key] as $v) {
                $response[] = $v;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    private function getMonthDiff($date, $pay_month)
    {
        $unix_paymonth = strtotime($pay_month . "-01");

        $ex_date = explode("-", $date);
        if (!isset($ex_date[1])) {
            return 0;
        }

        list($year, $month, $day) = explode("-", $date);

        $unix_date = strtotime($year . "-" . $month . "-01");

        $ym = [];
        for ($i = $unix_paymonth; $i > $unix_date; $i -= 86400) {
            $ym[date("Y-m", $i)] = '';
        }

        $diff = count($ym) - 2;
        if ($diff < 0) {
            $diff = 0;
        }

        return $diff;
    }

    /**
     * @return mixed
     */
    public function carditemlist()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $ary = [];

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%ユーシーカード内訳%' ";
        }
        $sql = implode(" union all ", $_sql) . " order by year, month, day; ";
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);

                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    if (isset($date)) {
                        list($year, $month, $day) = explode("-", $date);
                        $date = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
                    }


                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $keyItem = (preg_match("/^ＮＴＴ/", trim($ex_val[3]))) ? "NTT" : trim($ex_val[3]);
                    $keyItem = $this->makeItemName($keyItem);

                    $ary[$keyItem . "|" . $date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[3]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'uc',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%楽天カード内訳%' ";
        }
        $sql = implode(" union all ", $_sql) . " order by year, month, day; ";
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);


                    $date = strtr(trim($ex_val[0]), ['/' => '-']);


                    if (isset($date)) {
                        list($year, $month, $day) = explode("-", $date);
                        $date = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
                    }


                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $keyItem = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                    $keyItem = $this->makeItemName($keyItem);

                    $ary[$keyItem . "|" . $date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'rakuten',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%Amexカード内訳%' ";
        }
        $sql = implode(" union all ", $_sql) . " order by year, month, day; ";
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);


                    $date = strtr(trim($ex_val[0]), ['/' => '-']);


                    if (isset($date)) {
                        list($year, $month, $day) = explode("-", $date);
                        $date = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
                    }


                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $keyItem = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                    $keyItem = $this->makeItemName($keyItem);

                    $ary[$keyItem . "|" . $date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'amex',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $_sql = [];
        foreach ($_tables as $table) {
            $_sql[] = " select * from " . $table . " where article like '%住友カード内訳%' ";
        }
        $sql = implode(" union all ", $_sql) . " order by year, month, day; ";
        $result = DB::select($sql);

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);


                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);


                    if (isset($date)) {
                        list($year, $month, $day) = explode("-", $date);
                        $date = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
                    }


                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (count(explode("-", $date)) > 0) {
                        $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);
                    } else {
                        $monthDiff = "";
                    }

                    $keyItem = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                    $keyItem = $this->makeItemName($keyItem);

                    $ary[$keyItem . "|" . $date][] = [
                        'pay_month' => $v2->year . '-' . $v2->month,
                        'item' => trim($ex_val[1]),
                        'price' => $price,
                        'date' => $date,
                        'kind' => 'sumitomo',
                        'month_diff' => $monthDiff
                    ];
                }
            }
        }
        //------------------------------------------//

        $keys = array_keys($ary);
        sort($keys);

        $lastItem = "";
        foreach ($keys as $key) {
            list($_item, $date) = explode("|", $key);
            foreach ($ary[$key] as $v) {

                $v['flag'] = ($lastItem == $_item) ? 0 : 1;

                $response[] = $v;
            }
            $lastItem = $_item;
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    private function makeItemName($im)
    {
        $im = mb_convert_kana($im, "aK");

        if (preg_match("/AMAZON.CO.JP/", $im)) {
            $im = "AMAZON";
        }

        if (preg_match("/アマソ゛ンフ゜ライムカイヒ/", $im)) {
            $im = "AMAZON PRIME会費";
        }

        if (preg_match("/アマソ゛ン/", $im)) {
            $im = "AMAZON";
        }

        if (preg_match("/AMAZON DOWNLOADS/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }

        if (preg_match("/AmazonDownloads/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }

        if (preg_match("/Amazon　Downloads/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }

        if (preg_match("/YOUTUBE/", $im)) {
            $im = "YOUTUBE";
        }

        if (preg_match("/UDEMY/", $im)) {
            $im = "UDEMY";
        }

        if (preg_match("/VULTR/", $im)) {
            $im = "VULTR";
        }

        if (preg_match("/MICROSOFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/MSFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/NTTコミュニケーションズ/", $im)) {
            $im = "NTTコミュ";
        }

        if (preg_match("/PLAYSTATION/", $im)) {
            $im = "PLAYSTATION";
        }

        if (preg_match("/投信積立/", $im)) {
            $im = "投信積立";
        }

        if (preg_match("/楽天モバイル/", $im)) {
            $im = "楽天モバイル";
        }

        if (preg_match("/甘党・辛党丸田屋/", $im)) {
            $im = "甘党・辛党丸田屋";
        }

        if (preg_match("/西友/", $im)) {
            $im = "西友ネットスーパー";
        }

        if (preg_match("/マイクロソフト/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/AMAZON WEB SERVICES/", $im)) {
            $im = "AMAZON WEB SERVICES";
        }

        if (preg_match("/PATREON/", $im)) {
            $im = "PATREON";
        }

        if (preg_match("/ストリートアカデミー/", $im)) {
            $im = "ストアカ";
        }

        if (preg_match("/お名前.com/", $im)) {
            $im = "お名前.com";
        }

        if (preg_match("/オナマエ/", $im)) {
            $im = "お名前.com";
        }

        if (preg_match("/ドットインストール/", $im)) {
            $im = "ドットインストール";
        }

        if (preg_match("/NTTコミュニケ-ションズ/", $im)) {
            $im = "NTTコミュ";
        }

        if (preg_match("/Amazonプライム会費/", $im)) {
            $im = "AMAZON PRIME会費";
        }

        if (preg_match("/GOOGLE/", $im)) {
            $im = "GOOGLE";
        }

        if (preg_match("/JCB国内利用　JCB モノタロウ/", $im)) {
            $im = "MonotaRO.com";
        }

        return $im;
    }

    /**
     * @param Request $request
     */
    public function amazonPurchaseList(Request $request)
    {
        $response = [];

        $ary3 = [];

        list($year, $month, $day) = explode("-", $request->date);
        $table = 't_article' . $year;

        $result = DB::table($table)->where('article', 'like', '%●Amazon●%')->first();

        if ($result->article) {

            $jogai_order = [
//                'D01-1902624-6860240',
//                '250-7118603-8285416',
//                '250-2037234-8323806'
            ];

            $replace = [];
            $replace['配送状況を確認'] = '';
            $replace['商品の返品'] = '';
            $replace['ギフトレシートを共有する'] = '';
            $replace['注文を非表示にする'] = '';
            $replace['商品レビューを書く'] = '';
            $replace['購買登録を管理'] = '';
            $replace['出品者を評価'] = '';
            $replace['注文の詳細'] = '';
            $replace['領収書等'] = '';
            $replace['再度購入'] = '';
            $replace['お届け先'] = '';
            $replace['豊田英之'] = '';
            $replace['コンテンツと端末の管理'] = '';
            $replace['ご注文商品を玄関にお届けしました。'] = '';
            $replace['ご注文商品の配達が完了しました。'] = '';
            $replace['注文に関する問題'] = '';
            $replace['キャンセルリクエスト'] = '';
            $replace['置き配指定'] = '';
            $replace['注文内容を表示'] = '';
            $replace['商品について質問する'] = '';
            $replace['商品を表示'] = '';

            $article = strtr($result->article, $replace);
            $ex_article = explode("注文日", $article);

            //-------------------------------------------------//
            $amazonPhoto = [];
            $file = public_path() . "/mySetting/amazonPhoto.data";
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            foreach ($ex_content as $v) {
                if (trim($v) == "") {
                    continue;
                }

                $ex_v = explode("|", trim($v));

                if (trim($ex_v[0]) != "") {
                    $amazonPhoto[trim($ex_v[1])] = trim($ex_v[0]);
                }
            }
            //-------------------------------------------------//

            $ary = [];
            $_keys = [];
            foreach ($ex_article as $k => $v) {
                if ($k == 0) {
                    continue;
                }

                $ex_v = explode("\n", $v);
                $ary2 = [];
                foreach ($ex_v as $v2) {
                    if (trim($v2) == "") {
                        continue;
                    }

                    if (preg_match("/配達しました/", trim($v2))) {
                        continue;
                    }

                    if (preg_match("/返品期間/", trim($v2))) {
                        continue;
                    }

                    if (in_array(trim($v2), $ary2)) {
                        continue;
                    }

                    $ary2[] = trim($v2);
                }

                ///////////////////////////////////
                $goukei_line_no = "";
                $purchase_line = "";
                $order_line = "";
                foreach ($ary2 as $k2 => $v2) {
                    if (trim($v2) == "合計") {
                        $goukei_line_no = $k2;
                    }

                    if (preg_match("/(.+)年(.+)月(.+)日/", trim($v2))) {
                        $purchase_line = trim($v2);
                    }

                    if (preg_match("/注文番号(.+)/", trim($v2), $m)) {
                        $order_line = trim($v2);
                    }
                }

                $price = trim(strtr($ary2[$goukei_line_no + 1], ['￥' => '', ',' => '']));

                preg_match("/(.+)年(.+)月(.+)日/", $purchase_line, $m);
                $purchase_date = sprintf("%04d", $m[1]) . "-" . sprintf("%02d", $m[2]) . "-" . sprintf("%02d", $m[3]);

                preg_match("/注文番号(.+)/", $order_line, $m);
                $order_number = trim($m[1]);

                if (in_array($order_number, $jogai_order)) {
                    continue;
                }

                $other = [];
                foreach ($ary2 as $k2 => $v2) {
                    if (trim($v2) == $ary2[$goukei_line_no + 1]) {
                        continue;
                    }

                    if (trim($v2) == $purchase_line) {
                        continue;
                    }

                    if (trim($v2) == $order_line) {
                        continue;
                    }

                    if (trim($v2) == "合計") {
                        continue;
                    }

                    $other[] = trim($v2);
                }
                ///////////////////////////////////

                $_item = "";
                foreach ($other as $v3) {
                    if (trim($v3) != "") {
                        $_item = trim($v3);
                        break;
                    }
                }

                $item = $_item;

                $img = (isset($amazonPhoto[$item])) ? $amazonPhoto[$item] : "";

                $ary[$purchase_date][] = ['date' => $purchase_date, 'price' => $price, 'order_number' => $order_number, 'item' => $item, 'img' => $img];
                $_keys[$purchase_date] = '';
            }

            $keys = array_keys($_keys);
            sort($keys);

            foreach ($keys as $key) {
                foreach ($ary[$key] as $v) {
                    $ary3[] = $v;
                }
            }
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function seiyuuPurchaseList(Request $request)
    {
        $response = [];
//
//        list($year, $month, $day) = explode("-", $request->date);
//        $table = 't_article' . $year;
//
//        ///////////////////////////////////////////////
//        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
//            ->orderBy('year')->orderBy('month')->orderBy('day')
//            ->get();
//        foreach ($result as $k => $v) {
//            $_tmp_date[$v->year . "-" . $v->month . "-" . $v->day] = "";
//        }
//        $_key_date = array_keys($_tmp_date);
//        sort($_key_date);
//        ///////////////////////////////////////////////
//
//        $ary = [];
//        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
//            ->orderBy('year')->orderBy('month')->orderBy('day')
//            ->get();
//        foreach ($result as $k => $v) {
//            $date = $v->year . "-" . $v->month . "-" . $v->day;
//            $ex_article = explode(">", $v->article);
//            for ($i = 1; $i < count($ex_article); $i++) {
//                $ex_ex_article = explode("\n", $ex_article[$i]);
//
//                $item = trim($ex_ex_article[1]);
//
//                if (preg_match("/^【店内】/", $item)) {
//
//                    $tanka = trim(strtr($ex_ex_article[6], ['円' => '', ',' => '']));
//                    $kosuu = trim($ex_ex_article[7]);
//                    $price = trim(strtr($ex_ex_article[8], ['円' => '', ',' => '']));
//                } else {
//                    $tanka = trim(strtr($ex_ex_article[7], ['円' => '', ',' => '']));
//                    $kosuu = trim($ex_ex_article[8]);
//                    $price = trim(strtr($ex_ex_article[9], ['円' => '', ',' => '']));
//                }
//
//                $pos = array_search($date, $_key_date);
//
//                $ary[] = [
//                    'date' => $date,
//                    'pos' => $pos,
//                    'item' => $item,
//                    'tanka' => $tanka,
//                    'kosuu' => $kosuu,
//                    'price' => $price
//                ];
//            }
//        }

        $ary = $this->getSeiyuuData($request->date);
        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    private function getSeiyuuData($date)
    {

        list($year, $month, $day) = explode("-", $date);
        $table = 't_article' . $year;

        ///////////////////////////////////////////////
        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();
        foreach ($result as $k => $v) {
            $_tmp_date[$v->year . "-" . $v->month . "-" . $v->day] = "";
        }
        $_key_date = array_keys($_tmp_date);
        sort($_key_date);
        ///////////////////////////////////////////////

        //--------------------------------------------
        $seiyuPhoto = [];
        $file = public_path() . "/mySetting/seiyuPhoto.data";
        $content = file_get_contents($file);
//        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            $ex_v = explode("|", trim($v));
            $seiyuPhoto[trim($ex_v[1])] = trim($ex_v[0]);
        }
        //--------------------------------------------

        $ary = [];
        $result = DB::table($table)->where('article', 'like', '%西友ネットスーパー内訳%')
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();
        foreach ($result as $k => $v) {
            $date = $v->year . "-" . $v->month . "-" . $v->day;
            $ex_article = explode(">", $v->article);
            for ($i = 1; $i < count($ex_article); $i++) {
                $ex_ex_article = explode("\n", $ex_article[$i]);

                $item = trim($ex_ex_article[1]);

                if (preg_match("/^【店内】/", $item)) {

                    $tanka = trim(strtr($ex_ex_article[6], ['円' => '', ',' => '']));
                    $kosuu = trim($ex_ex_article[7]);
                    $price = trim(strtr($ex_ex_article[8], ['円' => '', ',' => '']));
                } else {
                    $tanka = trim(strtr($ex_ex_article[7], ['円' => '', ',' => '']));
                    $kosuu = trim($ex_ex_article[8]);
                    $price = trim(strtr($ex_ex_article[9], ['円' => '', ',' => '']));
                }

                $pos = array_search($date, $_key_date);

                $imgItem = strtr(trim($item), ['　（非食品）' => '']);
                $img = (isset($seiyuPhoto[$imgItem])) ?
                    $seiyuPhoto[$imgItem] : "";

                $ary[] = [
                    'date' => $date,
                    'pos' => $pos,
                    'item' => $item,
                    'tanka' => $tanka,
                    'kosuu' => $kosuu,
                    'price' => $price,
                    'img' => $img
                ];
            }
        }

        return $ary;
    }

    /**
     * @param Request $request
     */
    public function seiyuuPurchaseItemList(Request $request)
    {
        $response = [];
        $response2 = [];

        $ary = $this->getSeiyuuData($request->date);

        list($year, $month, $day) = explode("-", $request->date);

        $tma = Carbon::now()->subMonth(2)->format('Y-m-d');
        //$twoMonthAgo = new Carbon($tma);
        $ex_tma = explode("-", $tma);
        $twoMonthAgo = strtotime(date("$ex_tma[0]-$ex_tma[1]-01"));

        $ary2 = [];
        $ary3 = [];
        foreach ($ary as $v) {
            if ($v["item"] == "送料") {
                continue;
            }

            $ary2[$v["item"]][] = $v["date"] . "|" . $v["tanka"] . "|" . $v["kosuu"] . "|" . $v["price"];

//            $hikaku = new Carbon($v["date"]);
            $hikaku = strtotime($v["date"]);

            if ($hikaku > $twoMonthAgo) {
                $ary3[] = $v["item"];
            }
        }

        $ary4 = [];
        $ary5 = [];
        foreach ($ary2 as $item => $v) {
            if (in_array($item, $ary3)) {
                $ary4[$item] = $v;
            } else {
                $ary5[$item] = $v;
            }
        }

        $ary6 = [];
        foreach ($ary4 as $item => $v) {
            $str = implode("/", $v);
            $ary6[] = $item . ":" . $str;
        }

        $ary7 = [];
        foreach ($ary5 as $item => $v) {
            $str = implode("/", $v);
            $ary7[] = $item . ":" . $str;
        }


        $response = $ary6;
        $response2 = $ary7;


        return response()->json(['data' => $response, 'data2' => $response2]);
    }

    /**
     * @param Request $request
     */
    public function dutyData(Request $request)
    {
        $response = [];

        $dutyItems = ['所得税', '住民税', '年金', '国民年金基金', '国民健康保険'];

        $ary = [];
        foreach ($dutyItems as $duty) {
            $spend = DB::table('t_dailyspend')->where('koumoku', '=', $duty)
                ->where('year', '>=', '2020')
                ->orderBy('year')->orderBy('month')->orderBy('day')
                ->get();
            $credit = DB::table('t_credit')->where('item', '=', $duty)
                ->where('year', '>=', '2020')
                ->orderBy('year')->orderBy('month')->orderBy('day')
                ->get();

            $ary2 = [];
            $date = [];
            foreach ($spend as $v) {
                $ary2[$v->year . '-' . $v->month . '-' . $v->day][] = $v->price;
                $date[] = $v->year . '-' . $v->month . '-' . $v->day;
            }

            foreach ($credit as $v) {
                $ary2[$v->year . '-' . $v->month . '-' . $v->day][] = $v->price;
                $date[] = $v->year . '-' . $v->month . '-' . $v->day;
            }

            sort($date);

            foreach ($date as $dt) {
                foreach ($ary2[$dt] as $_price) {
                    $ary[$duty][] = $dt . "|" . $_price;
                }
            }


        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function yachinData(Request $request)
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $ary = [];
        foreach ($_tables as $table) {
            $result = DB::table($table)->where('article', 'like', '%水道光熱費内訳%')
                ->orderBy('year')->orderBy('month')->orderBy('day')
                ->get();
            foreach ($result as $v) {
                if ($v->year >= 2020) {
                    $ex_article = explode("\n", $v->article);

                    $ary2 = [
                        'date' => $v->year . '-' . $v->month,

                        'yachin' => 0,
                        'yachin_date' => '-',

                        'electric' => 0,
                        'electric_date' => '-',

                        'gas' => 0,
                        'gas_date' => '-',

                        'water' => 0,
                        'water_date' => '-',
                    ];

                    foreach ($ex_article as $v2) {
                        if (preg_match("/\((.+)\) 水道光熱費 (.+)円\/\/電気代/", trim($v2), $m)) {
                            $ary2['electric_date'] = $m[1];
                            $ary2['electric'] = strtr(trim($m[2]), [',' => '']);
                        }

                        if (preg_match("/\((.+)\) 水道光熱費 (.+)円\/\/ガス代/", trim($v2), $m)) {
                            $ary2['gas_date'] = $m[1];
                            $ary2['gas'] = strtr(trim($m[2]), [',' => '']);
                        }

                        if (preg_match("/\((.+)\) 水道光熱費 (.+)円\/\/水道代/", trim($v2), $m)) {
                            $ary2['water_date'] = $m[1];
                            $ary2['water'] = strtr(trim($m[2]), [',' => '']);
                        }
                    }

                    //----------------------------//
                    $result2 = DB::table('t_credit')
                        ->where('item', '=', '住居費')
                        ->where('year', '=', $v->year)->where('month', '=', $v->month)
                        ->first();
                    $ary2['yachin_date'] = $result2->day;
                    $ary2['yachin'] = $result2->price;
                    //----------------------------//

                    $ary[] = $ary2;
                }
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getMonthlyBankRecord(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->get();

        foreach ($result as $k => $v) {
            $response[$k]['day'] = $v->day;
            $response[$k]['item'] = $v->item;
            $response[$k]['price'] = $v->price;
            $response[$k]['bank'] = $v->bank;
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getgolddata()
    {
        $response = [];

        $midashi = ['year', 'month', 'day', 'gold_tanka', 'gram_num', 'gold_price'];

        $result = DB::table('t_gold')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary2 = [];
        $last_tanka = 0;

        foreach ($result as $k => $v) {
            $ary = [];
            foreach ($midashi as $v2) {
                switch ($v2) {
                    case "gold_tanka":
                        $ary['gold_tanka'] = $v->gold_tanka;
                        if ($v->gold_tanka > $last_tanka) {
                            $diff = ($v->gold_tanka - $last_tanka);
                            $ary['up_down'] = 1;
                            $ary['diff'] = $diff;
                        } else {
                            $diff = ($last_tanka - $v->gold_tanka) * -1;
                            $ary['up_down'] = 0;
                            $ary['diff'] = $diff;
                        }
                        break;
                    case "gram_num":
                        $ary['gram_num'] = round($v->gram_num, 5);
                        $sql = " select sum(gram_num) as total_gram, sum(gold_price) as pay_price from t_gold where id <= " . $v->id . "; ";
                        $_total = DB::select($sql);
                        $ary['total_gram'] = round($_total[0]->total_gram, 5);

                        //
                        $ary['gold_value'] = floor($v->gold_tanka * $_total[0]->total_gram);

                        //
                        $ary['pay_price'] = $_total[0]->pay_price;
                        break;

                    default:
                        $ary[$v2] = $v->$v2;
                        break;
                }
            }


            $ary2[$v->year . "-" . $v->month . "-" . $v->day] = $ary;

            $last_tanka = $v->gold_tanka;

        }

        $j = 0;
        $l = 0;
        for ($i = strtotime("2021-01-01"); $i <= strtotime(date("Y-m-d")); $i += 86400) {


            if (isset($ary2[date("Y-m-d", $i)])) {
                if ($l == 0) {
                    $ary3 = $ary2[date("Y-m-d", $i)];
                    $ary3['diff'] = "-";
                    $ary3['up_down'] = 9;
                    $response[$j] = $ary3;
                } else {
                    $response[$j] = $ary2[date("Y-m-d", $i)];
                }
                $l++;
            } else {
                $response[$j] = [
                    'year' => date("Y", $i),
                    'month' => date("m", $i),
                    'day' => date("d", $i),
                    'gold_tanka' => '-', 'up_down' => '-', 'diff' => '-', 'gram_num' => '-', 'total_gram' => '-',
                    'gold_value' => '-', 'gold_price' => '-', 'pay_price' => '-'
                ];
            }

            $j++;
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */

    /*
    public function getITFRecord(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $sql = "
select
year,month,day,koumoku item,price
from
t_dailyspend
where
koumoku = '投資信託'
union all
select
year,month,day,item,price
from
t_credit
where
item = '投資信託'
";

        $result = DB::select($sql);

        foreach ($result as $k => $v) {
            if (strtotime(trim($v->year) . "-" . trim($v->month) . "-" . trim($v->day)) > strtotime($request->date)) {
                continue;
            }

            $response[$k]["date"] = trim($v->year) . "-" . trim($v->month) . "-" . trim($v->day);
            $response[$k]["price"] = trim($v->price);
        }

        return response()->json(['data' => $response]);
    }
*/


    /**
     * @param Request $request
     */

    /*
    public function getITFPrice(Request $request)
    {

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $answer = "";
        foreach ($_tables as $table) {
            $sql = " select * from $table where article like 'ITF金額%'; ";
            $result = DB::select($sql);
            foreach ($result as $v) {
                $ex_article = explode("\n", $v->article);

                $ary = [];
                $ary[] = trim($ex_article[1]);
                $ary[] = $v->year . "-" . $v->month . "-" . $v->day;
                $ary[] = trim(strtr($ex_article[2], ['[' => '', ']' => '']));
                $answer = implode("|", $ary);
            }
        }

        $response = $answer;
        return response()->json(['data' => $response]);
    }
*/

    /**
     *
     */
    public function mercaridata()
    {
        $response = [];

        $result = DB::table('t_mercari')
            ->orderBy('settlement_at')
            ->get();

        $ary = [];
        $gain = [];
        $tot = 0;
        $total = [];
        foreach ($result as $v) {
            $dep = (trim($v->departured_at) != "") ? date("Y-m-d H", strtotime($v->departured_at)) : "";
            $sett = (trim($v->settlement_at) != "") ? date("Y-m-d H", strtotime($v->settlement_at)) : "";
            $rec = (trim($v->receive_at) != "") ? date("Y-m-d H", strtotime($v->receive_at)) : "";

            $title = strtr($v->title, ['(' => '（', ')' => '）', '/' => '／']);

            $ary[date("Y-m-d", strtotime($v->settlement_at))][] = "$v->buy_sell|$title|$v->sell_price|$v->tesuuryou|$v->shipping_fee|$v->price|$dep|$sett|$rec";

            $price = ($v->buy_sell == "sell") ? $v->price : ($v->price * -1);
            $gain[date("Y-m-d", strtotime($v->settlement_at))][] = $price;

            $tot += $price;
            $total[date("Y-m-d", strtotime($v->settlement_at))] = $tot;
        }

        $ary2 = [];
        $i = 0;
        foreach ($ary as $date => $v) {
            $ary2[$i]['date'] = $date;
            $ary2[$i]['record'] = implode("/", $v);
            $ary2[$i]['day_total'] = array_sum($gain[$date]);
            $ary2[$i]['total'] = $total[$date];
            $i++;
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getFundRecord(Request $request)
    {
        $response = [];

        $youbi = ['日', '月', '火', '水', '木', '金', '土'];

        $result2 = DB::table('t_fund')
            ->orderBy('fundname')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        $ary2 = [];

        foreach ($result2 as $v2) {
            $date = "$v2->year-$v2->month-$v2->day";
            $ary2[] = $date;

            $_youbi = $youbi[date("w", strtotime($date))];
            $flag = (preg_match("/\+/", trim($v2->compare_front))) ? 1 : 0;

            $ary9 = [];
            $ary9[] = $date;
            $ary9[] = $v2->base_price;
            $ary9[] = $v2->compare_front;
            $ary9[] = $v2->yearly_return;
            $ary9[] = $flag;

            $ary[$v2->fundname][$date] = implode("|", $ary9);
        }

        $date_min = $ary2[0];
        $date_max = $ary2[count($ary2) - 1];

        $ary3 = [];
        foreach ($ary as $name => $v) {
            for ($i = strtotime($date_min); $i <= strtotime($date_max); $i += 86400) {
                if (isset($v[date("Y-m-d", $i)])) {
                    $ary3[$name][date("Y-m-d", $i)] = $v[date("Y-m-d", $i)];
                } else {
                    $ary3[$name][date("Y-m-d", $i)] = date("Y-m-d", $i) . "|-|-|-|-";
                }
            }
        }

        $ary4 = [];
        $keep_name = "";
        foreach ($ary3 as $name => $v) {

            if ($keep_name != $name) {
                $check = false;
            }

            foreach ($v as $date => $v2) {
                if ($check == false) {
                    if (!preg_match("/\|-\|-/", $v2)) {
                        $check = true;
                    }
                }

                if ($check == true) {
                    $ary4[$name][$date] = $v2;
                }
            }

            $keep_name = $name;
        }

        $ary5 = [];
        foreach ($ary4 as $name => $v) {
            $ary5[] = $name . ":" . implode("/", $v);
        }

        $response = $ary5;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function stockdataexists(Request $request)
    {
        list($date,) = explode(" ", $request->date);
        $result = DB::table('t_stock')->where('created_at', 'like', $date . '%')->first();
        return response()->json(['data' => (!empty($result)) ? 1 : 0]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function stockdatedata(Request $request)
    {
        $response = [];

        //日次のデータを順位順に取得
        list($date,) = explode(" ", $request->date);
        $result = DB::table('t_stock')->where('created_at', 'like', $date . " " . $request->time . '%')->orderBy('id')->get();

        foreach ($result as $v) {
            $str = $v->rank . "|";
            $str .= $v->code . "|";
            $str .= $v->company . "（" . $v->industry . "）|";
            $str .= $v->grade . "|";
            $str .= $v->torihikichi . "|";
            $str .= (trim($v->tangen) == "") ? '-' : $v->tangen;
            $str .= "|";
            $str .= $v->market . "|";
            $str .= $v->isCountOverTwo . "|";
            $str .= $v->isUpper;

            $response[] = $str;
        }
        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function stockgradedata(Request $request)
    {
        $response = [];

        //gradeのデータを最新日付優先で取得
        $result = DB::table('t_stock')->where('grade', '=', $request->grade)->orderBy('id', 'desc')->get();
        $ary = [];
        foreach ($result as $v) {
            if (isset($ary[$v->code])) {
                continue;
            }

            $str = $v->code . "|";
            $str .= $v->company . "|";
            $str .= $v->industry . "|";
            $str .= date("Y-m-d H", strtotime($v->created_at)) . "|";
            $str .= $v->torihikichi . "|";
            $str .= (trim($v->tangen) == "") ? '-' : $v->tangen;
            $str .= "|";
            $str .= $v->market . "|";
            $str .= $v->isCountOverTwo . "|";
            $str .= $v->isUpper;

            $response[] = $str;

            $ary[$v->code] = '';
        }
        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function stockcodedata(Request $request)
    {
        $response = [];

        //codeのデータを日付、時間順に取得
        $result = DB::table('t_stock')->where('code', '=', $request->code)->orderBy('id')->get();

        if (isset($result[0])) {
            $response['code'] = $result[0]->code;
            $response['company'] = $result[0]->company;
            $response['industry'] = $result[0]->industry;
            $response['tangen'] = $result[0]->tangen;
            $response['market'] = $result[0]->market;

            //全codeデータ、同じ値が入っている
            $response['isCountOverTwo'] = $result[0]->isCountOverTwo;
            $response['isUpper'] = $result[0]->isUpper;

            //登場当初のgrade
            $response['grade'] = $result[0]->grade;

            //--------------------------------------//
            //日付、時間のprice
            $tmp = [];
            foreach ($result as $v) {
                $ymd = date("Y-m-d", strtotime($v->created_at));
                $hour = date("H", strtotime($v->created_at));
                $tmp[$ymd][$hour] = $v->torihikichi;

                $response['lastPrice'] = $v->torihikichi;
            }

            $start = strtotime($request->date . "-01");

            $monthEnd = date("t", strtotime($request->date));
            $end = strtotime(date($request->date . "-" . $monthEnd));

            for ($i = $start; $i <= $end; $i += 86400) {
                $date = date("Y-m-d", $i);

                $price = [];
                for ($j = 9; $j <= 15; $j++) {
                    $value = (isset($tmp[$date][sprintf("%02d", $j)])) ? $tmp[$date][sprintf("%02d", $j)] : '-';
                    $price[] = $value;
                }

                $response['price'][] = $date . "|" . implode("|", $price);
            }
            //--------------------------------------//
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function stockindustrylistdata(Request $request)
    {
        $response = [];

        //industryの合計sumの多い順に取得
        $sql = " select industry,sum(point) sum from t_stock group by industry order by sum desc; ";
        $result = DB::select($sql);

        $i = 0;
        foreach ($result as $v) {
            if (trim($v->industry) == "") {
                continue;
            }

            $response[$i]['industry'] = $v->industry;
            $response[$i]['sum'] = $v->sum;
            $i++;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function stockindustrydata(Request $request)
    {
        $response = [];

        $sql = " select ";
        $sql .= " code, company, grade, market, tangen, sum(point) sum ";
        $sql .= " from ";
        $sql .= " t_stock ";
        $sql .= " where ";
        $sql .= " industry = '" . $request->industry . "' ";
        $sql .= " group by code, company, grade, market, tangen ";
        $sql .= " order by sum desc; ";

        $result = DB::select($sql);

        $i = 0;
        foreach ($result as $v) {

            if ($i > 9) {
                break;
            }

            if (isset($request->grade)) {
                if ($request->grade != $v->grade) {
                    continue;
                }
            }

            $response[$i]['code'] = $v->code;
            $response[$i]['company'] = $v->company;
            $response[$i]['market'] = $v->market;
            $response[$i]['tangen'] = $v->tangen;
            $response[$i]['sum'] = $v->sum;

            //codeの最新のレコードを取得
            $result2 = DB::table('t_stock')->where('code', '=', $v->code)->orderBy('id', 'desc')->first();
            $response[$i]['price'] = $result2->torihikichi;
            $response[$i]['grade'] = $result2->grade;
            $response[$i]['date'] = date("Y-m-d H", strtotime($result2->created_at));

            //全レコード同じ値が入っている
            $response[$i]['isUpper'] = $result2->isUpper;

            $i++;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function stockpricedata(Request $request)
    {

        $response = [];

        ////////////////////////////////////////
        $start = strtotime("2020-09-29");
        $end = strtotime(date("Y-m-d"));

        $_threedays = [];
        for ($i = $end; $i >= $start; $i -= 86400) {
            $date = date("Y-m-d", $i);

            $result = DB::table('t_stock')->where('created_at', 'like', $date . '%')->first();
            if (!empty($result)) {
                $_threedays[] = $date;
            }
        }

        $threedays = [
            $_threedays[0],
            $_threedays[1],
            $_threedays[2]
        ];

        sort($threedays);
        ////////////////////////////////////////

        $start2 = $threedays[0] . " 00:00:00";
        $end2 = $threedays[count($threedays) - 1] . " 23:59:59";

        $sql = "";
        $sql .= " select * from t_stock where (created_at >='" . $start2 . "' and created_at < '" . $end2 . "') ";
        $sql .= " and torihikichi < " . $request->price . " ";
        $sql .= " and tangen is not null and tangen !='' ";
        $sql .= " order by torihikichi desc, id desc; ";

        $result = DB::select($sql);

        $_code = [];
        $_answer = [];
        $_priceguide = [];
        foreach ($result as $v) {
            if (in_array($v->code, $_code)) {
                continue;
            }

            $_priceguide[$v->torihikichi] = "";

            $ary = [];
            $ary[] = $v->code;
            $ary[] = $v->market;
            $ary[] = $v->company;

            $ary[] = $v->torihikichi;//最新日時のデータ
            $ary[] = $v->grade;//最新日時のデータ

            $ary[] = $v->industry;
            $ary[] = $v->tangen;
            $ary[] = $v->isCountOverTwo;
            $ary[] = $v->isUpper;

            $ary[] = date("Y-m-d H", strtotime($v->created_at));

            $_answer[$v->torihikichi][] = implode("|", $ary);

            $_code[] = $v->code;
        }

        $keys = array_keys($_priceguide);
        rsort($keys);

        $i = 0;
        foreach ($keys as $price) {
            foreach ($_answer[$price] as $v) {
                $response[$i] = $v;
                $i++;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function stockalldata(Request $request)
    {
        $response = [];

        //codeのデータを日時順に取得
        $result = DB::table('t_stock')->where('code', '=', $request->code)->orderBy('id')->get();

        $_lastPrice = 99999;
        foreach ($result as $k => $v) {

            //最新レコードの値
            $response['code'] = $v->code;
            $response['market'] = $v->market;
            $response['company'] = $v->company;
            $response['industry'] = $v->industry;
            $response['tangen'] = $v->tangen;
            $response['grade'] = $v->grade;

            //全レコード、各行ずつ
            $ary = [];
            $ary[] = date("Y-m-d H", strtotime($v->created_at));
            $ary[] = $v->torihikichi;
            $ary[] = ($v->torihikichi > $_lastPrice) ? 1 : 0;
            $response['price'][$k] = implode("|", $ary);

            $_lastPrice = $v->torihikichi;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getYearSpendSummay(Request $request)
    {
        $response = [];

        $item = $this->getItemMidashi();

        $ary = [];
        foreach ($item as $im) {
            for ($i = 1; $i <= 12; $i++) {

                $year = $request->year;
                $month = sprintf("%02d", $i);

                $sql1 = " select sum(price) as sum from t_dailyspend where koumoku = '{$im}' and year = '{$year}' and month = '{$month}'; ";
                $ans1 = DB::select($sql1);

                $sql2 = " select sum(price) as sum from t_credit where item = '{$im}' and year = '{$year}' and month = {$month}; ";
                $ans2 = DB::select($sql2);

                $ary[$im][$month] = ($ans1[0]->sum + $ans2[0]->sum);

            }
        }

        $response = ['midashi' => $item, 'summary' => $ary];

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getYearCreditSummay(Request $request)
    {
        $response = [];

        $credit = $this->getYearCredit($request->year);

        $item = $credit[0];
        $ary2 = $credit[1];

        sort($item);

        $response = ['midashi' => $item, 'summary' => $ary2];

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    private function getYearCredit($year)
    {

        $table = 't_article' . $year;

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%ユーシーカード内訳%')->get();

        $ary = [];
        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[3]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%楽天カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%Amexカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%住友カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[1]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        $ary2 = [];
        $item = [];
        foreach ($ary as $im => $v) {

            $item[] = $im;

            for ($i = 1; $i <= 12; $i++) {
                $month = sprintf("%02d", $i);
                $sum = (isset($ary[$im][$month])) ? array_sum($ary[$im][$month]) : 0;
                $ary2[$im][$month] = $sum;
            }
        }

        return [$item, $ary2];

    }

    /**
     *
     */
    public function itemDetailDisplay(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $spend = DB::table('t_dailyspend')
            ->where('koumoku', '=', $request->item)
            ->where('year', $year)->where('month', $month)
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $credit = DB::table('t_credit')
            ->where('item', '=', $request->item)
            ->where('year', $year)->where('month', $month)
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $summary2 = [];
        foreach ($spend as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $summary2[$date][] = $date . "|" . $v->price;
        }
        foreach ($credit as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $summary2[$date][] = $date . "|" . $v->price;
        }

        $dt = array_keys($summary2);

        $summary3 = [];
        foreach ($dt as $_dt) {
            $value = $summary2[$_dt];
            sort($value);
            foreach ($value as $v2) {
                list($dat, $prc) = explode("|", $v2);
                if ($prc == 0) {
                    continue;
                }
                $summary3[] = [
                    'date' => $dat,
                    'price' => $prc
                ];
            }
        }

        $response = $summary3;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     */
    public function getYearCreditCommonItem()
    {
        $response = [];

        $items = [];
        $j = 0;
        for ($i = 2020; $i <= date("Y"); $i++) {
            $credit = $this->getYearCredit($i);

            foreach ($credit[0] as $im) {
                $items[] = $im;
            }

            $j++;
        }

        $ary = array_count_values($items);
        $ary2 = [];
        foreach ($ary as $k => $v) {
            if ($v == $j) {
                $ary2[] = $k;
            }
        }

        sort($ary2);

        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function getSamedaySpend(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", trim($request->date));

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));

        $ary = [];
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            list($aYear, $aMonth, $aDay) = explode("-", trim($date));

            if ($aYear < 2020) {
                continue;
            }

            if ($aDay > $day) {
                continue;
            }

            $ym = "{$aYear}-{$aMonth}";

            $ary[$ym][] = $spend;
        }


        //---------------------------------------//
        $result = DB::table('t_salary')
            ->where('day', '<=', $day)
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();

        foreach ($result as $v) {

            if ($v->year < 2020) {
                continue;
            }

            $ary["{$v->year}-{$v->month}"][] = $v->salary;
        }
        //---------------------------------------//

        $ary2 = [];
        $i = 0;
        foreach ($ary as $_ym => $v) {
            $sum = array_sum($v);
            if ($sum < 0) {
                $sum = 0;
            }
            $ary2[$i] = [
                'ym' => $_ym,
                'sum' => $sum
            ];

            $i++;
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     */
    public function worktimemonthdata(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_worktime')
            ->where('year', '=', $year)->where('month', '=', $month)
            ->orderBy('day')->get();

        foreach ($result as $v) {
            $date = $v->year . '-' . $v->month . '-' . $v->day;

            $response[] = [
                "date" => $date,
                'work_start' => date("H:i", strtotime($v->work_start)),
                'work_end' => date("H:i", strtotime($v->work_end)),
            ];
        }

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     */
    public function workingmonthdata(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_worktime')
            ->where('year', '=', $year)->where('month', '=', $month)
            ->orderBy('day')->get();

        foreach ($result as $k => $v) {
            $date = $v->year . '-' . $v->month . '-' . $v->day;

            $response[$k]['date'] = $date;
            $response[$k]['work_start'] = date("H:i", strtotime($v->work_start));
            $response[$k]['work_end'] = date("H:i", strtotime($v->work_end));
        }

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function worktimesummary()
    {

        //------------------------------//
        $genbaName = $this->makeGenbaNameAry($this->getGenbaName());
        //------------------------------//
        $worktimeSalary = $this->getWorktimeSalary();
        //------------------------------//

        $result = DB::table('t_worktime')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        $ary2 = [];
        $ary3 = [];

        $hit_start = "2021-02-01";
        $hit_end = "2021-09-30";

        $noRest = [
            '2015-10-27',
            '2016-01-19',
            '2016-02-10',
            '2016-02-16',
            '2016-02-23',
            '2016-05-19',
            '2016-05-25',
            '2016-06-13',
            '2016-09-09',
            '2016-11-11',
            '2017-02-06',
            '2017-03-29',
            '2017-04-04',
            '2017-04-19',
            '2017-04-24',
            '2017-05-22',
            '2017-10-02',
            '2017-12-13',
            '2018-02-27',
            '2019-01-10',
            '2019-02-27',
            '2019-07-17',
            '2019-09-09',
        ];


        $lastYm = '';


        foreach ($result as $v) {
            ///////////////////////////
            $date = $v->year . "-" . $v->month . "-" . $v->day;

            $rest = 60;
            if ((strtotime($date) >= strtotime($hit_start)) && (strtotime($date) <= strtotime($hit_end))) {
                //hit
                $rest = (strtotime($v->work_end) > strtotime("17:30:00")) ? 90 : 60;
            }

            $seconds = strtotime($v->work_end) - strtotime($v->work_start);

            if (in_array($date, $noRest)) {
                $rest = 0;
            }

            $worktime = round(($seconds - ($rest * 60)) / 3600, 2);

            if (count(explode(".", $worktime)) == 1) {
                $worktime .= ".0";
            }

            $strange = 0;
            $strange = $this->startStrangeCheck($v);

            $ary7 = [];
            $ary7[] = date("H:i", strtotime($v->work_start));
            $ary7[] = date("H:i", strtotime($v->work_end));
            $ary7[] = $worktime;
            $ary7[] = $rest;
            $ary7[] = date("w", strtotime($date));
            $ary7[] = $strange;

            $ary[$date] = implode("|", $ary7);
            ///////////////////////////

            $ym = $v->year . "-" . $v->month;
            $ary2[$ym] = "";

            $ary3[$ym][] = ($seconds - ($rest * 60));

            $lastYm = $ym;
        }

        $ary3["2019-11"][0] = "";
        $ary3["2021-10"][0] = "";
        $ary3["2022-07"][0] = "";


        if ($lastYm != date("Y-m")) {
            $ary3[date("Y-m")][0] = "";
        }


        $ary4 = [];
        foreach ($ary3 as $ym => $v) {
            $ary4[$ym] = round(array_sum($v) / 3600, 2);
        }

        ksort($ary4);

        $youbi = ["日", "月", "火", "水", "木", "金", "土"];

        $ary5 = [];
        foreach ($ary4 as $ym => $v) {
            $monthEnd = date("t", strtotime($ym));

            for ($i = 1; $i <= $monthEnd; $i++) {
                $date = $ym . "-" . sprintf("%02d", $i);
                $w = date("w", strtotime($date));
                $ary5[$ym][sprintf("%02d", $i)] = (isset($ary[$date])) ?
                    sprintf("%02d", $i) . "($youbi[$w])|" . $ary[$ym . "-" . sprintf("%02d", $i)] :
                    sprintf("%02d", $i) . "($youbi[$w])|||||$w|0";
            }
        }

        $ary6 = [];
        foreach ($ary5 as $ym => $v) {
            $str = implode("/", $v);
            $summary = $ary4[$ym];
            if (count(explode(".", $summary)) == 1) {
                $summary .= ".0";
            }

            $company = "";
            $genba = "";
            if (isset($genbaName[$ym])) {
                $company = $genbaName[$ym]['company'];
                $genba = $genbaName[$ym]['genba'];
            }

            $salary = "";
            $hour = "";
            if (isset($worktimeSalary[$ym])) {
                $salary = $worktimeSalary[$ym];
                $hour = ($summary != 0) ? floor($salary / $summary) : 0;
            }

            $ary6[] = $ym . ";" . $summary . ";$company;$genba;$salary;$hour;" . $str;
        }

        $response = $ary6;
        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    private function makeGenbaNameAry($gAry)
    {
        $ret = [];
        foreach ($gAry as $v) {
            $ret[$v['yearmonth']]['company'] = $v['company'];
            $ret[$v['yearmonth']]['genba'] = $v['genba'];
        }

        return $ret;
    }

    /**
     *
     */
    private function getGenbaName()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $cnt = 0;
        foreach ($_tables as $table) {
            $result = DB::table($table)->where('article', 'like', '%★職歴まとめ%')->get();

            if (!empty($result[0])) {
                $ex_article = explode("\n", $result[0]->article);
                foreach ($ex_article as $art) {
                    if (trim($art) == "") {
                        continue;
                    }

                    $ex_art = explode("\t", trim($art));
                    if (count($ex_art) > 1) {
                        list($year, $month, $day, $juukyo, $single, $company, $genba, $kyogi) = explode("\t", trim($art));
                        if (is_numeric($year)) {
                            $response[$cnt]['yearmonth'] = $year . "-" . sprintf("%02d", $month);
                            $response[$cnt]['company'] = $company;
                            $response[$cnt]['genba'] = $genba;
                            $cnt++;
                        }
                    }
                }
            }
        }

        return $response;
    }

    /**
     * @return array
     */
    private function getWorktimeSalary()
    {
        $ret = [];

        $paymentTerm = [
            'SBC' => 1,
            'ギークス' => 1,
            'レバテック' => 1,
            'アンコンサルティング' => 2,
            'ジェニュイン' => 2,

            'フォスターネット' => 1,
            'マノア・リノ' => 2,

        ];

        $result = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ret2 = [];
        foreach ($result as $v) {
            $date = $v->year . "-" . $v->month . "-" . $v->day;
            $first = date("Y-m-01", strtotime($date));
            $dt = new Carbon($first);

            if (!isset($paymentTerm[$v->company])) {
                continue;
            }

            $sub = $dt->subMonth($paymentTerm[$v->company]);
            $ret2[$sub->format("Y-m")][] = $v->salary;
        }

        foreach ($ret2 as $ym => $v) {
            $ret[$ym] = array_sum($v);
        }

        return $ret;
    }

    /**
     * @param $vwork_start
     */
    private function startStrangeCheck($data)
    {
        $hit_end = date("Y-m-d");

        $date = $data->year . "-" . $data->month . "-" . $data->day;

        $checkValue = [
            '2014-10-15 / 2014-10-31' => '10:00',//SBC社内
            '2014-11-01 / 2014-12-10' => '09:00',//大門
            '2014-12-16 / 2015-03-31' => '09:00',//新宿
            '2015-04-01 / 2015-07-17' => '09:00',//求人
            '2015-07-21 / 2015-09-30' => '10:00',//ラボ
            '2015-10-01 / 2016-05-31' => '09:30',//蒲田
            '2016-06-01 / 2019-09-30' => '09:30',//光
            '2019-10-01 / 2019-10-31' => '10:00',//しまうま
            '2019-12-01 / 2020-04-17' => '10:00',//ベルシステム24
            '2020-05-18 / 2020-06-30' => '10:00',//エクスチェンジ
            '2020-07-01 / 2020-09-30' => '11:00',//リヴァンプ
            '2020-10-01 / 2020-11-30' => '10:00',//バルール
            '2020-12-01 / 2021-01-31' => '09:30',//KMS
            '2021-02-01 / 2021-09-30' => '09:00',//HIT
            '2021-11-01 / ' . $hit_end => '10:00'//epark
        ];

        $strange = 0;
        foreach ($checkValue as $k => $v) {
            list($day_start, $day_end) = explode(" / ", $k);

            if (strtotime($date) >= strtotime($day_start) && strtotime($date) <= strtotime($day_end)) {
                if (date("H:i", strtotime($data->work_start)) != $v) {
                    $strange = 1;
                }
            }
        }

        return $strange;
    }

    /**
     * @return mixed
     */
    public function getGenbaWorkTime()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $ary = [];
        foreach ($_tables as $table) {
            $result = DB::table($table)->where('article', 'like', '%★現場の勤務時間★%')->get();

            if (!empty($result[0])) {

                $ex_article = explode("\n", trim($result[0]->article));

                for ($i = 1; $i < count($ex_article); $i++) {
                    list($company, $genba, $startTime) = explode("\t", trim($ex_article[$i]));

                    $endTime = date("H:i", strtotime($startTime) + (60 * 60 * 9));


                    $ary[] = [
                        'company' => $company,
                        'genba' => $genba,
                        'start' => $startTime,
                        'end' => $endTime,
                    ];


                }
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function worktimeinsert(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            $data = [
                'work_start' => $request->work_start,
                'work_end' => $request->work_end
            ];

            $result = DB::table('t_worktime')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->get(['id']);

            if (isset($result[0])) {
                if ($data['work_start'] == '00:00' && $data['work_end'] == '00:00') {
                    //削除
                    DB::table('t_worktime')->where('id', $result[0]->id)->delete();
                } else {
                    //更新
                    DB::table('t_worktime')->where('id', $result[0]->id)->update($data);
                }
            } else {
                //新規作成
                $data['year'] = $year;
                $data['month'] = $month;
                $data['day'] = $day;

                DB::table('t_worktime')->insert($data);
            }

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     */
    public function workinggenbaname()
    {
        $response = [];

        $response = $this->getGenbaName();

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getholiday()
    {
        $response = [];

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

        $response = $holiday;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function dailyuranai(Request $request)
    {

        try {

            $response = [];

            //-----------------------------------------//
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

                        if ($request->date == $ex_v[0]) {

                            $ex_v1 = explode(";", trim($ex_v[1]));
                            $ex_v2 = explode(";", trim($ex_v[2]));
                            $ex_v3 = explode(";", trim($ex_v[3]));
                            $ex_v4 = explode(";", trim($ex_v[4]));

                            $ex_v1_0 = explode("<br>", trim($ex_v1[0]));

                            $response['total'] = [
                                'title' => trim($ex_v1_0[0]),
                                'description' => trim($ex_v1_0[1]),
                                'point' => trim($ex_v1[1]),
                            ];

                            $response['love'] = [
                                'description' => trim($ex_v2[0]),
                                'point' => trim($ex_v2[1]),
                            ];

                            $response['money'] = [
                                'description' => trim($ex_v3[0]),
                                'point' => trim($ex_v3[1]),
                            ];

                            $response['work'] = [
                                'description' => trim($ex_v4[0]),
                                'point' => trim($ex_v4[1]),
                            ];

                            break;
                        }

                    }
                }
            }
            //-----------------------------------------//

            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return 0;
        }

    }

    /**
     * @param Request $request
     */
    public function getMonthlyUranaiData(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        //-----------------------------------------//
        $file = public_path() . "/mySetting/uranai.data";
        $content = file_get_contents($file);
        $ary = [];
        if (!empty($content)) {
            $ex_content = explode("\n", $content);
            if (!empty($ex_content)) {
                foreach ($ex_content as $v) {
                    if (trim($v) == "") {
                        continue;
                    }

                    $ex_v = explode("|", trim($v));

                    $ex_v0 = explode("-", trim($ex_v[0]));

                    if (($year == trim($ex_v0[0])) && (trim($ex_v0[1]) == $month)) {
                        $ex_v1 = explode("<br>", trim($ex_v[1]));
                        $ex_v1_1 = explode(";", trim($ex_v1[1]));

                        $ary[trim($ex_v[0])] = [
                            'date' => trim($ex_v[0]),
                            'total' => [
                                trim($ex_v1[0]),
                                trim($ex_v1_1[0]),
                                trim($ex_v1_1[1])
                            ],

                            'love' => explode(";", trim($ex_v[2])),
                            'money' => explode(";", trim($ex_v[3])),
                            'work' => explode(";", trim($ex_v[4])),
                        ];
                    }
                }
            }
        }
        //-----------------------------------------//

        //-----------------------------------------//
        $file = public_path() . "/mySetting/leofortune.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);
        $ary2 = [];
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            $str = trim($v);
            $str = strtr($str, ['明日' => '今日']);
            $ex_v = explode("|", trim($str));

            $ary2["{$ex_v[0]}-{$ex_v[1]}-{$ex_v[2]}"] = [
                'sachikoi_rank' => trim($ex_v[3]),
                'sachikoi_love' => trim($ex_v[4]),
                'sachikoi_money' => trim($ex_v[5]),
                'sachikoi_work' => trim($ex_v[6]),
                'sachikoi_man' => trim($ex_v[7])
            ];
        }
        //-----------------------------------------//

        $ary3 = [];
        foreach ($ary as $date => $v) {
            $ary3[] = [
                'date' => $v['date'],

                'total_title' => $v['total'][0],
                'total_description' => $v['total'][1],
                'total_point' => $v['total'][2],

                'love_description' => $v['love'][0],
                'love_point' => $v['love'][1],

                'money_description' => $v['money'][0],
                'money_point' => $v['money'][1],

                'work_description' => $v['work'][0],
                'work_point' => $v['work'][1],

                'sachikoi_rank' => isset($ary2[$date]) ? $ary2[$date]['sachikoi_rank'] : "",
                'sachikoi_love' => isset($ary2[$date]) ? $ary2[$date]['sachikoi_love'] : "",
                'sachikoi_money' => isset($ary2[$date]) ? $ary2[$date]['sachikoi_money'] : "",
                'sachikoi_work' => isset($ary2[$date]) ? $ary2[$date]['sachikoi_work'] : "",
                'sachikoi_man' => isset($ary2[$date]) ? $ary2[$date]['sachikoi_man'] : ""
            ];
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function monthlyuranai(Request $request)
    {
        try {

            $response = [];

            list($year, $month, $day) = explode("-", $request->date);

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

                        if (preg_match("/^" . $year . "-" . $month . "/", trim($ex_v[0]))) {

                            $ex_v1 = explode(";", trim($ex_v[1]));
                            $ex_v2 = explode(";", trim($ex_v[2]));
                            $ex_v3 = explode(";", trim($ex_v[3]));
                            $ex_v4 = explode(";", trim($ex_v[4]));

                            $ex_v1_0 = explode("<br>", trim($ex_v1[0]));

                            $response[] = [
                                'date' => trim($ex_v[0]),
                                'title_total' => trim($ex_v1_0[0]),
                                'point_total' => trim($ex_v1[1]),
                                'point_love' => trim($ex_v2[1]),
                                'point_money' => trim($ex_v3[1]),
                                'point_work' => trim($ex_v4[1]),
                            ];
                        }
                    }
                }
            }
            //-----------------------------------------//

            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @return mixed
     */
    public function yearlyuranai(Request $request)
    {
        try {

            $response = [];

            list($year, $month, $day) = explode("-", $request->date);

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

                        if (preg_match("/^" . $year . "/", trim($ex_v[0]))) {

                            $ex_v1 = explode(";", trim($ex_v[1]));
                            $ex_v2 = explode(";", trim($ex_v[2]));
                            $ex_v3 = explode(";", trim($ex_v[3]));
                            $ex_v4 = explode(";", trim($ex_v[4]));

                            $ex_v1_0 = explode("<br>", trim($ex_v1[0]));

                            $response[] = [
                                'date' => trim($ex_v[0]),
                                'title_total' => trim($ex_v1_0[0]),
                                'point_total' => trim($ex_v1[1]),
                                'point_love' => trim($ex_v2[1]),
                                'point_money' => trim($ex_v3[1]),
                                'point_work' => trim($ex_v4[1]),
                            ];
                        }
                    }
                }
            }
            //-----------------------------------------//

            return response()->json(['data' => $response]);

        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @param Request $request
     * @return int
     */
    public function monthlyuranaidetail(Request $request)
    {

        try {

            $response = [];

            //-----------------------------------------//
            $file = public_path() . "/mySetting/uranai.data";
            $content = file_get_contents($file);

            if (!empty($content)) {
                $ex_content = explode("\n", $content);
                if (!empty($ex_content)) {

                    list($year, $month, $day) = explode("-", $request->date);

                    $i = 0;
                    foreach ($ex_content as $v) {
                        if (trim($v) == "") {
                            continue;
                        }
                        $ex_v = explode("|", trim($v));

                        if (preg_match("/" . $year . "-" . $month . "/", trim($ex_v[0]))) {

                            $ex_v1 = explode(";", trim($ex_v[1]));
                            $ex_v2 = explode(";", trim($ex_v[2]));
                            $ex_v3 = explode(";", trim($ex_v[3]));
                            $ex_v4 = explode(";", trim($ex_v[4]));

                            $ex_v1_0 = explode("<br>", trim($ex_v1[0]));

                            $response[$i]['date'] = trim($ex_v[0]);

                            $response[$i]['total'] = [
                                'title' => trim($ex_v1_0[0]),
                                'description' => trim($ex_v1_0[1]),
                                'point' => trim($ex_v1[1]),
                            ];

                            $response[$i]['love'] = [
                                'description' => trim($ex_v2[0]),
                                'point' => trim($ex_v2[1]),
                            ];

                            $response[$i]['money'] = [
                                'description' => trim($ex_v3[0]),
                                'point' => trim($ex_v3[1]),
                            ];

                            $response[$i]['work'] = [
                                'description' => trim($ex_v4[0]),
                                'point' => trim($ex_v4[1]),
                            ];

                            $i++;
                        }

                    }
                }
            }
            //-----------------------------------------//

            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return 0;
        }

    }

    /**
     * @param Request $request
     */
    public function getkotowazacount()
    {
        $response = [];

        $sql = " select head, count(head) cnt from t_kotowaza group by head; ";
        $result = DB::select($sql);

        foreach ($result as $k => $v) {
            $result2 = DB::table('t_kotowaza')
                ->where('head', '=', $v->head)
                ->where('flag', '=', 1)
                ->get();

            $response[$k]['head'] = $v->head;
            $response[$k]['count'] = $v->cnt;

            $response[$k]['flaged'] = count($result2);
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getkotowaza(Request $request)
    {
        $response = [];

        $result = DB::table('t_kotowaza')->where('head', '=', $request->head)->orderBy('yomi')->get();
        foreach ($result as $k => $v) {
            $response[$k]['id'] = $v->id;
            $response[$k]['word'] = $v->word;
            $response[$k]['yomi'] = $v->yomi;
            $response[$k]['explanation'] = $v->explanation;
            $response[$k]['flag'] = $v->flag;
            $response[$k]['head'] = $v->head;
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function changekotowazaflag(Request $request)
    {
        $result = DB::table('t_kotowaza')->where('id', '=', $request->id)->first();

        $update = [];
        switch ($result->flag) {
            case 0:
                $update['flag'] = 1;
                break;
            case 1:
                $update['flag'] = 0;
                break;
        }

        DB::table('t_kotowaza')->where('id', '=', $request->id)->update($update);

        //
        $response = [];
        $result2 = DB::table('t_kotowaza')->where('head', '=', $result->head)->orderBy('yomi')->get();
        foreach ($result2 as $k => $v) {
            $response[$k]['id'] = $v->id;
            $response[$k]['word'] = $v->word;
            $response[$k]['yomi'] = $v->yomi;
            $response[$k]['explanation'] = $v->explanation;
            $response[$k]['flag'] = $v->flag;
            $response[$k]['head'] = $v->head;
        }
        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getkotowazachecktest()
    {

        $response = [];

        $sql = " select * from t_kotowaza where flag = 1 order by rand(); ";
        $result = DB::select($sql);

        foreach ($result as $k => $v) {
            $response[$k]['id'] = $v->id;
            $response[$k]['word'] = $v->word;
            $response[$k]['yomi'] = $v->yomi;
            $response[$k]['explanation'] = $v->explanation;
            $response[$k]['flag'] = $v->flag;
            $response[$k]['head'] = $v->head;
        }

        return response()->json(['data' => $response]);

    }

    /**
     * @param Request $request
     */
    public function leofortune(Request $request)
    {

        $response = 0;

        list($year, $month, $day) = explode("-", $request->date);

        $file = public_path() . "/mySetting/leofortune.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($lf_year, $lf_month, $lf_day,) = explode("|", trim($v));

            if ("$year-$month-$day" == "$lf_year-$lf_month-$lf_day") {
                $str = trim($v);
                $str = strtr($str, ['明日' => '今日']);
                $response = explode("|", $str);
                break;
            }
        }

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getWellsRecord()
    {

        $response = [];

        $result = DB::table('t_credit')
            ->where('price', '=', 55880)
            ->orderBy('ymd')
            ->get();

        $ary = [];
        $lastPrice = 0;
        foreach ($result as $k => $v) {
            $sumPrice = ($lastPrice + $v->price);
            $ary[$v->year][] = sprintf("%03d", ($k + 1)) . "|$v->month-$v->day|$v->price|$sumPrice";
            $lastPrice = $sumPrice;
        }

        $ary2 = [];
        foreach ($ary as $year => $v) {
            $ary3 = [];
            for ($i = 0; $i < 12; $i++) {
                $ary3[$i] = "|||";
            }

            $j = (12 - count($v));
            if ($year == date("Y")) {
                $j = 0;
            }

            foreach ($v as $v2) {
                $ary3[$j] = $v2;
                $j++;
            }

            $ary2[$year] = $ary3;
        }

        $ary4 = [];
        foreach ($ary2 as $year => $v) {
            $ary4[] = "$year:" . implode("/", $v);
        }

        $response = $ary4;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getBalanceSheetRecord()
    {

        $response = [];

        $result = DB::table('t_balancesheet')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $midashi = [
            'assets_total_deposit_start',
            'assets_total_deposit_debit',
            'assets_total_deposit_credit',
            'assets_total_deposit_end',
            'assets_total_receivable_start',
            'assets_total_receivable_debit',
            'assets_total_receivable_credit',
            'assets_total_receivable_end',
            'assets_total_fixed_start',
            'assets_total_fixed_debit',
            'assets_total_fixed_credit',
            'assets_total_fixed_end',
            'assets_total_lending_start',
            'assets_total_lending_debit',
            'assets_total_lending_credit',
            'assets_total_lending_end',
            'capital_total_liabilities_start',
            'capital_total_liabilities_debit',
            'capital_total_liabilities_credit',
            'capital_total_liabilities_end',
            'capital_total_borrow_start',
            'capital_total_borrow_debit',
            'capital_total_borrow_credit',
            'capital_total_borrow_end',
            'capital_total_principal_start',
            'capital_total_principal_debit',
            'capital_total_principal_credit',
            'capital_total_principal_end',
            'capital_total_income_start',
            'capital_total_income_debit',
            'capital_total_income_credit',
            'capital_total_income_end'
        ];

        $ary3 = [];
        foreach ($result as $v) {

            $ary = [];
            $ary2 = [];

            $assets_total = 0;
            $capital_total = 0;

            foreach ($midashi as $v2) {
                if (preg_match("/^assets_total_/", $v2)) {
                    $ary[] = $v2 . ":" . $v->$v2;
                    if (preg_match("/_end$/", $v2)) {
                        $assets_total += $v->$v2;
                    }
                }

                if (preg_match("/^capital_total_/", $v2)) {
                    $ary2[] = $v2 . ":" . $v->$v2;
                    if (preg_match("/_end$/", $v2)) {
                        $capital_total += $v->$v2;
                    }
                }
            }

            $ar = [];
            $ar[] = "ym:$v->year-$v->month";
            $ar[] = "assets_total:$assets_total";
            $ar[] = "capital_total:$capital_total";
            $ar[] = implode("|", $ary);
            $ar[] = implode("|", $ary2);
            $ary3[] = implode("|", $ar);
        }

        $response = $ary3;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getITFRecord()
    {
        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $shintaku = 0;
        $stock = 0;
        $dateTime = "";

        $RakutenCredits = [];

        foreach ($_tables as $table) {

            $result = DB::table($table)
                ->where('article', 'like', '資産合計※楽天銀行残高除く%')
                ->first();

            if (!empty($result)) {
                $ex_article = explode("\n", trim($result->article));

                $time = "";
                foreach ($ex_article as $v) {
                    if (preg_match("/投資信託/", trim($v))) {
                        $ex_v = explode("\t", trim($v));
                        $shintaku += trim(strtr($ex_v[1], [',' => '', '円' => '']));
                    }

                    if (
                        preg_match("/国内株式/", trim($v)) ||
                        preg_match("/米国株式/", trim($v))
                    ) {
                        $ex_v = explode("\t", trim($v));
                        $stock += trim(strtr($ex_v[1], [',' => '', '円' => '']));
                    }

                    $time = trim(strtr(trim($v), ['[' => '', ']' => '']));
                }

                $da = [];
                $da[] = $result->year;
                $da[] = $result->month;
                $da[] = $result->day;
                $date = implode("-", $da);

                $dateTime = "$date $time";
            }

            /////////////////////////////////////////
            $result2 = DB::table($table)
                ->where('article', 'like', '%楽天カード内訳%')->get();

            foreach ($result2 as $v2) {
                $date = $v2->year . "-" . $v2->month . "-" . $v2->day;
                $RakutenCredits[$date] = explode("\n", trim($v2->article));
            }
            /////////////////////////////////////////

        }

        ///////////////////////////////////////////

        $shin = 0;
        $date_shin = "";
        $sql = "
select
year,month,day,koumoku item,price
from
t_dailyspend
where
koumoku = '投資信託'
union all
select
year,month,day,item,price
from
t_credit
where
item = '投資信託'
";
        $dshin = [];
        $result = DB::select($sql);
        foreach ($result as $v) {
            $shin += trim($v->price);

//            $date_shin = $v->year . "-" . $v->month . "-" . $v->day;
            $dshin[] = $v->year . "-" . $v->month . "-" . $v->day;
        }

        $date_shin = max($dshin);

        //>>>>>>>>>>>.//
        foreach ($RakutenCredits as $_date => $v) {
            foreach ($v as $v2) {
                if (preg_match("/投信積立（楽天証券）/", trim($v2))) {
                    $ex_v2 = explode("\t", trim($v2));
                    $sh = trim(strtr($ex_v2[4], ['¥' => '', ',' => '']));
                    $shin += $sh;

                    $date_shin = $_date;
                }
            }
        }

        //---------------------

        $stk = 0;
        $date_stk = "";
        $sql = "
select
year,month,day,koumoku item,price
from
t_dailyspend
where
koumoku = '株式買付'
union all
select
year,month,day,item,price
from
t_credit
where
item = '株式買付'
";

        $dstk = [];
        $result = DB::select($sql);
        foreach ($result as $v) {
            $stk += trim($v->price);

//            $date_stk = $v->year . "-" . $v->month . "-" . $v->day;

            $dstk[] = $v->year . "-" . $v->month . "-" . $v->day;
        }

        $date_stk = max($dstk);


        ///////////////////////////////////////////

        $response[] = $dateTime;
        $response[] = $date_shin . ";" . $shin . ";" . $shintaku;
        $response[] = $date_stk . ";" . $stk . ";" . $stock;

        $response2 = $response;
        $response = implode("/", $response2);

        return response()->json(['data' => $response]);
    }



















    /**
     * @return mixed
     */

    /*
    public function getStockPrice()
    {

        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $koumoku = ['米国株式'];

        $date = "";
        $ans = [];
        $time = "";
        foreach ($_tables as $table) {
            $sql = " select * from $table where article like '保有株式%'; ";
            $result = DB::select($sql);
            foreach ($result as $v) {
                $ex_article = explode("\n", $v->article);

                foreach ($ex_article as $v2){
                    $ex_v2 = explode("\t", trim($v2));
                    if (in_array(trim($ex_v2[0]), $koumoku)){
                        $ans[] = trim(strtr($ex_v2[1], [',' => '', '円' => '']));
                    }

                    if (preg_match("/\[(.+)\]/", trim($v2), $m)){
                        $time = trim($m[1]);
                    }
                }

                $date = $v->year . "-" . $v->month . "-" . $v->day;

                if (!empty($ans)){break;}
            }
        }

        //////////////////////////////////
        $result = DB::table('t_credit')
            ->where('item', '=', '株式買付')
            ->get();

        $kabushikiKaitsuke = [];
        foreach ($result as $v){
            $kabushikiKaitsuke[] = $v->price;
        }
        //////////////////////////////////

        $ary = [];
        $ary[] = array_sum($kabushikiKaitsuke);
        $ary[] = array_sum($ans);
        $ary[] = $date . " " . $time;

        $response = implode("|", $ary);

        return response()->json(['data' => $response]);
    }
*/

    /**
     * @return mixed
     */
    public function homeFixData()
    {

        $response = [];

        ///////////////////////////////////////////////
        $_tables = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach ($result as $v) {
            if (preg_match("/t_article/", $v->table_name)) {
                $_tables[] = $v->table_name;
            }
        }
        ///////////////////////////////////////////////

        $gas = [];
        $denki = [];
        $suidou = [];
        $mobile = [];
        $wifi = [];

        $_ym2 = [];
        foreach ($_tables as $table) {
            $result = DB::table($table)
                ->where('article', 'like', '%内訳%')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result as $v) {

                $ym = "{$v->year}-{$v->month}";
                if ($v->year >= 2020) {
                    $_ym2[$ym] = "";
                }

                $ex_v = explode("\n", trim($v->article));
                foreach ($ex_v as $v2) {
                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+ガス代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $gas[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+電気代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $denki[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/\((.+)\) 水道光熱費(.+)円.+水道代/", trim($v2), $m)) {
                        $day = trim($m[1]);
                        $price = trim(strtr($m[2], [',' => '']));
                        $suidou[$ym][] = number_format($price) . " ({$day})";
                    }

                    if (preg_match("/20.+楽天/", trim($v2))) {
                        $ex_v2 = explode("\t", trim($v2));
                        $ex_date = explode("/", trim($ex_v2[0]));
                        $yearmonth = "{$ex_date[0]}-{$ex_date[1]}";
                        $_day = trim($ex_date[2]);
                        $price = trim(strtr($ex_v2[4], ['¥' => '', ',' => '']));
                        if (preg_match("/ブロードバンド/", trim($v2))) {
                            $wifi[$yearmonth][] = number_format($price) . " ({$_day})";
                        } else {
                            if (
                                preg_match("/市場/", trim($v2)) ||
                                preg_match("/証券/", trim($v2))
                            ) {
                                //
                            } else {
                                $mobile[$yearmonth][] = number_format($price) . " ({$_day})";
                            }
                        }
                    }


                    if (preg_match("/ドコモご利用料金/", trim($v2))) {
                        $ex__v2 = explode(" ", trim($v2));
                        preg_match("/(.+)ドコモご利用料金/", trim($ex__v2[0]), $__m);
                        $__ymd = trim($__m[1]);
                        $ex__ymd = explode("/", trim($__ymd));
                        $__year = trim($ex__ymd[0]);
                        $__month = trim($ex__ymd[1]);
                        $__day = trim($ex__ymd[2]);
                        $__price = trim(strtr($ex__v2[1], [',' => '', 'リボへ変更する' => '']));
                        $wifi["{$__year}-{$__month}"][] = number_format($__price) . " ({$__day})";
                    }


                }
            }
        }

        $yachin = [];
        $result2 = DB::table("t_credit")
            ->where("price", "=", 67000)
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result2 as $v) {

            if ($v->year >= 2020) {
                $_ym2["{$v->year}-{$v->month}"] = "";
            }

            $yachin["{$v->year}-{$v->month}"][] = number_format($v->price) . " ({$v->day})";
        }

        $denki["2021-08"][] = "4,400 (19)";
        $suidou["2021-02"][] = "30,003 (05)";

        $wifi["2020-01"][] = "16,812 (06)";
        $wifi["2020-01"][] = "16,900 (31)";
        $wifi["2020-03"][] = "24,914 (02)";

        $mobile["2020-03"][] = "5,080 (31)";
        $mobile["2020-04"][] = "5,080 (30)";
        $mobile["2020-06"][] = "5,080 (01)";

        $YM2 = array_keys($_ym2);
        sort($YM2);

        $ary2 = [];
        foreach ($YM2 as $v3) {

            if (!isset($yachin[$v3])) {
                continue;
            }

            $ary2[$v3] = [
                'ym' => $v3,
                'yachin' => (isset($yachin)) ? implode(" / ", $yachin[$v3]) : "",
                'wifi' => (isset($wifi[$v3])) ? implode(" / ", $wifi[$v3]) : "",
                'mobile' => (isset($mobile[$v3])) ? implode(" / ", $mobile[$v3]) : "",
                'gas' => (isset($gas[$v3])) ? implode(" / ", $gas[$v3]) : "",
                'denki' => (isset($denki[$v3])) ? implode(" / ", $denki[$v3]) : "",
                'suidou' => (isset($suidou[$v3])) ? implode(" / ", $suidou[$v3]) : ""
            ];
        }

        $ary3 = [];
        foreach ($ary2 as $v) {
            $ary3[] = implode("|", $v);
        }


        $response = $ary3;

        return response()->json(['data' => $response]);

    }

    /**
     * @return mixed
     */
    public function tarotcard()
    {

        $response = [];

        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $result2 = DB::table("t_tarotdraw")
            ->where("year", "=", $year)
            ->where("month", "=", $month)
            ->where("day", "=", $day)
            ->get();

        if (!empty($result2[0])) {
            $result = DB::table("t_tarot")->where("id", "=", $result2[0]->tarot_id)->first();
            $just_reverse = ($result2[0]->reverse == 0) ? "just" : "reverse";
        } else {
            $dice1 = mt_rand(1, 78);
            $result = DB::table("t_tarot")->where("id", "=", $dice1)->first();

            $dice2 = mt_rand(1, 10);
            $just_reverse = ($dice2 % 2 == 1) ? "just" : "reverse";

            ////////////

            $insert = [];
            $insert['year'] = $year;
            $insert['month'] = $month;
            $insert['day'] = $day;
            $insert['tarot_id'] = $result->id;
            $insert["name"] = $result->name;
            $insert["reverse"] = ($just_reverse == "just") ? 0 : 1;

            DB::table("t_tarotdraw")->insert($insert);
        }

        $ary = [];
        $ary["id"] = $result->id;

        $ary["name"] = $result->name;
        $ary["prof1"] = $result->prof1;
        $ary["prof2"] = $result->prof2;

        $ary["just_reverse"] = $just_reverse;
        $ary["image"] = $result->image;

        $ary["word"] = ($just_reverse == "just") ? $result->word_just : $result->word_reverse;
        $ary["msg"] = ($just_reverse == "just") ? $result->msg_just : $result->msg_reverse;
        $ary["msg2"] = ($just_reverse == "just") ? $result->msg_just2 : $result->msg_reverse2;
        $ary["msg3"] = ($just_reverse == "just") ? $result->msg_just3 : $result->msg_reverse3;

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function tarotthree(Request $request)
    {

        $response = [];


        $getIds = $this->tarotIdGet($request->number);

        $ary = [];
        foreach ($getIds as $k => $v) {
            list($id, $reverse) = explode("-", $v);

            $result = DB::table("t_tarot")->where("id", "=", $id)->first();

            $ary[$k]['id'] = $id;
            $ary[$k]['reverse'] = $reverse;

            $ary[$k]['name'] = $result->name;
            $ary[$k]['prof1'] = $result->prof1;
            $ary[$k]['prof2'] = $result->prof2;

            $ary[$k]['image'] = $result->image;

            $ary[$k]['word'] = ($reverse == "just") ? $result->word_just : $result->word_reverse;

            $ary[$k]['msg1'] = ($reverse == "just") ? $result->msg_just : $result->msg_reverse;
            $ary[$k]['msg2'] = ($reverse == "just") ? $result->msg_just2 : $result->msg_reverse2;
            $ary[$k]['msg3'] = ($reverse == "just") ? $result->msg_just3 : $result->msg_reverse3;

        }


        $response = $ary;


        return response()->json(['data' => $response]);
    }

    /**
     * @param int $
     */
    private function tarotIdGet(int $number)
    {


        $getIds = [];

        $ary = [];
        while (count($ary) < $number) {
            $dice1 = mt_rand(1, 78);

            if (in_array($dice1, $ary)) {
                continue;
            }

            $ary[] = $dice1;
        }

        foreach ($ary as $v) {
            $dice2 = mt_rand(1, 10);
            $just_reverse = ($dice2 % 2 == 1) ? "just" : "reverse";

            $getIds[] = "{$v}-{$just_reverse}";
        }

        return $getIds;
    }

    /**
     *
     */
    public function tarotcategory(Request $request)
    {
        $response = [];

        //----------------------------------------//
        $ary2 = [];
        $result2 = DB::table('t_tarot')
            ->orderBy('id')
            ->get();
        foreach ($result2 as $v2) {
            $ary2[$v2->id] = 0;
        }
        //---
        $ary3 = [];
        $result3 = DB::table('t_tarotdraw')
            ->get();
        foreach ($result3 as $v3) {
            $ary3[$v3->tarot_id][] = "";
        }
        $allDraw = count($result3);
        //---
        $ary4 = [];
        foreach ($ary2 as $id => $v4) {
            $cnt = (isset($ary3[$id])) ? count($ary3[$id]) : 0;
            $ary4[$id] = "{$cnt} / {$allDraw}";
        }
        //----------------------------------------//

        $change = [];
        $change["Cups"] = "";
        $change["Pentacles"] = "";
        $change["Swords"] = "";
        $change["Wands"] = "";
        $change["of Cups"] = "";
        $change["of Pentacles"] = "";
        $change["of Swords"] = "";
        $change["of Wands"] = "";

        $result = DB::table("t_tarot")
            ->where("image", "like", $request->category . "%")
            ->orderBy('image')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = "{$v->id}:" . trim(strtr($v->name, $change)) . ":" . $ary4[$v->id];
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /**
     *
     */
    public function tarotselect(Request $request)
    {

        $response = [];

        $result = DB::table("t_tarot")
            ->where("id", "=", $request->id)
            ->first();

        $ary = [];
        $ary["name"] = $result->name;
        $ary["image"] = $result->image;

        $ary["prof1"] = $result->prof1;
        $ary["prof2"] = $result->prof2;

        $ary["word_j"] = $result->word_just;
        $ary["word_r"] = $result->word_reverse;

        $ary["msg_j"] = $result->msg_just;
        $ary["msg_r"] = $result->msg_reverse;

        $ary["msg2_j"] = $result->msg_just2;
        $ary["msg2_r"] = $result->msg_reverse2;

        $ary["msg3_j"] = $result->msg_just3;
        $ary["msg3_r"] = $result->msg_reverse3;

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /**
     *
     */
    public function tarothistory()
    {
        $response = [];

        $sql = "
select
t_tarot.id,
t_tarot.name,
t_tarotdraw.year,
t_tarotdraw.month,
t_tarotdraw.day,
t_tarotdraw.reverse,
t_tarot.image,
t_tarot.word_just,
t_tarot.word_reverse
from
t_tarot
inner join t_tarotdraw on t_tarotdraw.tarot_id = t_tarot.id
order by
t_tarotdraw.year, t_tarotdraw.month, t_tarotdraw.day;
";
        $result = DB::select($sql);

        $ary = [];
        foreach ($result as $k => $v) {
            $ary[$k]['year'] = $v->year;
            $ary[$k]['month'] = $v->month;
            $ary[$k]['day'] = $v->day;

            $ary[$k]['id'] = $v->id;
            $ary[$k]['name'] = $v->name;

            $ary[$k]['image'] = $v->image;
            $ary[$k]['reverse'] = $v->reverse;

            $ary[$k]['word'] = ($v->reverse == 0) ? $v->word_just : $v->word_reverse;
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getAllTarot()
    {
        $response = [];

        //----------------------------------------//
        $ary2 = [];
        $result2 = DB::table('t_tarot')
            ->orderBy('id')
            ->get();
        foreach ($result2 as $v2) {
            $ary2[$v2->id] = 0;
        }
        //---
        $ary3 = [];
        $ary5 = [];

        $result3 = DB::table('t_tarotdraw')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result3 as $v3) {
            $ary3[$v3->tarot_id][] = "";

            $ary5[$v3->tarot_id][$v3->reverse][] = "{$v3->year}-{$v3->month}-{$v3->day}";
        }
        $allDraw = count($result3);
        //---
        $ary4 = [];
        foreach ($ary2 as $id => $v4) {
            $cnt = (isset($ary3[$id])) ? count($ary3[$id]) : 0;
            $ary4[$id] = "{$cnt} / {$allDraw}";
        }
        //----------------------------------------//

        //----------------------------------------//
        $ary6 = [];
        for ($i = 1; $i <= 78; $i++) {
            for ($j = 0; $j <= 1; $j++) {
                $ary6[$i][$j] = (isset($ary5[$i][$j])) ? $ary5[$i][$j] : [];
            }
        }
        //----------------------------------------//

        $result = DB::table("t_tarot")
            ->orderBy("id")
            ->get();

        $ary = [];
        foreach ($result as $k => $v) {
            $ary[$k]["id"] = $v->id;

            $ary[$k]["name"] = $v->name;
            $ary[$k]["image"] = $v->image;

            $ary[$k]["prof1"] = $v->prof1;
            $ary[$k]["prof2"] = $v->prof2;

            $ary[$k]["word_j"] = $v->word_just;
            $ary[$k]["word_r"] = $v->word_reverse;

            $ary[$k]["msg_j"] = $v->msg_just;
            $ary[$k]["msg_r"] = $v->msg_reverse;

            $ary[$k]["msg2_j"] = $v->msg_just2;
            $ary[$k]["msg2_r"] = $v->msg_reverse2;

            $ary[$k]["msg3_j"] = $v->msg_just3;
            $ary[$k]["msg3_r"] = $v->msg_reverse3;

            $ary[$k]['drawNum'] = $ary4[$v->id];

            $ary[$k]['drawNum_j'] = $ary6[$v->id][0];
            $ary[$k]['drawNum_r'] = $ary6[$v->id][1];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getCatTarot()
    {
        $response = [];

        $file = public_path() . "/mySetting/CatTarot.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        $response = $ex_content;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function dice(Request $request)
    {
        $response = [];

        $num = strtr($request->diceNum, ['num_' => '']);
        $kind = strtr($request->diceKind, ['kind_' => '']);

        $ary = [];
        for ($i = 0; $i < $num; $i++) {
            $dice1 = mt_rand(1, $kind);
            $ary[] = $dice1;
        }

        $response = ['sum' => array_sum($ary), 'ary' => $ary];

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getDataStock(Request $request)
    {
        $response = [];

        $sql = " select year, month, day, ticker, name, hoyuu_suuryou, heikin_shutoku_kagaku, jika_hyoukagaku from t_stock_datas order by ticker, year, month, day; ";
        $result = DB::select($sql);

        $ary = [];
        foreach ($result as $v) {


//
//
//
//            $_name = strtr($v->name, ['\\t\\t決算発表日' => '']);
//
//            $name = "[{$v->ticker}] {$_name}";
//
//

            $ex_name = explode("\t", $v->name);
            if (count($ex_name) > 1) {
                $name = "[{$v->ticker}] " . $ex_name[0];
            } else {
                $name = "[{$v->ticker}] {$v->name}";
            }


            $date = "{$v->year}-{$v->month}-{$v->day}";
            $oneStock = strtr($v->heikin_shutoku_kagaku, [',' => '']);
            $cost = ($v->hoyuu_suuryou * $oneStock);
            $price = strtr($v->jika_hyoukagaku, [',' => '']);
            $diff = ($price - $cost);
            $ary[$name][] = [
                'date' => $date,
                'num' => $v->hoyuu_suuryou,
                'oneStock' => $oneStock,
                'cost' => $cost,
                'price' => $price,
                'diff' => $diff
            ];
        }

        $ary2 = [];
        $sum_cost = [];
        $sum_price = [];
        $sum_date = "";
        foreach ($ary as $name => $v) {
            $ary3 = [];
            foreach ($v as $v2) {
                $ary3[] = implode("|", $v2);
            }

            $sum_cost[] = $v[count($v) - 1]['cost'];
            $sum_price[] = $v[count($v) - 1]['price'];
            $sum_date = $v[count($v) - 1]['date'];

            $ary2[] = [
                'name' => $name,
                'date' => $v[count($v) - 1]['date'],
                'num' => $v[count($v) - 1]['num'],
                'oneStock' => $v[count($v) - 1]['oneStock'],
                'cost' => $v[count($v) - 1]['cost'],
                'price' => $v[count($v) - 1]['price'],
                'diff' => $v[count($v) - 1]['diff'],
                'data' => implode("/", $ary3)
            ];
        }

        $ary4 = [
            'cost' => array_sum($sum_cost),
            'price' => array_sum($sum_price),
            'diff' => (array_sum($sum_price) - array_sum($sum_cost)),
            'date' => $sum_date,
            'record' => $ary2
        ];

        $response = $ary4;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getDataShintaku(Request $request)
    {
        $response = [];

        $sql = " select year, month, day, name, hoyuu_suuryou, heikin_shutoku_kagaku, shutoku_sougaku, jika_hyoukagaku, kijun_kagaku from t_toushi_shintaku_datas order by name, year, month, day; ";
        $result = DB::select($sql);


        $ary = [];
        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";

            $num = strtr($v->hoyuu_suuryou, [',' => '', '口' => '']);
            $shutoku = strtr($v->heikin_shutoku_kagaku, [',' => '', '円' => '']);
            $cost = strtr($v->shutoku_sougaku, [',' => '', '円' => '']);
            $price = strtr($v->jika_hyoukagaku, [',' => '', '円' => '']);

            $kijunKagaku = strtr($v->kijun_kagaku, [',' => '', '円' => '']);

            $ary[$v->name][] = [
                'date' => $date,
                'num' => trim($num),
                'shutoku' => trim($shutoku),
                'cost' => trim($cost),
                'price' => trim($price),
                'diff' => (trim($price) - trim($cost)),
                'kijun' => trim($kijunKagaku),
            ];
        }

        $ary2 = [];
        $sum_cost = [];
        $sum_price = [];
        $sum_date = "";
        foreach ($ary as $name => $v) {
            $ary3 = [];
            foreach ($v as $v2) {
                $ary3[] = implode("|", $v2);
            }

            $sum_cost[] = $v[count($v) - 1]['cost'];
            $sum_price[] = $v[count($v) - 1]['price'];
            $sum_date = $v[count($v) - 1]['date'];

            $ary2[] = [
                'name' => $name,
                'date' => $v[count($v) - 1]['date'],
                'num' => $v[count($v) - 1]['num'],
                'shutoku' => $v[count($v) - 1]['shutoku'],
                'cost' => $v[count($v) - 1]['cost'],
                'price' => $v[count($v) - 1]['price'],
                'diff' => $v[count($v) - 1]['diff'],
                'data' => implode("/", $ary3)
            ];
        }

        $ary4 = [
            'cost' => array_sum($sum_cost),
            'price' => array_sum($sum_price),
            'diff' => (array_sum($sum_price) - array_sum($sum_cost)),
            'date' => $sum_date,
            'record' => $ary2
        ];

        $response = $ary4;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getAllMoney(Request $request)
    {
        $response = [];

        $result = DB::table('t_money')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {

            if ($v->year < 2020) {
                continue;
            }

            $ary[] = [
                "{$v->year}-{$v->month}-{$v->day}",
                "{$v->year}-{$v->month}",
                $v->yen_10000,
                $v->yen_5000,
                $v->yen_2000,
                $v->yen_1000,
                $v->yen_500,
                $v->yen_100,
                $v->yen_50,
                $v->yen_10,
                $v->yen_5,
                $v->yen_1,
                $v->bank_a,
                $v->bank_b,
                $v->bank_c,
                $v->bank_d,
                $v->bank_e,
                $v->pay_a,
                $v->pay_b,
                $v->pay_c,
                $v->pay_d,
                $v->pay_e
            ];
        }

        $ary2 = [];
        foreach ($ary as $v) {
            $ary2[] = implode("|", $v);
        }

        $response = $ary2;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllBenefit(Request $request)
    {
        $response = [];

        $result = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = "{$v->year}-{$v->month}-{$v->day}|{$v->year}-{$v->month}|{$v->salary}|{$v->company}";
        }

        $response = $ary;


        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getStockDetail(Request $request)
    {
        $response = [];

        $result = DB::table("t_stock_datas")
            ->orderBy("ticker")
            ->orderBy("year")
            ->orderBy("month")
            ->orderBy("day")
            ->get();

        $ary = [];
        $ary2 = [];

        $last_date = [];
        $last_suuryou = [];
        $last_sougaku = [];
        $last_hyoukagaku = [];
        $last_soneki = [];

        foreach ($result as $v) {

            $_n = trim(strtr($v->name, ['決算発表日' => '']));
            $name = "{$_n}（{$v->ticker}）";

            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary2[] = $date;

            $shutoku_sougaku = ($v->hoyuu_suuryou * trim(strtr($v->heikin_shutoku_kagaku, [',' => ''])));
            $shutoku_sougaku = number_format($shutoku_sougaku);

            $ary[$name][$date] = [
                'hoyuu_suuryou' => $v->hoyuu_suuryou,
                'shutoku_sougaku' => $shutoku_sougaku,
                'jika_hyoukagaku' => $v->jika_hyoukagaku,
                'hyouka_soneki' => $v->hyouka_soneki
            ];

            $last_date[$name] = $date;

            $last_suuryou[$name] = $v->hoyuu_suuryou;
            $last_sougaku[$name] = $shutoku_sougaku;
            $last_hyoukagaku[$name] = $v->jika_hyoukagaku;
            $last_soneki[$name] = $v->hyouka_soneki;

        }

        $date_min = $ary2[0];
        $date_max = $ary2[count($ary2) - 1];

        $ary3 = [];
        foreach ($ary as $name => $v) {
            for ($i = strtotime($date_min); $i <= strtotime($date_max); $i += 86400) {
                if (isset($v[date("Y-m-d", $i)])) {
                    $ary3[$name][date("Y-m-d", $i)] = implode("|", $v[date("Y-m-d", $i)]);
                } else {
                    $ary3[$name][date("Y-m-d", $i)] = "-|-|-|-";
                }
            }
        }

        $ary4 = [];
        foreach ($ary3 as $name => $v) {
            foreach ($v as $date => $v2) {
                $ary4[$name][] = $date . "|" . date("w", strtotime($date)) . "|" . $v2;
            }
        }

        $ary5 = [];
        foreach ($ary4 as $name => $v) {
            $ary5[] = "{$name};{$last_date[$name]};{$last_suuryou[$name]};{$last_sougaku[$name]};{$last_hyoukagaku[$name]};{$last_soneki[$name]};" .
                implode("/", $v);
        }

        $response = $ary5;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getShintakuDetail(Request $request)
    {
        $response = [];

        $result = DB::table("t_toushi_shintaku_datas")
            ->orderBy("name")
            ->orderBy("year")
            ->orderBy("month")
            ->orderBy("day")
            ->get();

        $ary = [];
        $ary2 = [];

        $last_date = [];
        $last_suuryou = [];
        $last_sougaku = [];
        $last_hyoukagaku = [];
        $last_soneki = [];

        foreach ($result as $v) {
            $name = $v->name;

            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary2[] = $date;

            $suuryou = trim(strtr($v->hoyuu_suuryou, ['口' => '']));
            $sougaku = trim(strtr($v->shutoku_sougaku, ['円' => '']));
            $hyoukagaku = trim(strtr($v->jika_hyoukagaku, ['円' => '']));
            $soneki = trim(strtr($v->hyouka_soneki, ['円' => '']));

            $ary[$name][$date] = [
                'hoyuu_suuryou' => $suuryou,
                'shutoku_sougaku' => $sougaku,
                'jika_hyoukagaku' => $hyoukagaku,
                'hyouka_soneki' => $soneki
            ];

            $last_date[$name] = $date;

            $last_suuryou[$name] = $suuryou;
            $last_sougaku[$name] = $sougaku;
            $last_hyoukagaku[$name] = $hyoukagaku;
            $last_soneki[$name] = $soneki;

        }

        sort($ary2);

        $date_min = $ary2[0];
        $date_max = $ary2[count($ary2) - 1];

        $ary3 = [];
        foreach ($ary as $name => $v) {
            for ($i = strtotime($date_min); $i <= strtotime($date_max); $i += 86400) {
                if (isset($v[date("Y-m-d", $i)])) {
                    $ary3[$name][date("Y-m-d", $i)] = implode("|", $v[date("Y-m-d", $i)]);
                } else {
                    $ary3[$name][date("Y-m-d", $i)] = "-|-|-|-";
                }
            }
        }

        $ary4 = [];

        foreach ($ary3 as $name => $v) {
            $_flag = false;
            foreach ($v as $date => $v2) {

                if ($_flag == false) {
                    if ($v2 != "-|-|-|-") {
                        $_flag = true;
                    }
                }

                if ($_flag == true) {
                    $ary4[$name][] = $date . "|" . date("w", strtotime($date)) . "|" . $v2;
                }
            }
        }

        $ary5 = [];
        foreach ($ary4 as $name => $v) {
            $ary5[] = "{$name};{$last_date[$name]};{$last_suuryou[$name]};{$last_sougaku[$name]};{$last_hyoukagaku[$name]};{$last_soneki[$name]};" .
                implode("/", $v);
        }

        $response = $ary5;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function monthSpendItem(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $ary = [];

        $result = DB::table('t_dailyspend')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "{$v->koumoku}|(daily)|{$v->price}";
        }

        $result = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "{$v->item}|(bank)|{$v->price}";
        }

        $result = DB::table('t_salary')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('id')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = "収入|(income)|{$v->salary}";
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBankMoney(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_money')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->first();

        $update = [];
        $update[$request->bank] = $request->price;

        DB::table('t_money')->where('id', '>=', $result->id)->update($update);

        return response()->json(['data' => 'ok']);
    }

    /**
     *
     */
    public function creditDetail(Request $request)
    {
        $response = [];


        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getCreditDateData(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result2 = DB::table('t_article' . $year)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->where('article', 'like', '%カード内訳%')
            ->get();

        if (count($result2) > 1) {

            $ary2 = [];
            $priceAry = [];

            foreach ($result2 as $result) {

                $flag = "";
                if (preg_match("/楽天カード内訳/", trim($result->article))) {
                    $flag = "rakuten";
                } elseif (preg_match("/Amexカード内訳/", trim($result->article))) {
                    $flag = "amex";
                }

                $ex_article = explode("\n", trim($result->article));
                foreach ($ex_article as $v) {
                    $val = trim(strip_tags($v));

                    switch ($flag) {
                        case "rakuten":
                        case "amex":
                            if (preg_match("/本人/", trim($val))) {
                                $ex_val = explode("\t", $val);

                                $date = trim(strtr($ex_val[0], ['/' => '-']));
                                $item = trim($ex_val[1]);
                                $price = trim(strtr($ex_val[4], [',' => '', '¥' => '']));

                                $ary2[$flag][] = [
                                    'card' => $flag,
                                    'date' => $date,
                                    'item' => $item,
                                    'price' => $price
                                ];

                                $priceAry[$flag][] = $price;
                            }
                            break;
                    }
                }
            }

            $priceAry2 = [];
            foreach ($priceAry as $flag => $v) {
                $priceAry2[$flag] = array_sum($v);
            }

            $_flag = "";
            foreach ($priceAry2 as $flag => $_price) {
                if ($_price == $request->price) {
                    $_flag = $flag;
                    break;
                }
            }

            $ary = $ary2[$_flag];

        } else if (count($result2) == 1) {

            $ary = [];

            foreach ($result2 as $result) {

                $flag = "";
                if (preg_match("/ユーシーカード内訳/", trim($result->article))) {
                    $flag = "uc";
                } elseif (preg_match("/楽天カード内訳/", trim($result->article))) {
                    $flag = "rakuten";
                } elseif (preg_match("/住友カード内訳/", trim($result->article))) {
                    $flag = "sumitomo";
                }

                $ex_article = explode("\n", trim($result->article));
                foreach ($ex_article as $v) {
                    $val = trim(strip_tags($v));


                    switch ($flag) {
                        case "uc":
                            if (preg_match("/円.+円/", trim($val))) {
                                $ex_val = explode("\t", $val);

                                $date = trim(strtr($ex_val[1], ['/' => '-']));
                                $item = trim($ex_val[3]);
                                $price = trim(strtr($ex_val[6], [',' => '', '円' => '']));

                                $ary[] = [
                                    'card' => $flag,
                                    'date' => $date,
                                    'item' => $item,
                                    'price' => $price
                                ];
                            }
                            break;
                        case "rakuten":
                            if (preg_match("/本人/", trim($val))) {
                                $ex_val = explode("\t", $val);

                                $date = trim(strtr($ex_val[0], ['/' => '-']));
                                $item = trim($ex_val[1]);
                                $price = trim(strtr($ex_val[4], [',' => '', '¥' => '']));

                                $ary[] = [
                                    'card' => $flag,
                                    'date' => $date,
                                    'item' => $item,
                                    'price' => $price
                                ];
                            }
                            break;
                        case "sumitomo":
                            if (preg_match("/◎/", trim($val))) {
                                $ex_val = explode("\t", $val);

                                $date = trim(strtr("20" . trim($ex_val[0]), ['/' => '-']));
                                $item = trim($ex_val[1]);
                                $price = trim(strtr($ex_val[2], [',' => '']));

                                $ary[] = [
                                    'card' => $flag,
                                    'date' => $date,
                                    'item' => $item,
                                    'price' => $price
                                ];
                            }
                            break;
                    }
                }
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function creditCompanySearch(Request $request)
    {

        $response = [];

        $searchCompany = "";
        switch ($request->company) {
            case "uc":
                $searchCompany = "ユーシーカード内訳";
                break;
            case "rakuten":
                $searchCompany = "楽天カード内訳";
                break;
            case "sumitomo":
                $searchCompany = "住友カード内訳";
                break;
            case "amex":
                $searchCompany = "Amexカード内訳";
                break;
        }

        $ary = [];
        for ($i = 2020; $i <= date("Y"); $i++) {
            $table = "t_article{$i}";
            $result = DB::table($table)
                ->where('article', 'like', '%' . $searchCompany . '%')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result as $v) {
                $ym = "{$v->year}-{$v->month}";
                $ex_article = explode("\n", $v->article);

                $ary2 = [];
                foreach ($ex_article as $v2) {
                    switch ($request->company) {
                        case "uc":
                            if (preg_match("/円.+円/", trim($v2))) {
                                $ary2[] = trim($v2);
                            }
                            break;
                        case "rakuten":
                            if (preg_match("/本人/", trim($v2))) {
                                $ary2[] = trim($v2);
                            }
                            break;
                        case "sumitomo":
                            if (preg_match("/◎/", trim($v2))) {
                                $ary2[] = trim($v2);
                            }
                            break;
                        case "amex":
                            if (preg_match("/本人/", trim($v2))) {
                                $ary2[] = trim($v2);
                            }
                            break;
                    }
                }

                foreach ($ary2 as $v2) {
                    $ex_val = explode("\t", $v2);

                    switch ($request->company) {
                        case "uc":
                            $date = strtr(trim($ex_val[1]), ['/' => '-']);
                            $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);
                            $item = (preg_match("/^ＮＴＴ/", trim($ex_val[3]))) ? "NTT" : trim($ex_val[3]);
                            break;

                        case "rakuten":
                            $date = strtr(trim($ex_val[0]), ['/' => '-']);
                            $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);
                            $item = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                            break;

                        case "sumitomo":
                            $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                            $price = strtr(trim($ex_val[2]), [',' => '']);
                            $item = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                            break;

                        case "amex":
                            $date = strtr(trim($ex_val[0]), ['/' => '-']);
                            $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);
                            $item = (preg_match("/^ＮＴＴ/", trim($ex_val[1]))) ? "NTT" : trim($ex_val[1]);
                            break;
                    }

                    if (isset($date)) {
                        list($year, $month, $day) = explode("-", $date);
                        $date = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
                    }

                    $item = $this->makeItemName($item);

                    $ary[] = [
                        'ym' => $ym,
                        'date' => $date,
                        'item' => $item,
                        'price' => $price
                    ];
                }
            }
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /**
     * @param Request $request
     */
    public function bankSearch(Request $request)
    {

        $response = [];

        $result = DB::table('t_money')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        $last_price = 0;
        foreach ($result as $v) {
            if ($v->year < 2020) {
                continue;
            }

            $check_price = 0;
            switch ($request->bank) {
                case "bank_a":
                    $check_price = $v->bank_a;
                    break;
                case "bank_b":
                    $check_price = $v->bank_b;
                    break;
                case "bank_c":
                    $check_price = $v->bank_c;
                    break;
                case "bank_d":
                    $check_price = $v->bank_d;
                    break;
                case "bank_e":
                    $check_price = $v->bank_e;
                    break;

                case "pay_a":
                    $check_price = $v->pay_a;
                    break;
                case "pay_b":
                    $check_price = $v->pay_b;
                    break;
                case "pay_c":
                    $check_price = $v->pay_c;
                    break;
                case "pay_d":
                    $check_price = $v->pay_d;
                    break;
                case "pay_e":
                    $check_price = $v->pay_e;
                    break;
            }

            if ($last_price != $check_price) {
                $ary[] = [
                    'date' => "{$v->year}-{$v->month}-{$v->day}",
                    'price' => $check_price,
                    'diff' => ($last_price - $check_price) * -1
                ];
            }

            $last_price = $check_price;
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function everydaySpendSearch(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        //-----------------------------//
        $ary = [];

        $result = DB::table('t_salary')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->get();

        foreach ($result as $v) {
            $date = "{$v->year}-{$v->month}-{$v->day}";
            $ary[$date][] = $v->salary;
        }
        //-----------------------------//

        $ary3 = [];
        $ary4 = [];

        //-----------------------------//
        $result3 = DB::table('t_timeplace')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        foreach ($result3 as $v3) {
            $date = "{$v3->year}-{$v3->month}-{$v3->day}";
            $time = ($v3->time == "00:00") ? "-" : $v3->time;
            $flag = "x";
            $ary3[$date][] = "{$time}|{$v3->place}|{$v3->price}|{$flag}";//***

            $ary4[$date][] = $v3->price;
        }
        //-----------------------------//

        //-----------------------------//
        $result3 = DB::table('t_credit')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->orderBy('day')
            ->get();

        foreach ($result3 as $v3) {
            $date = "{$v3->year}-{$v3->month}-{$v3->day}";
            $flag = "x";
            $ary3[$date][] = "-|{$v3->item}|{$v3->price}|{$flag}";//***

            $ary4[$date][] = $v3->price;
        }
        //-----------------------------//

        //-----------------------------//
        $result3 = DB::table('t_mercari')
            ->orderBy('settlement_at')
            ->get();

        $ary5 = [];
        foreach ($result3 as $v3) {
            $ex_settlement = explode(" ", $v3->settlement_at);
            $price = ($v3->buy_sell == "sell") ? ($v3->price * -1) : $v3->price;
            $flag = "x";
            $ary3[$ex_settlement[0]][] = "-|mercari|{$price}|{$flag}";//***

            $ary5[$ex_settlement[0]][] = $price;
        }

        foreach ($ary5 as $date => $v5) {
            $ary4[$date][] = array_sum($v5);
        }
        //-----------------------------//

        //-----------------------------//
        $Bank_Departure = [];
        $Bank_Arrival = [];
        $result7 = DB::table('t_bank_move')->get();
        foreach ($result7 as $v7) {
            $from_date = "{$v7->from_year}-{$v7->from_month}-{$v7->from_day}";
            $Bank_Departure[$from_date] = "";

            $to_date = "{$v7->to_year}-{$v7->to_month}-{$v7->to_day}";
            $toBankUpper = strtoupper($v7->to_bank);
            $ex_toBankUpper = explode("_", $toBankUpper);
            $flag = "x";


//            $Bank_Arrival[$to_date] = "-|Bank_Arrival ({$ex_toBankUpper[1]})|-100000|{$flag}";
            $Bank_Arrival[$to_date][] = "-|Bank_Arrival ({$ex_toBankUpper[1]})|-100000|{$flag}";


            $ary4[$to_date][] = -100000;
        }
        //-----------------------------//

        //-----------------------------//
        $result6 = DB::table('t_money')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $last_bankD = 0;
        foreach ($result6 as $v6) {
            if (($last_bankD - $v6->bank_d >= 100000) and ($last_bankD - $v6->bank_d <= 100500)) {
                $date = "{$v6->year}-{$v6->month}-{$v6->day}";
                $flag = "x";
                $ary3[$date][] = "-|Bank_Departure (D)|100000|{$flag}";//***
                $ary4[$date][] = 100000;
            }
            $last_bankD = $v6->bank_d;
        }


        $ary3['2022-07-01'][] = "-|Bank_Departure (D)|100000|{$flag}";//***
        $ary4['2022-07-01'][] = 100000;


        //-----------------------------//

        //-----------------------------//
        $walk = [];
        $result8 = DB::table('t_walk_record')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();
        foreach ($result8 as $v8) {
            $v8year = sprintf("%04d", $v8->year);
            $v8month = sprintf("%02d", $v8->month);
            $v8day = sprintf("%02d", $v8->day);
            $date = "{$v8year}-{$v8month}-{$v8day}";
            $walk[$date] = [
                'step' => $v8->step,
                'distance' => $v8->distance
            ];
        }
        //-----------------------------//

        ///////////////////////////////////////////////////////////////////////////////
        $table = 't_article' . $year;

        $ary99 = [];

        //------------------------------------------//
        $result = DB::table($table)
            ->where('article', 'like', '%ユーシーカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $item = $this->makeItemName(trim($ex_val[3]));

                    $ary99[$date][] = ['item' => $item, 'price' => $price, 'date' => $date, 'kind' => 'uc card'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('article', 'like', '%楽天カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $item = $this->makeItemName(trim($ex_val[1]));

                    $ary99[$date][] = ['item' => $item, 'price' => $price, 'date' => $date, 'kind' => 'rakuten'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('article', 'like', '%Amexカード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/本人/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $item = $this->makeItemName(trim($ex_val[1]));

                    $ary99[$date][] = ['item' => $item, 'price' => $price, 'date' => $date, 'kind' => 'amex'];
                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('article', 'like', '%住友カード内訳%')->get();

        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/◎/", trim($val))) {
                    $ex_val = explode("\t", $val);
                    $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    if (trim($price) == "") {
                        continue;
                    }

                    $item = $this->makeItemName(trim($ex_val[1]));

                    $ary99[$date][] = ['item' => $item, 'price' => $price, 'date' => $date, 'kind' => 'sumitomo'];
                }
            }
        }
        ///////////////////////////////////////////////////////////////////////////////

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));

        $ary2 = [];
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            list($mYear, $mMonth, $mDay) = explode("-", $date);

            if ("{$year}-{$month}" == "{$mYear}-{$mMonth}") {
                $sal = (isset($ary[$date])) ? array_sum($ary[$date]) : 0;
                $spd = ($spend + $sal);

                if (isset($Bank_Arrival[$date])) {
//                    $ary3[$date][] = $Bank_Arrival[$date];


                    foreach ($Bank_Arrival[$date] as $v100) {
                        $ary3[$date][] = $v100;
                    }


                }

                if (!isset($ary3[$date])) {
                    $ary3[$date][0] = "-|-|0|x";
                }

                ///////////////////////////////////////////////////////////////////////////////
                if (isset($ary99[$date])) {
                    foreach ($ary99[$date] as $v99) {
                        $flag = "x";
                        $ary3[$date][] = "-|{$v99['item']} [{$v99['kind']}]|{$v99['price']}|{$flag}";
                    }
                }
                ///////////////////////////////////////////////////////////////////////////////

                $record = (isset($ary3[$date])) ? implode("/", $ary3[$date]) : "";

                $diff = isset($ary4[$date]) ? ($spd - array_sum($ary4[$date])) : 0;

                $step = (isset($walk[$date])) ? $walk[$date]['step'] : "";
                $distance = (isset($walk[$date])) ? $walk[$date]['distance'] : "";

                $ary2[] = [
                    'date' => $date,
                    'spend' => $spd,
                    'record' => $record,
                    'diff' => $diff,
                    'step' => "{$step}",
                    'distance' => "{$distance}"
                ];
            }
        }

        $response = $ary2;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function setBankMove(Request $request)
    {

        $response = [];

        list($from_year, $from_month, $from_day) = explode("-", $request->from_date);

        //-----------------------------//
        DB::table('t_bank_move')
            ->where('from_year', '=', $from_year)
            ->where('from_month', '=', $from_month)
            ->where('from_day', '=', $from_day)
            ->delete();
        //-----------------------------//

        list($to_year, $to_month, $to_day) = explode("-", $request->to_date);

        $insert = [];
        $insert["price"] = $request->price;
        $insert["from_bank"] = $request->from_bank;
        $insert["from_year"] = $from_year;
        $insert["from_month"] = $from_month;
        $insert["from_day"] = $from_day;
        $insert["to_bank"] = $request->to_bank;
        $insert["to_year"] = $to_year;
        $insert["to_month"] = $to_month;
        $insert["to_day"] = $to_day;

        DB::table('t_bank_move')->insert($insert);

        return response()->json(['data' => 'ok']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function setKeihiData(Request $request)
    {

        try {
            DB::beginTransaction();

            $ex_selected_list = explode(",", $request->selected_list);

            $ex_last_item = explode("|", trim($ex_selected_list[count($ex_selected_list) - 1]));
            $date = trim($ex_last_item[0]);
            list($year, $month, $day) = explode("-", $date);
            $shubetsu = trim($ex_last_item[5]);

            DB::table('t_keihi')
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('shubetsu', '=', $shubetsu)
                ->delete();

            foreach ($ex_selected_list as $v) {

                if (trim($v) == "") {
                    continue;
                }

                list($date, $time, $item, $price, $flag, $shubetsu) = explode("|", $v);
                list($year, $month, $day) = explode("-", $date);

                $insert = [];
                $insert['year'] = trim($year);
                $insert['month'] = trim($month);
                $insert['day'] = trim($day);
                $insert['time'] = trim($time);
                $insert['item'] = trim($item);
                $insert['price'] = trim($price);
                $insert['shubetsu'] = trim($shubetsu);

                DB::table('t_keihi')->insert($insert);
            }

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param $dir
     * @return array
     */
    private function scandir_r($dir)
    {
        $list = scandir($dir);

        $results = array();

        foreach ($list as $record) {
            if (in_array($record, array(".", ".."))) {
                continue;
            }

            $path = rtrim($dir, "/") . "/" . $record;
            if (is_file($path)) {
                $results[] = $path;
            } else {
                if (is_dir($path)) {
                    $results = array_merge($results, $this->scandir_r($path));
                }
            }
        }

        return $results;
    }

    /**
     * @return mixed
     */
    public function getTrain()
    {
        $response = [];

        $result = DB::table('t_train')
            ->orderBy('order_number')
            ->get();

        $ary = [];
        $i = 0;
        foreach ($result as $k => $v) {

            if ($v->train_number < 10000) {
                continue;
            }

            $ary[$i]['train_number'] = $v->train_number;
            $ary[$i]['train_name'] = $v->train_name;

            $i++;
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @return mixed
     */
    public function getTrain2()
    {
        $response = [];

        $result = DB::table('t_train')
            ->orderBy('order_number')
            ->get();

        $ary = [];
        $i = 0;
        foreach ($result as $k => $v) {

            if ($v->train_number < 10000) {
                continue;
            }

            $ary[$i]['train_number'] = $v->train_number;
            $ary[$i]['train_name'] = $v->train_name;

            $i++;
        }

        $response = $ary;

        return response()->json($response);
    }

    /**
     * @param Request $request
     */
    public function getTrainStation(Request $request)
    {
        $response = [];

        $result = DB::table('t_station')
            ->where('train_number', '=', $request->train_number)
            ->orderBy('id')
            ->get();

        $ary = [];
        foreach ($result as $k => $v) {
            $ary[$k]['station_name'] = $v->station_name;
            $ary[$k]['address'] = $v->address;
            $ary[$k]['lat'] = $v->lat;
            $ary[$k]['lng'] = $v->lng;
            $ary[$k]['line_number'] = 0;
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function getTrainStation2(Request $request)
    {
        $response = [];

        $result = DB::table('t_station')
            ->where('train_number', '=', $request->train_number)
            ->orderBy('id')
            ->get();

        $ary = [];
        foreach ($result as $k => $v) {
            $ary[$k]['station_name'] = $v->station_name;
            $ary[$k]['address'] = $v->address;
            $ary[$k]['lat'] = $v->lat;
            $ary[$k]['lng'] = $v->lng;
            $ary[$k]['line_number'] = 0;
        }

        $response = $ary;

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTrainCompany(Request $request)
    {
        $response = [];

        $ary = [];

        $result = DB::table('t_train_company')
            ->orderBy('id')
            ->get();

        $i = 0;
        foreach ($result as $k => $v) {

            $sql2 = " select * from t_train where company_id = {$v->company_id}; ";
            $result2 = DB::select($sql2);

            $ary2 = [];
            $j = 0;
            foreach ($result2 as $v2) {
                if ($v2->train_number < 10000) {
                    continue;
                }

                $ary2[$j]['train_number'] = $v2->train_number;
                $ary2[$j]['train_name'] = $v2->train_name;
                $ary2[$j]['pickup'] = $v2->pickup;

                $j++;
            }

            $ary[$i]['company_id'] = $v->company_id;
            $ary[$i]['company_name'] = $v->company_name;
            $ary[$i]['flag'] = $v->flag;
            $ary[$i]['train'] = $ary2;

            $i++;
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     */
    public function updateTrainFlag(Request $request)
    {
        try {
            DB::beginTransaction();

            $sql = " update t_train_company set flag = 1; ";
            DB::statement($sql);


            $ex_flags = explode(",", $request->flags);
            foreach ($ex_flags as $v) {
                DB::table('t_train_company')->where('company_id', '=', $v)->update(['flag' => 0]);
            }

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getWalkRecord(Request $request)
    {
        $response = [];

        ////////////////////////////////////////////////
        $mercari = [];

        $result2 = DB::table('t_mercari')
            ->where('buy_sell', '=', 'sell')
            ->orderBy('departured_at')
            ->get();

        foreach ($result2 as $v2) {
            $ex_dep = explode(" ", $v2->departured_at);
            $mercari[trim($ex_dep[0])] = "";
        }
        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $temple = [];

        $result3 = DB::table('t_temple')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result3 as $v3) {
            $date = "{$v3->year}-{$v3->month}-{$v3->day}";
            $tpl = (trim($v3->memo) != "") ? "{$v3->temple}、{$v3->memo}" : $v3->temple;
            $temple[$date] = $tpl;
        }
        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $timeplace = [];

        $result4 = DB::table('t_timeplace')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        $ary4 = [];
        $before = "";
        $thisDate = "";
        foreach ($result4 as $v4) {
            $date = "{$v4->year}-{$v4->month}-{$v4->day}";

            if ($thisDate == $date) {
                if ($before == $v4->place) {
                    continue;
                }
            }

            $ary4[$date][] = $v4->place;

            $thisDate = $date;
            $before = $v4->place;
        }

        foreach ($ary4 as $date => $v4) {
            $timeplace[$date] = implode(" - ", $v4);
        }
        ////////////////////////////////////////////////

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        $result6 = DB::table('t_walk_record')
            ->orderBy('id', 'desc')
            ->first();
        $maxId = $result6->id;
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        //****************************************************//
        $snd = [];

        $salary = [];
        $result7 = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();
        foreach ($result7 as $v7) {
            $date = "{$v7->year}-{$v7->month}-{$v7->day}";
            $salary[$date] = $v7->salary;
        }

        //------------

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            $spd = (isset($salary[$date])) ? ($salary[$date] + $spend) : $spend;
            $snd[$date] = "{$spd} 円";
        }
        //****************************************************//

        $offset = ($request->size * $request->page);
        $limit = $request->size;

        $result = DB::table('t_walk_record')
            ->orderBy('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $a_year = [];

        $hasNext = true;

        $ary = [];
        foreach ($result as $v) {
            $month = sprintf("%02d", $v->month);
            $day = sprintf("%02d", $v->day);
            $date = "{$v->year}-{$month}-{$day}";

            $ary[$date] = [
                'step' => $v->step,
                'distance' => $v->distance
            ];

            $a_year[$v->year][] = "";

            if ($v->id == $maxId) {
                $hasNext = false;
            }
        }

        //-------------------------------//
        $train = [];

        $article_year = array_keys($a_year);
        foreach ($article_year as $year) {
            $table = "t_article{$year}";
            $result5 = DB::table($table)
                ->where('tag', '=', '電車乗車')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result5 as $v5) {
                $date = "{$v5->year}-{$v5->month}-{$v5->day}";
                $ex_article = explode("\n", trim($v5->article));
                $acl = [];
                foreach ($ex_article as $ac) {
                    $acl[] = trim($ac);
                }
                $train[$date] = implode("、", $acl);
            }
        }
        //-------------------------------//


        foreach ($ary as $date => $v) {
            $response['data'][] = [
                'date' => $date,
                'step' => $v['step'],
                'distance' => $v['distance'],
                'timeplace' => (isset($timeplace[$date])) ? $timeplace[$date] : "",
                'temple' => (isset($temple[$date])) ? $temple[$date] : "",
                'mercari' => (isset($mercari[$date])) ? "メルカリ販売" : "",
                'train' => (isset($train[$date])) ? $train[$date] : "",
                'spend' => (isset($snd[$date])) ? $snd[$date] : 0
            ];
        }

        $response['hasNext'] = $hasNext;

        return response()->json($response);
    }

    /**
     * @return mixed
     */
    public function getWalkRecord2()
    {
        $response = [];

        ////////////////////////////////////////////////
        $mercari = [];

        $result2 = DB::table('t_mercari')
            ->where('buy_sell', '=', 'sell')
            ->orderBy('departured_at')
            ->get();

        foreach ($result2 as $v2) {
            $ex_dep = explode(" ", $v2->departured_at);
            $mercari[trim($ex_dep[0])] = "";
        }
        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $temple = [];

        $result3 = DB::table('t_temple')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        foreach ($result3 as $v3) {
            $date = "{$v3->year}-{$v3->month}-{$v3->day}";
            $tpl = (trim($v3->memo) != "") ? "{$v3->temple}、{$v3->memo}" : $v3->temple;
            $temple[$date] = $tpl;
        }
        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $timeplace = [];

        $result4 = DB::table('t_timeplace')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        $ary4 = [];
        $before = "";
        $thisDate = "";
        foreach ($result4 as $v4) {
            $date = "{$v4->year}-{$v4->month}-{$v4->day}";

            if ($thisDate == $date) {
                if ($before == $v4->place) {
                    continue;
                }
            }

            $ary4[$date][] = $v4->place;

            $thisDate = $date;
            $before = $v4->place;
        }

        foreach ($ary4 as $date => $v4) {
            $timeplace[$date] = implode(" - ", $v4);
        }
        ////////////////////////////////////////////////

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        $result6 = DB::table('t_walk_record')
            ->orderBy('id', 'desc')
            ->first();
        $maxId = $result6->id;
        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        //****************************************************//
        $snd = [];

        $salary = [];
        $result7 = DB::table('t_salary')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();
        foreach ($result7 as $v7) {
            $date = "{$v7->year}-{$v7->month}-{$v7->day}";
            $salary[$date] = $v7->salary;
        }

        //------------

        $file = public_path() . "/mySetting/MoneyTotal.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", mb_convert_encoding($content, "utf8", "sjis-win"));
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $x, $total, $spend) = explode("|", trim($v));
            $spd = (isset($salary[$date])) ? ($salary[$date] + $spend) : $spend;
            $snd[$date] = "{$spd} 円";
        }
        //****************************************************//

        $result = DB::table('t_walk_record')
            ->orderBy('id')
            ->get();

        $a_year = [];

        $hasNext = true;

        $ary = [];
        foreach ($result as $v) {
            $month = sprintf("%02d", $v->month);
            $day = sprintf("%02d", $v->day);
            $date = "{$v->year}-{$month}-{$day}";

            $ary[$date] = [
                'step' => $v->step,
                'distance' => $v->distance
            ];

            $a_year[$v->year][] = "";

            if ($v->id == $maxId) {
                $hasNext = false;
            }
        }

        //-------------------------------//
        $train = [];

        $article_year = array_keys($a_year);
        foreach ($article_year as $year) {
            $table = "t_article{$year}";
            $result5 = DB::table($table)
                ->where('tag', '=', '電車乗車')
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result5 as $v5) {
                $date = "{$v5->year}-{$v5->month}-{$v5->day}";
                $ex_article = explode("\n", trim($v5->article));
                $acl = [];
                foreach ($ex_article as $ac) {
                    $acl[] = trim($ac);
                }
                $train[$date] = implode("、", $acl);
            }
        }
        //-------------------------------//

        $ary2 = [];
        foreach ($ary as $date => $v) {
            $ary2[] = [
                'date' => $date,
                'step' => $v['step'],
                'distance' => $v['distance'],
                'timeplace' => (isset($timeplace[$date])) ? $timeplace[$date] : "",
                'temple' => (isset($temple[$date])) ? $temple[$date] : "",
                'mercari' => (isset($mercari[$date])) ? "メルカリ販売" : "",
                'train' => (isset($train[$date])) ? $train[$date] : "",
                'spend' => (isset($snd[$date])) ? $snd[$date] : 0
            ];
        }

        $response = $ary2;

        return response()->json($response);
    }

    /**
     * @param Request $request
     */
    public function getAgentDocument(Request $request)
    {
        $response = [];

        ////////////////////////////////////////
        $ary = [];
        $result = DB::table('t_agent_document_a')
            ->orderBy('agent')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        foreach ($result as $v) {
            $ary[$v->agent][] = [
                'ym' => "{$v->year}-{$v->month}",
                'content' => $v->content
            ];
        }
        ////////////////////////////////////////

        $result2 = DB::table('t_agent_document_b')
            ->where('agent', '=', $request->agent)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $ary2 = [];
        foreach ($result2 as $v2) {
            if ("{$v2->year}-{$v2->month}" == "2019-09") {
                $ary2 = $ary[$v2->content];
            } else {
                $ary2[] = [
                    'ym' => "{$v2->year}-{$v2->month}",
                    'content' => $v2->content
                ];
            }
        }

        $response = $ary2;


        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAgentName(Request $request)
    {
        $response = [];
        $ary = [
            'truth|真実',
            'identity|アイデンティティ',
            'techbiz|テックビズ',
            'olive|フリーランスのミカタ(olive)',
            'an|アンコンサルティング（フリエン）',
            'manoa|マノア・リノ',
            'threeshake|スリーシェイク',
            'mid|ミッドワークス',
            'h-basis|ヘルスベイシス'
        ];
        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getYoutubeList(Request $request)
    {

        $response = [];

        if (trim($request->bunrui) == "") {
            $result = DB::table('t_youtube_data')
                ->orderBy('getdate', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            if (trim($request->bunrui) == "blank") {
                $sql = " select * from t_youtube_data where (bunrui is null or bunrui = '') order by getdate desc, id desc; ";
                $result = DB::select($sql);
            } else if ($request->bunrui == "search") {

                if (trim($request->searchBunrui) != "") {

                    $sql = " select * from t_youtube_data where (title like '%{$request->word}%' or youtube_id like '%{$request->word}%' or channel_title like '%{$request->word}%') and bunrui = '{$request->searchBunrui}' order by bunrui asc, pubdate desc; ";

                } else {

                    $sql = " select * from t_youtube_data where (title like '%{$request->word}%' or youtube_id like '%{$request->word}%' or channel_title like '%{$request->word}%') order by bunrui asc, pubdate desc; ";

                }

                $result = DB::select($sql);

            } else {
                $result = DB::table('t_youtube_data')
                    ->where('bunrui', '=', $request->bunrui)
                    ->orderBy('getdate', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        $threeDaysAgo = strtotime(date("Ymd")) - (86400 * 3);

        $ary = [];
        foreach ($result as $v) {

            if ($v->del == '1') {
                continue;
            }

            if ($request->threedays == 1) {
                if (strtotime($v->getdate) < $threeDaysAgo) {
                    continue;
                }
            }

            $playtime = "";
            if (trim($v->playtime) != "") {
                if (preg_match("/PT(.+)H(.+)M(.+)S/", trim($v->playtime), $m)) {
                    $hour = sprintf("%02d", $m[1]);
                    $minute = sprintf("%02d", $m[2]);
                    $second = sprintf("%02d", $m[3]);
                    $playtime = "{$hour}:{$minute}:{$second}";
                } else if (preg_match("/PT(.+)M(.+)S/", trim($v->playtime), $m)) {
                    $minute = sprintf("%02d", $m[1]);
                    $second = sprintf("%02d", $m[2]);
                    $playtime = "00:{$minute}:{$second}";
                }
            }

            $ary[] = [
                'youtube_id' => $v->youtube_id,
                'title' => $v->title,
                'getdate' => $v->getdate,
                'url' => $v->url,

                'bunrui' => (trim($v->bunrui) != "") ? $v->bunrui : 0,
                'special' => (trim($v->special) != "") ? $v->special : 0,

                'pubdate' => (trim($v->pubdate) != "") ? $v->pubdate : "",
                'channel_id' => (trim($v->channel_id) != "") ? $v->channel_id : "",
                'channel_title' => (trim($v->channel_title) != "") ? $v->channel_title : "",

                'playtime' => $playtime
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     * @return void
     */
    public function getDeletedVideo()
    {

        $result = DB::table('t_youtube_data')
            ->where('del', '=', '1')
            ->get();

        $ary = [];
        foreach ($result as $v) {

            $playtime = "";
            if (trim($v->playtime) != "") {
                if (preg_match("/PT(.+)H(.+)M(.+)S/", trim($v->playtime), $m)) {
                    $hour = sprintf("%02d", $m[1]);
                    $minute = sprintf("%02d", $m[2]);
                    $second = sprintf("%02d", $m[3]);
                    $playtime = "{$hour}:{$minute}:{$second}";
                } else if (preg_match("/PT(.+)M(.+)S/", trim($v->playtime), $m)) {
                    $minute = sprintf("%02d", $m[1]);
                    $second = sprintf("%02d", $m[2]);
                    $playtime = "00:{$minute}:{$second}";
                }
            }

            $ary[] = [
                'youtube_id' => $v->youtube_id,
                'title' => $v->title,
                'getdate' => $v->getdate,
                'url' => $v->url,

                'bunrui' => (trim($v->bunrui) != "") ? $v->bunrui : 0,
                'special' => (trim($v->special) != "") ? $v->special : 0,

                'pubdate' => (trim($v->pubdate) != "") ? $v->pubdate : "",
                'channel_id' => (trim($v->channel_id) != "") ? $v->channel_id : "",
                'channel_title' => (trim($v->channel_title) != "") ? $v->channel_title : "",

                'playtime' => $playtime
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateVideoPlayedAt(Request $request)
    {

        try {
            DB::beginTransaction();

            $update = [];
            $update['last_played_at'] = $request->date;

            DB::table('t_youtube_data')->where('youtube_id', '=', $request->youtube_id)->update($update);

            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     */
    public function bunruiYoutubeData(Request $request)
    {


        try {
            DB::beginTransaction();

            switch ($request->bunrui) {

                case "recycling":
                    $sql = " update t_youtube_data set del = '0', bunrui = '', category1='', category2='' where youtube_id in ({$request->youtube_id}); ";
                    break;

                case "delete":
                    $sql = " update t_youtube_data set del = '1', bunrui = '', category1='', category2='' where youtube_id in ({$request->youtube_id}); ";
                    break;

                case "erase":
                    $sql = " update t_youtube_data set bunrui = '', category1='', category2='' where youtube_id in ({$request->youtube_id}); ";
                    break;

                case "special":
//                    $ex_val = explode(",", $request->youtube_id);
//                    foreach ($ex_val as $v) {
//                        $ex_v = explode("|", trim($v));
//                        $sql = " update t_youtube_data set special = '{$ex_v[1]}' where youtube_id = '{$ex_v[0]}'; ";
//                        DB::statement($sql);
//                    }

                    $sql = " update t_youtube_data set special = '1' where youtube_id in ({$request->youtube_id}); ";

//                    $ex_val = explode(",", $request->youtube_id);
//                    foreach ($ex_val as $v) {
//                        $yid = trim(strtr($v, ["'" => ""]));
//
//                        $result = DB::table('t_youtube_data')->where('youtube_id', $yid)->first();
//
//                        switch ($result->special) {
//                            case 1:
//                                $sql = " update t_youtube_data set special = '0' where youtube_id = '{$yid}'; ";
//                                break;
//                            case 0:
//                                $sql = " update t_youtube_data set special = '1' where youtube_id = '{$yid}'; ";
//                                break;
//                        }
//                        DB::statement($sql);
//                    }
                    break;

                default:
                    $sql = " update t_youtube_data set bunrui = '{$request->bunrui}' where youtube_id in ({$request->youtube_id}); ";
                    break;
            }

//
//            if ($request->bunrui != 'special') {
//                DB::statement($sql);
//            }
//
//


            DB::statement($sql);


            DB::commit();

            $response = $request->all();
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     */
    public function getBunruiName()
    {

        $response = [];

        $sql = " select bunrui from t_youtube_data where bunrui is not null and del = '0' group by bunrui; ";
        $result = DB::select($sql);

        $ary = [];
        $ary2 = [];
        foreach ($result as $v) {
            if (trim($v->bunrui) == "") {
                continue;
            }
            $ary[] = $v->bunrui;

            $ex_bunrui = explode(" ", $v->bunrui);
            if (count($ex_bunrui) > 1) {
                $ary2[] = trim($ex_bunrui[0]);
            }
            $ex_bunrui2 = explode("_", $v->bunrui);
            if (count($ex_bunrui2) > 1) {
                $ary2[] = trim($ex_bunrui2[0]);
            }
            $ex_bunrui3 = explode("-", $v->bunrui);
            if (count($ex_bunrui3) > 1) {
                $ary2[] = trim($ex_bunrui3[0]);
            }
        }

        $ary4 = array_unique($ary2);

        $ary3 = [];
        foreach ($ary as $k => $v) {
            $ar = [];
            $ar[] = $v;

            $ex_v = explode(" ", $v);
            $ex_v2 = explode("_", $v);
            $ex_v3 = explode("-", $v);

            $flag = 0;
            if (
                (in_array($ex_v[0], $ary4)) or (in_array($ex_v2[0], $ary4)) or (in_array($ex_v3[0], $ary4))
            ) {
                $flag = 1;
            }

            $ar[] = $flag;
            $ary3[] = implode("|", $ar);
        }

        $response = $ary3;

        return response()->json(['data' => $response]);

    }


    /**
     *
     */
    public function getSpecialVideo()
    {

        $response = [];

        $result = DB::table('t_youtube_data')
            ->where('special', '=', '1')
            ->where('del', '=', '0')
            ->orderBy('bunrui')
            ->orderBy('getdate', 'desc')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[$v->bunrui][] = [
                'youtube_id' => $v->youtube_id,
                'title' => $v->title,
                'getdate' => $v->getdate,
                'url' => $v->url,
                'bunrui' => $v->bunrui,
                'special' => $v->special,
                'pubdate' => $v->pubdate,
                'channel_id' => $v->channel_id,
                'channel_title' => $v->channel_title,
                'playtime' => $v->playtime,
            ];
        }

        $ary2 = [];
        $keepBunrui = '';
        foreach ($result as $v) {
            if ($keepBunrui != $v->bunrui) {
                $ary2[] = [
                    'bunrui' => $v->bunrui,
                    'count' => count($ary[$v->bunrui]),
                    'item' => $ary[$v->bunrui],
                ];
            }

            $keepBunrui = $v->bunrui;
        }

        $response = $ary2;

        return response()->json(['data' => $response]);

    }

    /**
     *
     */
    public function getOrderedVideo()
    {

        $response = [];

        $result = DB::table('t_youtube_data')
            ->where('del', '=', '0')
            ->orderBy('getdate', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = [
                'youtube_id' => $v->youtube_id,
                'title' => $v->title,
                'getdate' => $v->getdate,
                'url' => $v->url,
                'pubdate' => $v->pubdate,
                'channel_id' => $v->channel_id,
                'channel_title' => $v->channel_title,
                'playtime' => $v->playtime,
                'special' => $v->special,
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param $year
     * @return array
     */
    private function getRandomPhoto($year)
    {
        //-----------//
        $skiplist = [];
        $skipfile = "/var/www/html/Temple/public/mySetting/skiplist";
        $content = file_get_contents($skipfile);
        foreach (explode("\n", $content) as $v) {
            if (trim($v) == "") {
                continue;
            }
            $skiplist[] = trim($v);
        }
        //-----------//

        //-----------//
        $skiplist2 = [];
        $skipfile = "/var/www/html/Temple/public/mySetting/skiplist2";
        $content = file_get_contents($skipfile);
        foreach (explode("\n", $content) as $v) {
            if (trim($v) == "") {
                continue;
            }
            $skiplist2[] = trim($v);
        }
        //-----------//

        $_dir = "/var/www/html/BrainLog/public/UPPHOTO";
        $filelist = $this->scandir_r($_dir);

        sort($filelist);

        $files2 = [];
        foreach ($filelist as $v) {
            if (!preg_match("/" . $year . "/", trim($v))) {
                continue;
            }

            $files2[] = $v;
        }

        sort($files2);

        $files3 = [];
        foreach ($files2 as $v) {
            $ex_v = explode("/", trim($v));

            $str = [];
            for ($i = 6; $i <= 9; $i++) {
                $str[] = $ex_v[$i];
            }

            if (in_array($ex_v[8], $skiplist)) {
                continue;
            }
            if (in_array($ex_v[9], $skiplist2)) {
                continue;
            }

            $files3[$ex_v[8]][] = "http://toyohide.work/BrainLog/public/" . implode("/", $str);
        }

        foreach ($files3 as $date => $v) {
            $rand = mt_rand(0, count($v) - 1);
            $files[$date] = $v[$rand];
        }

        return $files;
    }


}
