<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TempleInfoGet extends Command
{

    protected $signature = 'TempleInfoGet';

    protected $description = 'Command description';

    public function handle()
    {

        //-----------------------// (1)
        $url = "http://www.tokyo-jinjacho.or.jp/jinja/map/";
        $content = file_get_contents($url);
        $ex_content = explode("\n", $content);

        $a = [];
        foreach ($ex_content as $v) {
            if (preg_match("/s_menu_mi/", trim($v))) {
                $a[] = trim($v);
            }
        }

        $b = [];
        foreach ($a as $v) {
            preg_match("/href=\"(.+)\">/", $v, $m);
            $b[] = trim($m[1]);
        }

//        print_r($b);
        //-----------------------// (1)


        foreach ($b as $k => $v) {


//            if ($k > 0) {
//                break;
//            }


            $url = "http://www.tokyo-jinjacho.or.jp{$v}";
            $content = file_get_contents($url);
            $ex_content = explode("\n", $content);

            $aa = [];
            $bb = 0;
            foreach ($ex_content as $k2 => $v2) {
                if (preg_match("/s_top_box/", trim($v2))) {
                    $aa[] = $k2;
                }
                if (preg_match("/<!-- \/ メインwrap -->/", trim($v2))) {
                    $bb = $k2;
                }
            }
            $aa[] = $bb;


            $cc = [];
            for ($i = 0; $i < count($aa) - 1; $i++) {
                $cc[$i] = [];
                for ($j = $aa[$i]; $j < $aa[$i + 1]; $j++) {
                    $ex_val = explode("|", strtr($ex_content[$j], ['<' => '|<']));

                    foreach ($ex_val as $v2) {
                        if (trim($v2) != "") {
                            $cc[$i][] = trim($v2);
                        }
                    }
                }
            }
//            print_r($cc);


            foreach ($cc as $v2) {
                $ee = 0;
                $ff = 0;
                $gg = 0;
                $hh = 0;
                foreach ($v2 as $k3 => $v3) {
                    if (preg_match("/<a.+tokyo-jinjacho/", trim($v3))) {
                        $ee = $k3;
                    }
                    if (preg_match("/class=\"s_top_tl\"/", trim($v3))) {
                        $ff = $k3;
                    }
                    if (preg_match("/class=\"s_top_tl02\"/", trim($v3))) {
                        $gg = $k3;
                    }
                    if (preg_match("/s_top_mi/", trim($v3))) {
                        $hh = $k3;
                    }
                }

                $ex_url = explode('"', trim($v2[$ee]));
                $ex_url_1 = explode("/", trim($ex_url[1]));

                $addr = strip_tags($v2[$hh]);

                $_lat = "";
                $_lng = "";

                try {

                    $url9 = "https://maps.googleapis.com/maps/api/geocode/json?address=" . trim($addr) . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                    $content9 = file_get_contents($url9);
                    $jsonStr = json_decode($content9);

                    if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                        $_lat = $jsonStr->results[0]->geometry->location->lat;
                    }

                    if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                        $_lng = $jsonStr->results[0]->geometry->location->lng;
                    }

                } catch (Exception $e) {
                }

                $insert = [
                    "city" => trim(strtr($v, ['/' => ''])),
                    "jinjachou_id" => $ex_url_1[count($ex_url_1) - 1],
                    "url" => trim($ex_url[1]),
                    "name" => strip_tags($v2[$ff]) . strip_tags($v2[$gg]),
                    "address" => $addr,
                    "lat" => $_lat,
                    "lng" => $_lng,
                ];

                print_r($insert);

                DB::table('t_temple_list')->insert($insert);
            }
        }
    }
}
