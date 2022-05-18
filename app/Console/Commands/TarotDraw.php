<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TarotDraw extends Command
{

    protected $signature = 'TarotDraw';

    protected $description = 'TarotDraw';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ///////////////////////////////////////////////
        $date = date("Y-m-d");
        list($year, $month, $day) = explode("-", $date);

        $result = DB::table("t_tarotdraw")
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('day', '=', $day)
            ->first();

        print_r($result);

        if (!empty($result)){
            exit();
        }
        ///////////////////////////////////////////////

        $dice1 = mt_rand(1, 78);
        $result = DB::table("t_tarot")->where("id", "=", $dice1)->first();

        $dice2 = mt_rand(1, 10);
        $just_reverse = ($dice2 % 2 == 1) ? "just" : "reverse";

        $insert = [];
        $insert['year'] = $year;
        $insert['month'] = $month;
        $insert['day'] = $day;
        $insert['tarot_id'] = $result->id;
        $insert["name"] = $result->name;
        $insert["reverse"] = ($just_reverse == "just") ? 0 : 1;

        DB::table("t_tarotdraw")->insert($insert);

    }
}
