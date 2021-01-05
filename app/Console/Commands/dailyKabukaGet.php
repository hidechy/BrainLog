<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class dailyKabukaGet extends Command
{

    protected $signature = 'dailyKabukaGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $is_move = true;
        if (date("H") < 9) {
            $is_move = false;
        }
        if (date("H") > 15) {
            $is_move = false;
        }
        if (date("w", strtotime(date("Ymd"))) == 0) {
            $is_move = false;
        }
        if (date("w", strtotime(date("Ymd"))) == 6) {
            $is_move = false;
        }

        if (date("Y-m-d") < "2021-01-04") {
            $is_move = false;
        }

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

        if (in_array(date("Y-m-d"), $holiday)) {
            $is_move = false;
        }
        //------------------//

        if ($is_move == false) {
            exit();
        }


        $url = "https://info.finance.yahoo.co.jp/ranking/?kd=1&tm=d&vl=a&mk=1&p=1";
        $content = file_get_contents($url);
        $ex_content = explode("\n", $content);

        $a = 0;
        $b = 0;
        foreach ($ex_content as $k => $v) {
            if ($v == "<!-- rankingTable -->") {
                $a = $k;
            }
            if ($v == "<!-- /rankingTable -->") {
                $b = $k;
            }

            if (($a > 0) && ($b > 0)) {
                break;
            }
        }

        $str = "";
        for ($i = $a; $i < $b; $i++) {
            $str .= trim($ex_content[$i]);
        }

        $ex_str = explode("|", strtr($str, ['</tr>' => '</tr>|']));

        foreach ($ex_str as $v) {
            if (preg_match("/rankingTabledata/", $v)) {
                $insert = [];
                $insert['created_at'] = date("Y-m-d H:i:s");

                $ex_v = explode("|", strtr($v, ['</td>' => '</td>|']));
                foreach ($ex_v as $k2 => $v2) {
                    switch ($k2) {
                        case 0:
                            $insert['rank'] = trim(strip_tags($v2));
                            break;
                        case 1:
                            $insert['code'] = trim(strip_tags($v2));
                            break;
                        case 2:
                            $insert['market'] = trim(strip_tags($v2));
                            break;
                        case 3:
                            $insert['company'] = trim(strip_tags($v2));
                            break;
                        case 4:
                            $insert['last_update'] = trim(strip_tags($v2));
                            break;
                        case 5:
                            $insert['torihikichi'] = trim(strtr(strip_tags($v2), [',' => '']));

                            $insert['grade'] = "E";
                            if ($insert['torihikichi'] > 500) {
                                $insert['grade'] = "D";
                            }
                            if ($insert['torihikichi'] > 1000) {
                                $insert['grade'] = "C";
                            }
                            if ($insert['torihikichi'] > 1500) {
                                $insert['grade'] = "B";
                            }
                            if ($insert['torihikichi'] > 2000) {
                                $insert['grade'] = "A";
                            }
                            break;
                        case 6:
                            preg_match("/([.0-9]+)/", trim(strip_tags($v2)), $m);
                            $insert['percentage'] = $m[1];
                            break;
                        case 7:
                            $insert['zenjitsuhi'] = trim(strip_tags($v2));
                            break;
                        case 8:
                            $insert['dekidaka'] = trim(strip_tags($v2));
                            break;
                    }
                }

                $insert['point'] = (51 - $insert['rank']);

                $insert['industry'] = "";

                //----------------// [t]
                $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".t";
                $content2 = file_get_contents($url2);
                $ex_content2 = explode("\n", $content2);

                foreach ($ex_content2 as $v2) {
                    if (preg_match("/industry/", $v2)) {
                        $insert['industry'] = trim(strip_tags($v2));
                        break;
                    }
                }

                //----------------// [n]
                if (trim($insert['industry']) == "") {
                    $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".n";
                    $content2 = file_get_contents($url2);
                    $ex_content2 = explode("\n", $content2);

                    foreach ($ex_content2 as $v2) {
                        if (preg_match("/industry/", $v2)) {
                            $insert['industry'] = trim(strip_tags($v2));
                            break;
                        }
                    }
                }

                //----------------// [f]
                if (trim($insert['industry']) == "") {
                    $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".f";
                    $content2 = file_get_contents($url2);
                    $ex_content2 = explode("\n", $content2);

                    foreach ($ex_content2 as $v2) {
                        if (preg_match("/industry/", $v2)) {
                            $insert['industry'] = trim(strip_tags($v2));
                            break;
                        }
                    }
                }

                //----------------// [t]//category
                if (trim($insert['industry']) == "") {
                    $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".t";
                    $content2 = file_get_contents($url2);
                    $ex_content2 = explode("\n", $content2);

                    foreach ($ex_content2 as $v2) {
                        if (preg_match("/category/", $v2)) {
                            $insert['industry'] = trim(strip_tags($v2));
                            break;
                        }
                    }
                }

                //----------------// [n]//category
                if (trim($insert['industry']) == "") {
                    $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".n";
                    $content2 = file_get_contents($url2);
                    $ex_content2 = explode("\n", $content2);

                    foreach ($ex_content2 as $v2) {
                        if (preg_match("/category/", $v2)) {
                            $insert['industry'] = trim(strip_tags($v2));
                            break;
                        }
                    }
                }

                //----------------// [f]//category
                if (trim($insert['industry']) == "") {
                    $url2 = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $insert['code'] . ".f";
                    $content2 = file_get_contents($url2);
                    $ex_content2 = explode("\n", $content2);

                    foreach ($ex_content2 as $v2) {
                        if (preg_match("/category/", $v2)) {
                            $insert['industry'] = trim(strip_tags($v2));
                            break;
                        }
                    }
                }

                DB::table('t_stock')->insert($insert);

//print_r($insert);
//echo "\n\n\n";

            }
        }
    }
}
