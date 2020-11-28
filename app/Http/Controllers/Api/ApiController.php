<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
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

        list($year, $month, $day) = explode("-", $request->all()['date']);

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

        list($year, $month, $day) = explode("-", $request->all()['date']);

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

        list($year, $month, $day) = explode("-", $request->all()['date']);

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

}
