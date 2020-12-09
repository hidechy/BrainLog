<?php

use Illuminate\Database\Seeder;

class timePlaceInsertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

$str = "
12月5日	土		自宅	0	
					
12月6日	日	1813	下総中山	1270	食費
					
12月7日	月	820	移動中	283	交通費
12月7日	月	1250	六本木一丁目	583	食費
12月7日	月	1930	移動中	283	交通費
12月7日	月	2058	西船橋	467	食費

";

        $ex_str = explode("\n", $str);

        $insert = [];
        foreach ($ex_str as $v) {
            if (trim($v) == ""){continue;}

            $ex_v = explode("\t", trim($v));

            preg_match("/([0-9]+)月([0-9]+)日/", trim($ex_v[0]), $m);

            $tmp_time = sprintf("%04d", trim($ex_v[2]));

            $insert[] = [
                'year' => 2020,
                'month' => sprintf("%02d", $m[1]),
                'day' => sprintf("%02d", $m[2]),
                'time' => substr($tmp_time, 0, 2) . ":" . substr($tmp_time, 2, 2),
                'place' => trim($ex_v[3]),
                'price' => trim($ex_v[4])
            ];
        }
        
        DB::table('t_timeplace')->insert($insert);

    }
}
