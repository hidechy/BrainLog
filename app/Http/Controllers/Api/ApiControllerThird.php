<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApiControllerThird extends Controller
{

    /**
     *
     */
    public function getPrefecture()
    {
        $response = [];

        $area = [
            "北海道・東北" => "北海道|青森県|秋田県|岩手県|山形県|宮城県|福島県",
            "関東" => "群馬県|栃木県|茨城県|埼玉県|千葉県|東京都|神奈川県",
            "中部" => "新潟県|富山県|石川県|福井県|長野県|岐阜県|山梨県|愛知県|静岡県",
            "近畿" => "京都府|滋賀県|大阪府|奈良県|三重県|和歌山県|兵庫県",
            "中国" => "鳥取県|岡山県|島根県|広島県|山口県",
            "四国" => "香川県|愛媛県|徳島県|高知県",
            "九州・沖縄" => "福岡県|佐賀県|長崎県|大分県|熊本県|宮崎県|鹿児島県|沖縄県"
        ];

        $ary = [];

        $i = 0;
        foreach ($area as $ar => $v) {
            $ex_v = explode("|", $v);

            $j = 0;
            foreach ($ex_v as $v2) {
                $ary[] = ['areaNo' => $i, 'area' => $ar, 'prefecture' => trim($v2), 'prefNo' => $j];

                $j++;
            }

            $i++;
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getPrefectureTrainCompany(Request $request)
    {
        $response = [];

        $result = DB::table('t_station')
            ->where('prefecture', '=', $request->prefecture)
            ->get();

        $station_trainNumber = [];
        foreach ($result as $k => $v) {
            $station_trainNumber[$v->train_number] = "";
        }
        $s_trainNumber = array_keys($station_trainNumber);

        sort($s_trainNumber);

        $ary = [];

        $result2 = DB::table('t_train')
            ->whereIn('train_number', $s_trainNumber)
            ->get();

        $trainCompanyId = [];
        foreach ($result2 as $k2 => $v2) {
            $trainCompanyId[$v2->company_id] = "";
        }
        $tcId = array_keys($trainCompanyId);

        $result3 = DB::table('t_train_company')
            ->whereIn('company_id', $tcId)
            ->get();

        foreach ($result3 as $v3) {
            $ary[] = [
                'company_id' => $v3->company_id,
                'company_name' => $v3->company_name,
                'flag' => $v3->flag,
                'train' => [],
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getLifetimeRecordItem(Request $request)
    {
        $response = [];

        $result = DB::table('t_lifetime_item')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = ['item' => $v->item];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function insertLifetime(Request $request)
    {
        try {
            DB::beginTransaction();

            list($year, $month, $day) = explode("-", $request->date);

            DB::table('t_lifetime')
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->delete();

            $exLifetime = explode("|", $request->lifetime);

            $insert = [];
            $insert['year'] = $year;
            $insert['month'] = $month;
            $insert['day'] = $day;

            foreach ($exLifetime as $k => $v) {
                $hour = sprintf("%02d", $k);
                $insert["hour{$hour}"] = $v;
            }

            DB::table('t_lifetime')->insert($insert);

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
     * @return void
     */
    public function getLifetimeDateRecord(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_lifetime')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->first();

        $ary = $result;

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getLifetimeYearlyRecord(Request $request)
    {

        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table('t_lifetime')
            ->where('year', '=', $year)
            ->orderBy('month')
            ->orderBy('day')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = $v;
        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getWalkRecord3(Request $request)
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


        list($pYear, $pMonth, $pDay) = explode("-", $request->date);


        $dt = new Carbon($request->date);
        $nextMonth = $dt->addMonth();
        $nm = explode(" ", $nextMonth);


        $ary3 = [];
        foreach ($ary2 as $v) {
            list($lYear, $lMonth, $lDay) = explode("-", $v['date']);

            if ($lYear == $pYear && $lMonth == $pMonth) {
                $ary3[] = $v;
            }
        }


        $response = [
            'next' => $nm[0],
            'record' => $ary3,
        ];

        return response()->json(['data' => $response]);
    }


}
