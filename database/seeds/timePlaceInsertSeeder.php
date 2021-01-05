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

12月28日	月	820	移動中	283	交通費
12月28日	月	1300	六本木一丁目	810	食費
12月28日	月	1845	移動中	283	交通費
12月28日	月	1954	西船橋	977	食費
12月28日	月	1954	西船橋	1645	雑費
					
12月29日	火	1500	移動中	482	交通費
					
12月30日	水	1300	等々力	200	お賽銭
12月30日	水	1300	等々力	100	お賽銭
12月30日	水	1330	等々力	100	お賽銭
12月30日	水	1430	二子玉川	3795	交際費
					
12月31日	木		実家	0	
					
1月1日	金	1500	東伏見	5	お賽銭
					
1月2日	土	1200	善福寺	100	お賽銭
					
1月3日	日	1900	移動中	482	交通費
1月3日	日	2000	下総中山	990	食費
					
1月4日	月	804	西船橋	181	食費
1月4日	月	820	移動中	283	交通費
1月4日	月	1248	六本木一丁目	910	食費
1月4日	月	1930	移動中	283	交通費
1月4日	月	2030	西船橋	700	食費
					
1月5日	火		自宅	0	





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
