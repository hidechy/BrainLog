<?php

namespace App\Console\Commands;

use DB;
use Exception;
use Illuminate\Console\Command;

class YoutubeInfoGet extends Command
{

    protected $signature = 'YoutubeInfoGet';

    protected $description = 'Command description';

    public function handle()
    {

        $sql = " select * from t_youtube_data where playtime = '' or playtime is null; ";
        $result = DB::select($sql);

        foreach ($result as $k => $v) {

            print_r($v);

            try {

                //---------------------------------------//
//                DB::table('t_youtube_data')->where('del', '=', '1')->delete();
                //---------------------------------------//


                $url = "https://www.googleapis.com/youtube/v3/videos?id={$v->youtube_id}&part=snippet,contentDetails&key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

                $content = file_get_contents($url);
                $jsonStr = json_decode($content);

                $v3_pubDate = substr($jsonStr->items[0]->snippet->publishedAt, 0, 10);
                $v3_channelId = $jsonStr->items[0]->snippet->channelId;
                $v3_channelTitle = $jsonStr->items[0]->snippet->channelTitle;
                $v3_playtime = $jsonStr->items[0]->contentDetails->duration;



                $v3_title = $jsonStr->items[0]->snippet->title;



                echo "////////////////////////////\n";
                print_r([
                    $v3_pubDate,
                    $v3_channelId,
                    $v3_channelTitle,
                    $v3_playtime
                ]);
                echo "////////////////////////////\n";

                echo "\n\n\n";

                $update = [];
                $update['pubdate'] = $v3_pubDate;
                $update['channel_id'] = $v3_channelId;
                $update['channel_title'] = $v3_channelTitle;
                $update['playtime'] = $v3_playtime;


                if (trim($v->title) == ""){
                    $update['title'] = $v3_title;
                }






                DB::table('t_youtube_data')->where('youtube_id', '=', $v->youtube_id)->update($update);

            } catch (Exception $e) {

                print_r($e->getMessage());

            }
        }
    }
}
