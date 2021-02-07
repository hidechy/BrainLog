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
1月25日	月	1939	下総中山	858	食費
1月25日	月	1946	下総中山	1297	食費
					
1月26日	火		自宅	0	
					
1月27日	水		自宅	0	
					
1月28日	木		自宅	0	
					
1月29日	金	1930	下総中山	710	食費
					
1月30日	土	1030	移動中	220	交通費
1月30日	土	1053	錦糸町	147	食費
1月30日	土	1103	錦糸町	1200	教育費
1月30日	土	1920	移動中	220	交通費
1月30日	土	1950	下総中山	720	食費





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
