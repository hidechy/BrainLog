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







11月19日	木	852	下総中山	252	食費
11月19日	木	1349	浅草橋	900	食費
					
11月20日	金	856	下総中山	168	食費
11月20日	金	1343	浅草橋	693	食費
11月20日	金	2020	下総中山	820	食費
					
11月21日	土	1200	移動中	396	交通費
11月21日	土	1427	上石神井	108	食費
11月21日	土	1450	井草	500	遊興費
11月21日	土	1630	上石神井	388	食費
					
11月22日	日	900	移動中	199	交通費
11月22日	日	938	移動中	272	交通費
11月22日	日	1031	横浜	909	食費
11月22日	日	1200	横浜	6000	遊興費
11月22日	日	1945	横浜	5000	交際費
11月22日	日	2000	移動中	272	交通費
11月22日	日	2040	移動中	199	交通費
					
11月23日	月	1620	移動中	400	交通費
11月23日	月	1940	下総中山	791	食費
					
11月24日	火	854	下総中山	252	食費
11月24日	火	1322	浅草橋	900	食費
11月24日	火	1953	下総中山	548	食費







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
