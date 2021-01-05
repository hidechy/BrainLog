<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TempleLatLngGet extends Command
{

    protected $signature = 'TempleLatLngGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $queryResult = DB::table('t_temple')->whereNull('lat')->get();
        foreach ($queryResult as $v) {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $v->address . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";
            $content = file_get_contents($url);
            $jsonStr = json_decode($content);

            $update = [];

            if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                $update['lat'] = $jsonStr->results[0]->geometry->location->lat;
            }
            if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                $update['lng'] = $jsonStr->results[0]->geometry->location->lng;
            }

            echo $v->temple;
            echo "\n";
            echo $v->address;
            echo "\n";
            print_r($update);
            echo "\n";
            echo "\n";
            echo "\n";

            DB::table('t_temple')->where('id', '=', $v->id)->update($update);
        }
    }

}
