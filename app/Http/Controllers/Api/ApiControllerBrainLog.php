<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class ApiControllerBrainLog extends Controller
{


    /**
     * @param Request $request
     * @return void
     */
    public function getOnedayArticle(Request $request)
    {
        $response = [];

        list($year, $month, $day) = explode("-", $request->date);

        $result = DB::table("t_article{$year}")
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->orderBy('id')
            ->get();

        $ary = [];
        foreach ($result as $v) {
            $ary[] = [
                'id' => $v->id,
                'article' => strtr($v->article, ['\r\n' => '\n']),
            ];
        }

        $response = $ary;

        return response()->json(['data' => $response]);
    }


}
