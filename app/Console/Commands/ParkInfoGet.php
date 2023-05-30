<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class ParkInfoGet extends Command
{

    protected $signature = 'ParkInfoGet';

    protected $description = 'Command description';

    public function handle()
    {

        $str = "
青山公園
赤塚公園
秋留台公園
井の頭恩賜公園
上野恩賜公園
宇喜田公園
浮間公園
大泉中央公園
大神山公園
大島小松川公園
大戸緑地 
尾久の原公園
小山内裏公園
小山田緑地
葛西臨海公園
亀戸中央公園
砧公園
木場公園
小金井公園
駒沢オリンピック公園
小宮公園
桜ヶ丘公園
狭山公園
狭山・境緑道
猿江恩賜公園
汐入公園
潮風公園
篠崎公園
芝公園
石神井公園
城北中央公園
浅間山公園
善福寺川緑地
善福寺公園
祖師谷公園
台場公園
高井戸公園
滝山公園
玉川上水緑道
東京臨海広域防災公園
舎人公園
戸山公園
中川公園
中藤公園
長沼公園
野川公園
野山北・六道山公園
八国山緑地
東綾瀬公園
東白鬚公園
東伏見公園
東村山中央公園
東大和公園
東大和南公園
光が丘公園
日比谷公園
平山城址公園
府中の森公園
水元公園
武蔵国分寺公園
武蔵野公園
武蔵野中央公園
武蔵野の森公園
明治公園
夢の島公園
横網町公園
代々木公園
陵南公園
林試の森公園
蘆花恒春園
六仙公園
和田堀公園

";

        $ex_str = explode("\n", $str);

        foreach ($ex_str as $k => $v) {

            if (trim($v) == "") {
                continue;
            }

//            if ($k > 3) {
//                break;
//            }

            try {

                $url9 = "https://maps.googleapis.com/maps/api/geocode/json?address=" . trim($v) . "&components=country:JP&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                $content9 = file_get_contents($url9);
                $jsonStr = json_decode($content9);

                $_lat = '';
                $_lng = '';

                if (isset($jsonStr->results[0]->geometry->location->lat) and trim($jsonStr->results[0]->geometry->location->lat) != "") {
                    $_lat = $jsonStr->results[0]->geometry->location->lat;
                }

                if (isset($jsonStr->results[0]->geometry->location->lng) and trim($jsonStr->results[0]->geometry->location->lng) != "") {
                    $_lng = $jsonStr->results[0]->geometry->location->lng;
                }

                if (trim($_lat) == '' || trim($_lng) == '') {
                    continue;
                }


                $_addr = $jsonStr->results[0]->formatted_address;

                $insert = [
                    "name" => $v,
                    "address" => $_addr,
                    "latitude" => $_lat,
                    "longitude" => $_lng
                ];

                print_r($insert);


                DB::table('t_metropolitan_park')->insert($insert);


            } catch (Exception $e) {
            }

        }
    }
}
