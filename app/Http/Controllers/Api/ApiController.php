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

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%ユーシーカード内訳%')->first(['article']);

        $ex_result = explode("\n", $result->article);
        foreach ($ex_result as $v) {
            $val = trim(strip_tags($v));
            if (preg_match("/円.+円/", trim($val))) {
                $ex_val = explode("\t", $val);
                $date = strtr(trim($ex_val[1]), ['/' => '-']);
                $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);
                $response[] = ['item' => trim($ex_val[3]), 'price' => $price, 'date' => $date, 'kind' => 'uc'];
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%楽天カード内訳%')->first(['article']);

        $ex_result = explode("\n", $result->article);
        foreach ($ex_result as $v) {
            $val = trim(strip_tags($v));
            if (preg_match("/本人/", trim($val))) {
                $ex_val = explode("\t", $val);
                $date = strtr(trim($ex_val[0]), ['/' => '-']);
                $price = strtr(trim($ex_val[4]), [',' => '', '¥' => '']);
                $response[] = ['item' => trim($ex_val[1]), 'price' => $price, 'date' => $date, 'kind' => 'rakuten'];
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)->where('month', $month)
            ->where('article', 'like', '%住友カード内訳%')->first(['article']);

        $ex_result = explode("\n", $result->article);
        foreach ($ex_result as $v) {
            $val = trim(strip_tags($v));
            if (preg_match("/◎/", trim($val))) {
                $ex_val = explode("\t", $val);
                $date = strtr("20" . trim($ex_val[0]), ['/' => '-']);
                $price = strtr(trim($ex_val[2]), [',' => '']);
                $response[] = ['item' => trim($ex_val[1]), 'price' => $price, 'date' => $date, 'kind' => 'sumitomo'];
            }
        }
        //------------------------------------------//

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

}
