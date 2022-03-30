<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class YoutubeTitleGet extends Command
{

    protected $signature = 'YoutubeTitleGet';

    protected $description = 'Command description';

    public function handle()
    {

        if (date("H") == 4) {
            DB::table('t_movie')->update(['title' => '']);
        }

        $result = DB::table('t_movie')->whereNull('title')->get();

        foreach ($result as $v) {
            if (\is_null($v->movie)) {
                continue;
            }


// echo $v->movie;
// echo "\n";


            $contnt = \file_get_contents($v->movie);
            $ex_content = explode("\n", $content);
            $title = "";
            foreach ($ex_content as $v2) {
                if (\preg_match("/<title/", $v2)) {
                    $title = trim(\strip_tags($v2));
                    break;
                }
            }

            if (trim($title) != "") {

                echo $v->movie;
                echo "\n";
                echo $title;
                echo "\n";
                echo "\n";
                echo "\n";


                /*
                    $update = [];
                    $update['title'] = trim($title);

                    DB::table('t_movie')->where('id', '=', $v->id)->update($update);
                    */
            }


            /*
                        $context = stream_context_create(
                            [
                                "http"=>
                                [
                                    "ignore_errors"=>true
                                ]
                            ]
                        );

                        file_get_contents($v->movie, false, $context);

                        preg_match("/[0-9]{3}/", $http_response_header[0], $stcode);

                        if((int)$stcode[0] >= 100 && (int)$stcode[0] <= 199){
                        }else if((int)$stcode[0] >= 400 && (int)$stcode[0] <= 499){
                        }else if((int)$stcode[0] >= 500 && (int)$stcode[0] <= 599){
                        }else{
                            $contnt = \file_get_contents($v->movie);
                            $ex_content = explode("\n", $content);
                            $title = "";
                            foreach($ex_content as $v2){
                                if (\preg_match("/<title/", $v2)){
                                    $title = trim(\strip_tags($v2));
                                    break;
                                }
                            }

                            if (trim($title) != ""){

            echo $v->movie;
            echo "\n";
            echo $title;
            echo "\n";echo "\n";echo "\n";

                                $update = [];
                                $update['title'] = trim($title);

                                DB::table('t_movie')->where('id', '=', $v->id)->update($update);
                            }


                        }
                        */


        }
    }


}
