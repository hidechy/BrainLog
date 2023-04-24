<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class LocationDataGet extends Command
{

    protected $signature = 'LocationDataGet';

    protected $description = 'Command description';

    public function handle()
    {

        $file = public_path() . "/mySetting/location/Records.json";
        $content = file_get_contents($file);

        $json_decode = json_decode($content);


//        print_r(count($json_decode->locations));
//        exit();


        foreach ($json_decode->locations as $v) {

            preg_match("/(.+)T(.+)/", $v->timestamp, $m);
            list($year, $month, $day) = explode("-", $m[1]);

            if ($year < date("Y")) {
                echo $v->timestamp;
                echo "\n";
                echo "\n";

                continue;
            }

            list($hour, $minute, $x) = explode(":", $m[2]);
            $_mktime = mktime($hour, $minute, 0, $month, $day, $year);
            $date = date("Y-m-d-H-i", $_mktime + (60 * 60 * 9));
            list($xYear, $xMonth, $xDay, $xHour, $xMinute) = explode("-", $date);

            $lat = (isset($v->latitudeE7)) ? $v->latitudeE7 / 10000000 : '';
            $lng = (isset($v->longitudeE7)) ? $v->longitudeE7 / 10000000 : '';

            $result = DB::table('t_time_location')
                ->where('year', $xYear)
                ->where('month', $xMonth)
                ->where('day', $xDay)
                ->where('time', "{$xHour}:{$xMinute}")
                ->first();

            if (is_null($result)) {

                $insert = [
                    "year" => $xYear,
                    "month" => $xMonth,
                    "day" => $xDay,
                    "time" => "{$xHour}:{$xMinute}",
                    "latitude" => $lat,
                    "longitude" => $lng,
                ];

                print_r($insert);

                DB::table('t_time_location')->insert($insert);
            }else{
                echo $v->timestamp;
                echo "\n";
                echo "\n";
            }
        }

    }
}
