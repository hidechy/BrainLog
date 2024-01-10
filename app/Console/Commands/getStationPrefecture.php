<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class getStationPrefecture extends Command
{

    protected $signature = 'getStationPrefecture';

    protected $description = 'Command description';

    public function handle()
    {

        /////////////////////////////////////////////
        $sql = " select * from t_station where prefecture is not null ";
        $result = DB::select($sql);
        $_pref = [];
        foreach ($result as $k => $v) {
            $_pref[$v->prefecture] = "";
        }
        $pref = array_keys($_pref);
        /////////////////////////////////////////////


        $sql = " select * from t_station where prefecture is null ";
        $result = DB::select($sql);

        foreach ($result as $k => $v) {
//            if ($k > 0) {
//                break;
//            }

            $prefecture = "";

            try {

                $url9 = "https://maps.google.com/maps/api/geocode/json?latlng={$v->lat},{$v->lng}&sensor=false&language=ja&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                $content9 = file_get_contents($url9);
                $jsonStr = json_decode($content9);

                $address_components = $jsonStr->results[0]->address_components;

                foreach ($address_components as $v2) {
                    if (in_array($v2->long_name, $pref)) {
//                        print_r($v2->long_name);
//                        echo "\n";

                        $prefecture = $v2->long_name;
                    }
                }
            } catch (Exception $e) {
            }

            if ($prefecture == '') {
                continue;
            }


            print_r($v);
            echo "\n";
            echo $prefecture;
            echo "\n";
            echo "\n";
            echo "\n";
            

            $update = [];
            $update['prefecture'] = $prefecture;

            DB::table('t_station')->where('id', $v->id)->update($update);
        }
    }
}
