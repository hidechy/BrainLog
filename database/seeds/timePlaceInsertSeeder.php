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

1月14日	木	1918	下総中山	1155	食費
1月14日	木	1944	下総中山	858	食費
					
1月15日	金		自宅	0	
					
1月16日	土		自宅	0	
					
1月17日	日	830	移動中	555	交通費
1月17日	日	1043	横浜	73	食費
1月17日	日	1607	横浜	800	食費
1月17日	日	1615	移動中	555	交通費
1月17日	日	1745	西船橋	435	食費
1月17日	日	1745	西船橋	208	雑費
1月17日	日	1755	西船橋	990	食費






";

        $ex_str = explode("\n", $str);

        $insert = [];
        foreach ($ex_str as $v) {
            if (trim($v) == ""){continue;}

            $ex_v = explode("\t", trim($v));

            preg_match("/([0-9]+)月([0-9]+)日/", trim($ex_v[0]), $m);

            $tmp_time = sprintf("%04d", trim($ex_v[2]));

            $insert[] = [
                'year' => 2021,
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
