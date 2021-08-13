<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class MealCalorieGet extends Command
{

    protected $signature = 'MealCalorieGet';

    protected $description = 'MealCalorieGet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $url = "https://fukupon.jp/papa/a/19080921";
        $crawler = \Goutte::request('GET', $url);

        $cal = $crawler->filter('.table_row.stripe')->filter('tr')->filter('td')->each(function ($node) {
            return $node->html();
        });

        $ary = [];
        for ($i = 0; $i < count($cal); $i += 2) {
            $ary[] = [
                'menu' => strtr($cal[$i], ['\?' => ' ']),
                'calorie' => $cal[$i + 1]
            ];
        }

        print_r($ary);


        /*
        $dr = $crawler->filter('.fortune_daily_rank')->text();
        $daily_ranking = strtr($dr, ['位' => '']);

        $re = $crawler->filter('.fortune_daily_item.fortune_daily_item_renaiun .fortune_daily_item_body')->text();
        $renaiun = $re;

        $ki = $crawler->filter('.fortune_daily_item.fortune_daily_item_kinun .fortune_daily_item_body')->text();
        $kinun = $ki;

        $sh = $crawler->filter('.fortune_daily_item.fortune_daily_item_shigotoun .fortune_daily_item_body')->text();
        $shigotoun = $sh;

        $ta = $crawler->filter('.fortune_daily_item.fortune_daily_item_taijinun .fortune_daily_item_body')->text();
        $taijinun = $ta;

        $da = $crawler->filter('.page_icon')->text();
        preg_match("/\((.+)\)/", trim($da), $m);
        $ex_m1 = explode("/", trim($m[1]));

        $insert = [
            'year' => date("Y"),
            'month' => sprintf("%02d", $ex_m1[0]),
            'day' => sprintf("%02d", $ex_m1[1]),
            'rank' => $daily_ranking,
            'love' => trim($renaiun),
            'money' => trim($kinun),
            'work' => trim($shigotoun),
            'man' => trim($taijinun)
        ];

        print_r($insert);

        $file = "/var/www/html/BrainLog/public/mySetting/leofortune.data";
        $fp = fopen($file, "a+");
        fwrite($fp, mb_convert_encoding(implode("|", $insert), "utf-8") . "\n");
        fclose($fp);
        */
    }
}
