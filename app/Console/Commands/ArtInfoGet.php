<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class ArtInfoGet extends Command
{

    protected $signature = 'ArtInfoGet';

    protected $description = 'Command description';

    public function handle()
    {

        $url = 'https://artscape.jp/mdb/mdb.php';

        $content = file_get_contents($url);
        $ex_content = explode("\n", $content);

        $a = [];
        foreach ($ex_content as $v) {
            if (preg_match("/fncResult\((.+)\)/", trim($v), $m)) {

                if (trim($m[1]) != "") {
                    $ex_m1 = explode(",", trim($m[1]));
                    $val = trim(strtr($ex_m1[1], ["'" => ""]));

                    if ((int)$val) {
                        $a[] = $val;
                    }
                }
            }
        }

        //--------------------------------------//

        foreach ($a as $k => $v) {

            $url = 'https://artscape.jp/mdb/mdb_result.php?area=' . $v;

            $content = file_get_contents($url);
            $ex_content = explode("\n", $content);

            $b = [];
            $c = [];
            foreach ($ex_content as $k2 => $v2) {
                if (preg_match("/exhiInfo/", trim($v2))) {
                    $b[] = $k2;
                }
                if (preg_match("/contentFooter/", trim($v2))) {
                    $c[] = $k2;
                }
            }

            $b[] = $c[0];

            for ($i = 0; $i < count($b) - 2; $i++) {

                $str = "";
                for ($j = $b[$i]; $j < $b[$i + 1]; $j++) {
                    $st = strtr(trim($ex_content[$j]),
                        [
                            '<p class="mdbAddress">' => '<p class="mdbAddress">|',
                            '<ul class="mdbJunle">' => '<ul class="mdbJunle">|'
                        ]);

                    $st2 = trim(strip_tags($st));
                    $st3 = explode("[", $st2);
                    $st4 = explode("［", trim($st3[0]));
                    $st5 = $st4[0];
                    $st6 = strtr($st5, ['&nbsp;' => '']);
                    $str .= trim($st6);
                }

                if (trim($str) != "") {
                    $ex_str = explode("|", trim($str));

                    $_lat = "";
                    $_lng = "";

                    try {

                        $ex_val2 = explode(" ", trim($ex_str[2]));
                        $addr = (count($ex_val2) > 1) ? $ex_val2[0] : trim($ex_str[2]);

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
                        "name" => trim($ex_str[0]),
                        "genre" => trim($ex_str[1]),
                        "address" => trim($ex_str[2]),
                        "latitude" => $_lat,
                        "longitude" => $_lng,
                    ];

                    print_r($insert);

                    DB::table('t_art_facilities')->insert($insert);

                }
            }
        }
        //--------------------------------------//


    }
}
