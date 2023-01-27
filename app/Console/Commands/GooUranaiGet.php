<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GooUranaiGet extends Command
{

    protected $signature = 'GooUranaiGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $url = "https://www.goo.ne.jp/uranai/leo.html";
        $content = file_get_contents($url);
        $ex_content = explode("\n", $content);


        $a = 0;
        $b = 0;
        foreach ($ex_content as $k => $v) {
            if (preg_match("/<div id=\"NR-main-in\">/", trim($v))) {
                $a = $k;
            }

            if (preg_match("/他の星座 今日の運勢/", trim($v))) {
                $b = $k;

                break;
            }
        }

        $str = "";
        for ($i = $a; $i < $b; $i++) {
            $str .= trim($ex_content[$i]);
        }

        $ex_str = explode("|", strtr($str, ["><" => ">|<"]));

        $rank_line = 0;
        $all_line = 0;
        $section_data = [];
        $point_data = [];
        foreach ($ex_str as $k => $v) {
            if (preg_match("/<div class=\"daily-rank\">/", trim($v))) {
                $rank_line = $k;
            }

            if (preg_match("/<p class=\"daily-lead\">/", trim($v))) {
                $all_line = $k;
            }

            if (preg_match("/<p class=\"text-a\">/", trim($v))) {
                $section_data[] = $k;
            }

            if (preg_match("/<img.+public\/img\/uranai/", trim($v))) {
                $point_data[] = $k;
            }
        }

        $ra = trim(strip_tags($ex_str[$rank_line]));
        preg_match("/第(.+)位/", $ra, $m);
        $rank = trim($m[1]);

        $uranai = [];
        $uranai[0] = trim(strtr(strip_tags($ex_str[$all_line]), ["ラッキーカラー" => "//ラッキーカラー", "ラッキーアイテム" => "//ラッキーアイテム"]));
        foreach ($section_data as $v) {
            $uranai[] = trim(strip_tags($ex_str[$v]));
        }

        $point = [];
        foreach ($point_data as $v) {
            $po = trim($ex_str[$v]);
            $ex_po = explode("|", strtr($po, ["alt" => "|alt"]));
            foreach ($ex_po as $v2) {
                if (preg_match("/alt/", trim($v2))) {
                    if (!preg_match("/png/", trim($v2))) {
                        $poi = trim(strtr(trim($v2), ["alt=\"" => "", "\">" => ""]));
                        if (trim($poi) != "") {
                            $point[] = trim($poi);
                        }
                    }
                }
            }
        }

        $answer = [date("Y-m-d"), $rank];
        for ($i = 0; $i < count($uranai); $i++) {
            $answer[] = "{$uranai[$i]};{$point[$i]}";
        }
        print_r($answer);

        //------------------------------------
        $data = [];

        $file = "/var/www/html/BrainLog/public/mySetting/uranai2.data";
        $aaa = file_get_contents($file);
        $ex_aaa = explode("\n", $aaa);
        foreach ($ex_aaa as $v) {
            if (trim($v) == "") {
                continue;
            }
            $ex_v = explode("|", trim($v));
            $data[trim($ex_v[0])] = trim($v);
        }

        $data[date("Y-m-d")] = implode("|", $answer);
        ksort($data);

        file_put_contents($file, implode("\n", $data));
        chmod($file, 0777);
        //------------------------------------

    }
}
