<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class LeoFortuneGet extends Command
{

    protected $signature = 'LeoFortuneGet';

    protected $description = 'LeoFortuneGet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $url = "https://www.goodfortune.jp/fortune/tomorrow/leo";
        $crawler = \Goutte::request('GET', $url);

        $dr = $crawler->filter('.fortune_daily_rank')->text();
        $daily_ranking = strtr($dr, ['位' => '']);

        $re = $crawler->filter('.article_body.article_body_renaiun .mainNewsRight.mainNewsRight-detail .article_text')->text();
        $renaiun = substr($re, strpos($re, "。") + 3);

        $ki = $crawler->filter('.article_body.article_body_kinun .mainNewsRight.mainNewsRight-detail .article_text')->text();
        $kinun = substr($ki, strpos($ki, "。") + 3);

        $sh = $crawler->filter('.article_body.article_body_shigotoun .mainNewsRight.mainNewsRight-detail .article_text')->text();
        $shigotoun = substr($sh, strpos($sh, "。") + 3);

        $ta = $crawler->filter('.article_body.article_body_taijinun .mainNewsRight.mainNewsRight-detail .article_text')->text();
        $taijinun = substr($ta, strpos($ta, "。") + 3);

        $da = $crawler->filter('.contents_title.contents_title_type2.contents_title_center > h3')->text();
        preg_match("/獅子座（しし座）(.+)月(.+)日の運勢/", trim($da), $m);

        $insert = [
            'year' => date("Y"),
            'month' => sprintf("%02d", $m[1]),
            'day' => sprintf("%02d", $m[2]),
            'rank' => $daily_ranking,
            'love' => trim($renaiun),
            'money' => trim($kinun),
            'work' => trim($shigotoun),
            'man' => trim($taijinun)
        ];

        print_r($insert);


        $file = "/var/www/html/BrainLog/public/mySetting/leofortune.data";
        $fp = fopen($file, "a+");
        fwrite($fp, mb_convert_encoding(implode("|", $insert), "utf-8")."\n");
        fclose($fp);
    }
}
