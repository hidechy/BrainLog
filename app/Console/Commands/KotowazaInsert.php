<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class KotowazaInsert extends Command
{

    protected $signature = 'KotowazaInsert';

    protected $description = 'Command description';

    public function handle()
    {

        $file = "/var/www/html/BrainLog/public/mySetting/kotowaza.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }
            $ex_v = explode("|", trim($v));

            $ex_v_0 = explode("（", trim($ex_v[0]));
            $word = trim($ex_v_0[0]);

            $head = mb_substr(trim($ex_v_0[1]), 0, 1);
            $ary = [
                "望蜀", "坊主憎けりゃ袈裟まで憎い", "墨守", "仏作って魂入れず", "仏の顔も三度"
            ];
            if (in_array($word, $ary)) {
                $head = "ほ";
            }

            preg_match("/（(.+)）/", trim($ex_v[0]), $m);

            $insert = [];
            $insert['head'] = trim($head);
            $insert['word'] = trim($word);
            $insert['yomi'] = trim($m[1]);
            $insert['explanation'] = trim($ex_v[1]);
            $insert['flag'] = 0;

            print_r($insert);

            DB::table('t_kotowaza')->insert($insert);
        }
    }
}
