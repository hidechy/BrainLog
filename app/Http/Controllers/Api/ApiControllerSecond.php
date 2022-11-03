<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApiControllerSecond extends Controller
{

    /*
     *
     */
    public function getKigoSeasonRandomList(Request $request): array
    {

        if ($request->season != '-') {
            $sql = " update t_haiku_season set cnt=cnt+1 where season_en = '{$request->season}'; ";
            DB::statement($sql);
        }

        $query_common = " select id from t_haiku_kigo";
        $query_common .= " where season = '{$request->season}'";

        $query1 = $query_common;
        $query1 .= " order by id limit 1; ";
        $result1 = DB::select($query1);

        $query2 = $query_common;
        $query2 .= " order by id desc limit 1; ";
        $result2 = DB::select($query2);

        $start = $result1[0]->id;
        $end = $result2[0]->id;

        ////////////////////////////////////////////////
        $list = [];

        $getIds = [];
        while (true) {
            $dice1 = mt_rand($start, $end);
            $getIds[$dice1] = "";
            if (count($getIds) > 20) {
                break;
            }
        }


        $result5 = DB::table('t_haiku_season')
            ->where('season_en', $request->season)
            ->first();


        foreach ($getIds as $k => $v) {

            $result = DB::table('t_haiku_kigo')
                ->where('id', '=', $k)->first();

            $list[] = [
                "title" => $result->title,
                "yomi" => $result->yomi,
                "detail" => $result->detail,
                "length" => $result->length,
                "category" => $result->category,
                "cnt" => ($result->cnt + 1),
                "seasonCnt" => $result5->cnt,
            ];

            DB::table('t_haiku_kigo')
                ->where('id', $k)
                ->update(['cnt' => ($result->cnt + 1)]);
        }


        ////////////////////////////////////////////////

        ////////////////////////////////////////////////
        $len = [];

        $inIds = [];
        for ($i = $start; $i <= $end; $i++) {
            $inIds[] = $i;
        }

        $result4 = DB::table('t_haiku_kigo')
            ->whereIn('id', $inIds)
            ->get(['length']);

        foreach ($result4 as $v4) {
            $len[] = $v4->length;
        }
        ////////////////////////////////////////////////

        return [
            'min' => min($len),
            'max' => max($len),
            'list' => $list
        ];

    }

    /*
     *
     */
    public function getKigoSearchedList(Request $request): array
    {

        if ($request->season != '-') {
            $sql = " update t_haiku_season set cnt=cnt+1 where season_en = '{$request->season}'; ";
            DB::statement($sql);
        }

        $query = " select * from t_haiku_kigo where season = '{$request->season}'";

        if (isset($request->title) && trim($request->title) != "") {
            $query .= " and title like '{$request->title}%'";
        }

        if (isset($request->yomi_head) && trim($request->yomi_head) != "") {
            $query .= " and yomi like '{$request->yomi_head}%'";
        }

        if (isset($request->length) && $request->length > 0) {
            $query .= " and length = {$request->length}";
        }

        if (isset($request->category) && trim($request->category) != "") {
            $query .= " and category = '{$request->category}'";
        }

        $query .= " order by id";

        $result = DB::select($query);

        ////////////////////////////////////////////////
        $len = [];


        $result5 = DB::table('t_haiku_season')
            ->where('season_en', $request->season)
            ->first();


        $list = [];
        foreach ($result as $v) {
            $list[] = [
                "title" => $v->title,
                "yomi" => $v->yomi,
                "detail" => $v->detail,
                "length" => $v->length,
                "category" => $v->category,
                "cnt" => ($v->cnt + 1),
                "seasonCnt" => $result5->cnt,
            ];

            $len[] = $v->length;

            DB::table('t_haiku_kigo')
                ->where('id', $v->id)
                ->update(['cnt' => ($v->cnt + 1)]);

        }
        ////////////////////////////////////////////////

        return [
            'min' => min($len),
            'max' => max($len),
            'list' => $list
        ];

    }

    /*
     *
     */
    public function getKigoSeasonList(Request $request): array
    {
        $result = DB::table('t_haiku_season')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = [
                'season_en' => $v->season_en,
                'season_jp' => $v->season_jp,
                'cnt' => $v->cnt,
            ];
        }

        return $ary;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAllTemple()
    {
        $response = [];

        $photoUrl = $this->getPhotoUrl();

        $ary = [];
        foreach ($photoUrl as $v) {
            foreach ($v as $date => $photo) {
                list($year, $month, $day) = explode("-", $date);
                $result = DB::table('t_temple')
                    ->where('year', '=', $year)
                    ->where('month', '=', $month)
                    ->where('day', '=', $day)
                    ->first();

                $rand = mt_rand(0, count($photo) - 1);
                $thumbnail = $photo[$rand];

                /////////////////////////////////////////////////
                $_lat = '';
                $_lng = '';

                if (trim($result->lat) == "" || trim($result->lng) == "") {

                    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $result->address . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
                    $content = file_get_contents($url);
                    $jsonStr = json_decode($content);

                    if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                        $_lat = $jsonStr->results[0]->geometry->location->lat;
                    }

                    if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                        $_lng = $jsonStr->results[0]->geometry->location->lng;
                    }

                    //------------------------
                    $update = [];
                    if (trim($_lat) != "") {
                        $update['lat'] = $_lat;
                    }
                    if (trim($_lng) != "") {
                        $update['lng'] = $_lng;
                    }
                    DB::table('t_temple')->where('id', '=', $result->id)->update($update);
                    //------------------------

                } else {
                    $_lat = trim($result->lat);
                    $_lng = trim($result->lng);
                }
                /////////////////////////////////////////////////

                //+---------+--------------+------+-----+---------+----------------+
                $result2 = DB::table('t_temple_latlng')
                    ->where('temple', '=', $result->temple)
                    ->first();
                if (empty($result2)) {
                    $insert = [
                        'temple' => $result->temple,
                        'address' => $result->address,
                        'lat' => $_lat,
                        'lng' => $_lng
                    ];

                    DB::table('t_temple_latlng')->insert($insert);
                }

                if (trim($result->memo) != "") {
                    $ex_memo = explode("、", $result->memo);
                    foreach ($ex_memo as $v2) {
                        $result3 = DB::table('t_temple_latlng')
                            ->where('temple', '=', $v2)
                            ->first();
                        if (empty($result3)) {
                            $insert = [
                                'temple' => $v2
                            ];

                            DB::table('t_temple_latlng')->insert($insert);
                        }
                    }
                }

                $ary['list'][] = [
                    'date' => $date,
                    'temple' => $result->temple,
                    'address' => $result->address,
                    'station' => $result->station,

                    'memo' => (trim($result->memo) != "") ? $result->memo : "",
                    'gohonzon' => (trim($result->gohonzon) != "") ? $result->gohonzon : "",

                    'thumbnail' => $thumbnail,
                    'lat' => $_lat,
                    'lng' => $_lng,
                    'photo' => $photo
                ];
            }
        }

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDateTemple(Request $request)
    {
        $response = [];

        $ary = [];
        list($year, $month, $day) = explode("-", $request->date);
        $result = DB::table('t_temple')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->first();

        $photo = $this->getPhotoUrl($request->date);
        $rand = mt_rand(0, count($photo) - 1);
        $thumbnail = $photo[$rand];

        /////////////////////////////////////////////////
        $_lat = '';
        $_lng = '';

        if (trim($result->lat) == "" || trim($result->lng) == "") {

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $result->address . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
            $content = file_get_contents($url);
            $jsonStr = json_decode($content);
            if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                $_lat = $jsonStr->results[0]->geometry->location->lat;
            }
            if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                $_lng = $jsonStr->results[0]->geometry->location->lng;
            }

            //------------------------
            $update = [];
            if (trim($_lat) != "") {
                $update['lat'] = $_lat;
            }
            if (trim($_lng) != "") {
                $update['lng'] = $_lng;
            }
            DB::table('t_temple')->where('id', '=', $result->id)->update($update);
            //------------------------

        } else {
            $_lat = trim($result->lat);
            $_lng = trim($result->lng);
        }

        /////////////////////////////////////////////////

        $ary = [
            'date' => $request->date,
            'temple' => $result->temple,
            'address' => $result->address,
            'station' => $result->station,

            'memo' => (trim($result->memo) != "") ? $result->memo : "",
            'gohonzon' => (trim($result->gohonzon) != "") ? $result->gohonzon : "",

            'thumbnail' => $thumbnail,
            'lat' => $_lat,
            'lng' => $_lng,
            'photo' => $photo
        ];

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     *
     */
    public function getTempleLatLng()
    {
        $response = [];

        $result = DB::table('t_temple_latlng')->get();

        $ary = [];
        foreach ($result as $v) {
            $ary['list'][] = [
                'temple' => $v->temple,
                'address' => $v->address,
                'lat' => $v->lat,
                'lng' => $v->lng
            ];
        }

        return $ary;

//        $response = $ary;
//
//        return response()->json(['data' => $response]);
    }

    /**
     * @param null $pickdate
     * @return array|mixed
     */
    private function getPhotoUrl($pickdate = null)
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

        foreach ($filelist as $v) {

            $pos = strpos($v, 'UPPHOTO');
            $str = substr(trim($v), $pos);

            list(, $year, $date, $photo) = explode("/", $str);

            if (in_array($date, $skiplist)) {
                continue;
            }
            if (in_array($photo, $skiplist2)) {
                continue;
            }

            $photolist[$year][$date][] = strtr($v, ['/var/www/html' => 'http://toyohide.work']);
        }

        if (is_null($pickdate)) {
            return $photolist;
        } else {
            list($year, $month, $day) = explode("-", $pickdate);
            return $photolist[$year][$pickdate];
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


    /*
     *
     */
    public function getTempleName(Request $request)
    {

        $response = [];

        $sql = " select * from t_temple where temple like '%{$request->name}%' or memo like '%{$request->name}%' order by year,month,day; ";
        $result = DB::select($sql);

        $ary = [];
        foreach ($result as $v) {
            $str = [];
            $str[] = $v->temple;
            if (is_null($v->memo) || trim($v->memo) == "") {
            } else {
                $str[] = $v->memo;
            }
            $ex_str = explode("、", implode("、", $str));

            $data = [];
            foreach ($ex_str as $v2) {
                $result2 = DB::table('t_temple_latlng')
                    ->where('temple', $v2)
                    ->first();

                $data[] = [
                    "temple" => $result2->temple,
                    "address" => $result2->address,
                    "lat" => $result2->lat,
                    "lng" => $result2->lng
                ];
            }

            $ary[] = [
                "year" => $v->year,
                "month" => $v->month,
                "day" => $v->day,
                "data" => $data
            ];
        }

        $response = $ary;

        return response()->json(['list' => $response]);

    }


    /**
     *
     */
    public function getCategoryRate(Request $request)
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
//            $ary[] = "{$v->id}:" . trim(strtr($v->name, $change)) . ":" . $ary4[$v->id];


            $name = trim(strtr($v->name, $change));
            $ary[] = [
                "id" => $v->id,
                "name" => "{$request->category} {$name}",
                "rate" => $ary4[$v->id],
            ];


        }

        $response = $ary;

        return response()->json(['data' => $response]);

    }


}
