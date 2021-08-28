<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{

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

        $response[0] = 1 + date('W', $time + 86400) - date('W', strtotime(date('Y-m', $time)) + 86400);

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
        } catch (\Exception $e) {
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
            $summary4[$koumoku]['percent'] = ($v['sum'] > 0) ? floor($v['sum'] / $total * 100) : 0;
        }

        $item = $this->getItemMidashi();

        $i = 0;
        foreach ($item as $im) {
            if (isset($summary4[$im])) {
                $response[$i]['item'] = $im;
                $response[$i]['sum'] = $summary4[$im]['sum'];
                $response[$i]['percent'] = $summary4[$im]['percent'];
                $i++;
            }
        }

        return response()->json(['data' => $response]);
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
            $summary4[$koumoku]['percent'] = floor($v['sum'] / $total * 100);
        }

        $item = $this->getItemMidashi();

        $i = 0;
        foreach ($item as $im) {
            if (isset($summary4[$im])) {
                $response[$i]['item'] = $im;
                $response[$i]['sum'] = $summary4[$im]['sum'];
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
税金
年金
国民年金基金
国民健康保険
アイアールシー
手数料
不明
利息
臨時収入
給付金
プラス
メルカリ
投資信託
株式買付
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


//
//
//
//                if (preg_match("/^ＮＴＴ/", $v['item'])) {
//                    $v['flag'] = ($lastItem == "ＮＴＴ") ? 0 : 1;
//                } else {
//                    $v['flag'] = ($lastItem == $v['item']) ? 0 : 1;
//                }
//
//
//
//


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
     * @param Request $request
     */
    public function amazonPurchaseList(Request $request)
    {
        $response = [];

        $ary3 = [];

        list($year, $month, $day) = explode("-", $request->date);
        $table = 't_article' . $year;

        $result = DB::table($table)->where('article', 'like', '%** Amazon **%')->first();

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

            $article = strtr($result->article, $replace);
            $ex_article = explode("注文日", $article);

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

                $ary[$purchase_date][] = ['date' => $purchase_date, 'price' => $price, 'order_number' => $order_number, 'item' => implode("\n", $other)];
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

                $ary[] = [
                    'date' => $date,
                    'pos' => $pos,
                    'item' => $item,
                    'tanka' => $tanka,
                    'kosuu' => $kosuu,
                    'price' => $price
                ];
            }
        }

        return $ary;
    }


    /**
     * @param Request $request
     */
    public function dutyData(Request $request)
    {
        $response = [];

        $dutyItems = ['税金', '年金', '国民年金基金', '国民健康保険'];

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
     * @param Request $request
     */
    public function getFundRecord(Request $request)
    {
        $response = [];

        $sql = " select fundname from t_fund group by fundname; ";
        $result = DB::select($sql);

        $youbi = ['日', '月', '火', '水', '木', '金', '土'];

        foreach ($result as $v) {
            $result2 = DB::table('t_fund')
                ->where('fundname', '=', $v->fundname)
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('day')
                ->get();

            foreach ($result2 as $v2) {

                $date = "$v2->year-$v2->month-$v2->day";
                $_youbi = $youbi[date("w", strtotime($date))];

                $response[$v->fundname][] = [
                    'year' => $v2->year,
                    'month' => $v2->month,
                    'day' => $v2->day . "(" . $_youbi . ")",
                    'base_price' => $v2->base_price,
                    'compare_front' => $v2->compare_front,
                    'yearly_return' => $v2->yearly_return
                ];
            }
        }

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
    public function worktimemonthdata(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_worktime')
            ->where('year', '=', $year)->where('month', '=', $month)
            ->orderBy('day')->get();

        foreach ($result as $v) {
            $date = $v->year . '-' . $v->month . '-' . $v->day;
            $response[$date]['work_start'] = date("H:i", strtotime($v->work_start));
            $response[$date]['work_end'] = date("H:i", strtotime($v->work_end));
        }

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
            'ジェニュイン' => 2];

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
            '2021-02-01 / ' . $hit_end => '09:00'//HIT
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
        $hit_end = date("Y-m-d");

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
        }

        $ary3["2019-11"][0] = "";

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
                $hour = floor($salary / $summary);
            }

            $ary6[] = $ym . ";" . $summary . ";$company;$genba;$salary;$hour;" . $str;
        }

        $response = $ary6;
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return 0;
        }

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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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


}
