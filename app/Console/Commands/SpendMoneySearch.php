<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class SpendMoneySearch extends Command
{

    protected $signature = 'SpendMoneySearch';

    protected $description = 'Command description';

    public function handle()
    {

        //$table = 't_article' . $request->year;
        $table = 't_article2021';
        $year = 2021;

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
            ->where('article', 'like', '%ユーシーカード内訳%')->get();

        $ary = [];
        foreach ($result as $v2) {
            $ex_result = explode("\n", $v2->article);
            foreach ($ex_result as $v) {
                $val = trim(strip_tags($v));
                if (preg_match("/円.+円/", trim($val))) {

                    $ex_val = explode("\t", $val);
                    $date = strtr(trim($ex_val[1]), ['/' => '-']);
                    $price = strtr(trim($ex_val[6]), [',' => '', '円' => '']);




                    print_r($val);
                    echo "\n";
                    print_r($ex_val);
                    echo "\n\n";










                    if (trim($price) == "") {
                        continue;
                    }

                    $im = trim($ex_val[3]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
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

                    $im = trim($ex_val[1]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

        //------------------------------------------//
        $result = DB::table($table)
            ->where('year', $year)
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

                    $im = trim($ex_val[1]);
                    $im = $this->makeItemName($im);
                    $ary[$im][$v2->month][] = $price;

                }
            }
        }
        //------------------------------------------//

//        print_r($ary);


        $ary2 = [];
        foreach ($ary as $im => $v) {
            for ($i = 1; $i <= 12; $i++) {
                $month = sprintf("%02d", $i);
                $sum = (isset($ary[$im][$month])) ? array_sum($ary[$im][$month]) : 0;
                $ary2[$im][$month] = $sum;
            }
        }

        print_r($ary2);


        /*
        $midashi = array_keys($ary);
        sort($midashi);
        print_r($midashi);
*/

    }


    /**
     *
     */
    private function makeItemName($im)
    {
        $im = mb_convert_kana($im, "aK");

        //-----------------//
        if (preg_match("/AMAZON.CO.JP/", $im)) {
            $im = "AMAZON";
        }

        if (preg_match("/アマソ゛ンフ゜ライムカイヒ/", $im)) {
            $im = "AMAZON PRIME会費";
        }

        if (preg_match("/アマソ゛ン/", $im)) {
            $im = "AMAZON";
        }

        if (preg_match("/AMAZON DOWNLOADS/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }

        if (preg_match("/AmazonDownloads/", $im)) {
            $im = "AMAZON DOWNLOADS";
        }
        //-----------------//

        if (preg_match("/YOUTUBE/", $im)) {
            $im = "YOUTUBE";
        }

        if (preg_match("/UDEMY/", $im)) {
            $im = "UDEMY";
        }

        if (preg_match("/VULTR/", $im)) {
            $im = "VULTR";
        }

        if (preg_match("/MICROSOFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/MSFT/", $im)) {
            $im = "MICROSOFT";
        }

        if (preg_match("/NTTコミュニケーションズ/", $im)) {
            $im = "NTT COMMUNICATIONS";
        }

        if (preg_match("/PLAYSTATION/", $im)) {
            $im = "PLAYSTATION";
        }

        if (preg_match("/投信積立/", $im)) {
            $im = "投信積立";
        }

        if (preg_match("/楽天モバイル/", $im)) {
            $im = "楽天モバイル";
        }

        if (preg_match("/甘党・辛党丸田屋/", $im)) {
            $im = "甘党・辛党丸田屋";
        }

        if (preg_match("/西友/", $im)) {
            $im = "西友ネットスーパー";
        }

        if (preg_match("/マイクロソフト/", $im)) {
            $im = "MICROSOFT";
        }

        return $im;
    }


}
