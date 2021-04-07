<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{

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

        list($year, $month, $day) = explode("-", $request->date);

        $end_date = date("Y-m-d", strtotime($request->date) + (86400 * 7));

        for ($i = strtotime($request->date); $i < strtotime($end_date); $i += 86400) {

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

            foreach ($dailySpend as $v) {
                $cnt = (isset($response[$date])) ? count($response[$date]) : 0;
                $response[$date][$cnt]['koumoku'] = $v->koumoku;
                $response[$date][$cnt]['price'] = $v->price;
            }

            foreach ($credit as $v) {
                $cnt = (isset($response[$date])) ? count($response[$date]) : 0;
                $response[$date][$cnt]['koumoku'] = $v->item;
                $response[$date][$cnt]['price'] = $v->price;
            }
        }

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
            $cnt = count($response[$ymd]);
            $response[$ymd][$cnt]['date'] = $ymd;
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
            $cnt = count($response[$ymd]);
            $response[$ymd][$cnt]['date'] = $ymd;
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
            $cnt = count($response[$ymd]);
            $response[$ymd][$cnt] = $v->article;
        }

        return response()->json(['data' => $response]);

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
            $cnt = count($response[$ymd]);
            $response[$ymd][$cnt]['date'] = $ymd;
            $response[$ymd][$cnt]['time'] = $v->time;
            $response[$ymd][$cnt]['place'] = $v->place;
            $response[$ymd][$cnt]['price'] = $v->price;
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

        list($year, $month, $day) = explode("-", $request->date);

        $end_date = date("Y-m-d", strtotime($request->date) + (86400 * 7));

        for ($i = strtotime($request->date); $i < strtotime($end_date); $i += 86400) {

            $date = date("Y-m-d", $i);
            list($year, $month, $day) = explode("-", $date);

            $result = DB::table('t_timeplace')
                ->where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)
                ->orderBy('time')->get();

            foreach ($result as $v) {
                $cnt = (isset($response[$date])) ? count($response[$date]) : 0;
                $response[$date][$cnt]['time'] = $v->time;
                $response[$date][$cnt]['place'] = $v->place;
                $response[$date][$cnt]['price'] = $v->price;
            }
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
            ->where('price', '=', 0)
            ->orderBy('year')->orderBy('month')->orderBy('day')
            ->get();

        foreach ($result as $v) {
            $response[] = $v->year . "-" . $v->month . "-" . $v->day;
        }

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
                'pay_d' => $request->pay_d
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

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

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

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

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

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

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
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

                    $ary[trim($ex_val[3]) . "|" . $date][] = [
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
                    $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

                    $ary[trim($ex_val[1]) . "|" . $date][] = [
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
                    $price = strtr(trim($ex_val[2]), [',' => '']);

                    $monthDiff = $this->getMonthDiff($date, $v2->year . '-' . $v2->month);

                    $ary[trim($ex_val[1]) . "|" . $date][] = [
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

        list($year, $month, $day) = explode("-", $request->date);
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
                $tanka = trim(strtr($ex_ex_article[7], ['円' => '', ',' => '']));
                $kosuu = trim($ex_ex_article[8]);
                $price = trim(strtr($ex_ex_article[9], ['円' => '', ',' => '']));

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

        $response = $ary;

        return response()->json(['data' => $response]);
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

            foreach ($spend as $v) {
                $ary[$duty][] = $v->year . '-' . $v->month . '-' . $v->day . '|' . $v->price;
            }

            foreach ($credit as $v) {
                $ary[$duty][] = $v->year . '-' . $v->month . '-' . $v->day . '|' . $v->price;
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

                            $response[] = [
                                'date' => trim($ex_v[0]),
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

}
