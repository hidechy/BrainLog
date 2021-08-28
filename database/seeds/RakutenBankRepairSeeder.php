<?php

use Illuminate\Database\Seeder;

class RakutenBankRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {












        $str = "

2020/12/25	15002
2021/1/25	5002
2021/2/25	35002
2021/3/25	25002
2021/4/26	15007
2021/5/25	35007
2021/6/21	25007
2021/6/23	125007
2021/6/25	115007
2021/7/19	85007
2021/7/26	75007
2021/7/27	62557
2021/8/16	142249
2021/8/17	143069


";


        $ex_str = explode("\n", $str);

        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($date, $price) = explode("\t", trim($v));

            list($year, $month, $day) = explode("/", trim($date));



            $year = sprintf("%02d", $year);
            $month = sprintf("%02d", $month);
            $day = sprintf("%02d", $day);






            $result = DB::table('t_money')
                ->where('year', '=', $year)
                ->where('month', '=', $month)
                ->where('day', '=', $day)
                ->first();


echo trim($v);
echo "\n\n";





            DB::table('t_money')
                ->where('id', '>=', $result->id)
                ->update(['bank_e' => $price]);
        }











    }
}
