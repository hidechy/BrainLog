<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class StationDataInsert extends Command
{

    protected $signature = 'StationDataInsert';

    protected $description = 'Command description';

    public function handle()
    {


        /*
        $file = "/var/www/html/BrainLog/storage/station20210312free.csv";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $k => $v) {
            if (trim($v) == "") {
                continue;
            }

            $ex_v = explode(",", trim($v));

            $insert = [];
            $insert['train_number'] = trim($ex_v[5]);
            $insert['station_name'] = trim($ex_v[2]);
            $insert['address'] = trim($ex_v[8]);
            $insert['lat'] = trim($ex_v[10]);
            $insert['lng'] = trim($ex_v[9]);

            print_r($insert);

            DB::table('t_station')->insert($insert);
        }
        */

        /*
            $file = "/var/www/html/BrainLog/storage/line20210312free.csv";
            $content = file_get_contents($file);
            $ex_content = explode("\n", $content);

            foreach ($ex_content as $k => $v) {
                if (trim($v) == "") {
                    continue;
                }

                if ($k == 0) {
                    continue;
                }

                $ex_v = explode(",", trim($v));

                $insert = [];
                $insert['train_number'] = trim($ex_v[0]);
                $insert['company_id'] = trim($ex_v[1]) * 1;
                $insert['train_name'] = trim($ex_v[2]);
                $insert['order_number'] = ($k * 10);

                print_r($insert);

                DB::table('t_train')->insert($insert);
            }
        */


        $file = "/var/www/html/BrainLog/storage/company20200619.csv";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $k => $v) {
            if (trim($v) == "") {
                continue;
            }

            if ($k == 0) {
                continue;
            }

            $ex_v = explode(",", trim($v));

            $insert = [];
            $insert['company_id'] = trim($ex_v[0]);
            $insert['company_name'] = trim($ex_v[2]);

            print_r($insert);

            DB::table('t_train_company')->insert($insert);
        }


        /*
select
brain.t_station.*
from
brain.t_station
inner join brain.t_train on brain.t_train.train_number = brain.t_station.train_number
where
brain.t_train.train_number = '11101';

        */


    }
}
