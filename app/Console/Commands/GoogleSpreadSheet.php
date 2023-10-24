<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

use Google_Client;
use Google_Http_Batch;
use Google_Service_Exception;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;
use Google_Service_Sheets;

class GoogleSpreadSheet extends Command
{

    protected $signature = 'GoogleSpreadSheet';

    protected $description = 'Command description';

    public function handle()
    {

        require_once '/var/www/html/BrainLog/vendor/autoload.php';

        $key = "/var/www/html/BrainLog/public/mySetting/brainlog-e7a9793af15b.json";

        //-------------------------//
        $client = new Google_Client();

        $client->setScopes([
            \Google_Service_Sheets::SPREADSHEETS,
            \Google_Service_Sheets::DRIVE,]);

        $client->setAuthConfig($key);
        //-------------------------//

        //-------------------------//
        $sheet = new Google_Service_Sheets($client);

        $sheet_id = '1Jpe_qH0uY9fVBoDno2ynfBkxb3NbzqsuHjE07fijd58';

        $range = 'シート1!A1:C1000';

        $response = $sheet->spreadsheets_values->get($sheet_id, $range);

        $values = $response->getValues();

//        print_r($values);
        //-------------------------//

        $m = null;
        foreach ($values as $k => $v) {

//print_r($v);

            preg_match("/youtu\.be\/(.+)/", trim($v[2]), $m);

//print_r($m);

            if (!isset($m[1])) {
                continue;
            }

            $youtube_id = trim($m[1]);

            $exYoutubeId = explode("?si=", trim($youtube_id));
            $YId = (count($exYoutubeId) == 1) ? $youtube_id : $exYoutubeId[0];

            $m = null;

            $result = DB::table('t_youtube_data')
                ->where('youtube_id', '=', $YId)
                ->first();

            if (isset($result->id)) {
                continue;
            }

            preg_match("/\"(.+)\"/", trim($v[1]), $m);

//print_r($m);

            if (!isset($m[1])) {
                continue;
            }

            $title = trim($m[1]);
//            $title = json_decode($title);

            $m = null;

            $insert = [];
            $insert['youtube_id'] = $youtube_id;
            $insert['getdate'] = trim(strtr($v[0], ['/' => '']));
            $insert['title'] = trim($title);
            $insert['url'] = trim($v[2]);
            $insert['del'] = 0;
            $insert['special'] = 0;

            print_r($insert);
            echo "\n\n";

            try {
                DB::table('t_youtube_data')->insert($insert);
            } catch (\Exception $e) {
            }
        }


        //----------------------------------//
        $file = public_path() . "/mySetting/self_youtube.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        $ary2 = [];
        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            $ex_v = explode("?", trim($v));
            $ex_v_1 = explode("&", trim($ex_v[1]));

            $youtube_id = "";
            foreach ($ex_v_1 as $v2) {
                if (preg_match("/v=(.+)/", trim($v2), $m)) {
                    $youtube_id = $m[1];
                    break;
                }
            }

            if ($youtube_id == "") {
                continue;
            }

            $result = DB::table('t_youtube_data')
                ->where('youtube_id', '=', $youtube_id)
                ->first();

            if (isset($result->id)) {
                continue;
            }

            $insert = [];
            $insert['youtube_id'] = $youtube_id;
            $insert['getdate'] = date("Ymd");
            $insert['title'] = "";
            $insert['url'] = "https://youtu.be/{$youtube_id}";
            $insert['del'] = 0;
            $insert['special'] = 0;

            DB::table('t_youtube_data')->insert($insert);

        }

        file_put_contents($file, "");
        //----------------------------------//

    }
}
