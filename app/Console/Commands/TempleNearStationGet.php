<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class TempleNearStationGet extends Command
{

    protected $signature = 'TempleNearStationGet';

    protected $description = 'Command description';

    public function handle()
    {

        ////////////////////////////
        $sql = "select * from t_station where substring(lat, 1, 2) in (34, 35, 36) and substring(lng, 1, 3) in (138, 139, 140)";
        $result = DB::select($sql);
        $station = [];
        foreach ($result as $v) {
            $station[] = [
                "id" => $v->id,
                "lat" => $v->lat,
                "lng" => $v->lng,
                "station_name" => $v->station_name,
                "address" => $v->address,
            ];
        }
        ////////////////////////////

        $result = DB::table('t_temple_list')->orderBy('id')->get();

        foreach ($result as $v) {
//            if ($v->id > 1) {
//                break;
//            }

            $ary = [];
            $kilo = [];
            foreach ($station as $v2) {
                $dist = $this->getDistance(
                    $v->lat,
                    $v->lng,
                    $v2['lat'],
                    $v2['lng']
                );

                if ($dist[0] * 1000 <= 10000) {
                    $kilo[] = $dist[0] * 1000;
                    $ary[$dist[0] * 1000][] = $v2;
                }
            }

            sort($kilo);

            $ary2 = [];
            foreach ($kilo as $v2) {
                foreach ($ary[$v2] as $v3) {
                    $ary2[] = [
                        'id' => $v3['id'],
                        'lat' => $v3['lat'],
                        'lng' => $v3['lng'],
                        'station_name' => $v3['station_name'],
                        'address' => $v3['address'],
                        'dist' => $v2,
                    ];
                }
            }

            $ary3 = [];
            $name = [];
            foreach ($ary2 as $v2) {
                if (!in_array($v2['station_name'], $name)) {
                    $ary3[] = $v2;
                }

                $name[] = $v2['station_name'];
            }

            $ary4 = array_slice($ary3, 0, 3);

            $ids = [];
            foreach ($ary4 as $v2) {
                $ids[] = $v2['id'];
            }
            $update = [
                'near_station' => implode(", ", $ids),
            ];

            DB::table('t_temple_list')->where('id', $v->id)->update($update);

            print_r($v);
            print_r($ids);
            print_r($ary4);
            echo "\n\n";
            echo "\n\n";

        }
    }

    private function getDistance($originLat, $originLng, $destLat, $destLng)
    {
        $distanceKm = 6371 *
            acos(
                cos($originLat / 180 * M_PI) *
                cos(($destLng - $originLng) / 180 * M_PI) *
                cos($destLat / 180 * M_PI) +
                sin($originLat / 180 * M_PI) * sin($destLat / 180 * M_PI)
            );

        list($seisuu, $shousuu) = explode(".", $distanceKm);

        $sho = substr($shousuu, 0, 2);


        return ["{$seisuu}.{$sho}", "{$seisuu}.{$sho} Km"];
    }
}
