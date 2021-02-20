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

2月9日	火	732	西船橋	246	食費
2月9日	火	745	移動中	283	交通費
2月9日	火	2000	移動中	283	交通費
2月9日	火	2055	西船橋	1050	食費
					
2月10日	水		自宅	0	
					
2月11日	木	1820	下総中山	900	食費
2月11日	木	1846	下総中山	810	雑費
2月11日	木	1846	下総中山	779	食費
2月11日	木	1854	下総中山	1006	食費
					
2月12日	金		自宅	0	
					
2月13日	土		自宅	0	
					
2月14日	日	830	移動中	555	交通費
2月14日	日	1043	横浜	73	食費
2月14日	日	1530	横浜	1000	食費
2月14日	日	1540	移動中	555	交通費
2月14日	日	1652	西船橋	1050	食費

















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
