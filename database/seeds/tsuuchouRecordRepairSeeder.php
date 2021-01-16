<?php

use Illuminate\Database\Seeder;

class tsuuchouRecordRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $str = "

2020	7	27	支払い	14042
2020	8	27	支払い	18212
2020	1	27	支払い	4980
2020	3	27	支払い	5539
2020	5	27	支払い	9104
2020	6	29	支払い	11541
2020	1	6	支払い	26535
2020	1	27	支払い	1718
2020	2	5	支払い	22449
2020	2	26	支払い	1633
2020	3	5	支払い	24026
2020	3	26	支払い	1635
2020	4	6	支払い	45806
2020	4	27	支払い	1604
2020	4	27	支払い	5260
2020	5	7	支払い	38550
2020	5	26	支払い	1634
2020	6	5	支払い	55316
2020	6	26	支払い	1613
2020	7	6	支払い	41165
2020	7	27	支払い	2500
2020	8	5	支払い	35444
2020	8	26	支払い	2559
2020	9	7	支払い	29701
2020	9	28	支払い	2631
2020	9	28	支払い	47938
2020	10	5	支払い	28114
2020	10	26	支払い	7084
2020	10	30	支払い	12736
2020	11	5	支払い	32769
2020	11	26	支払い	6889
2020	12	7	支払い	35493
2020	12	28	支払い	6985
2020	12	28	支払い	5060

";

        $ex_str = explode("\n", $str);

        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($year, $month, $day, $x, $price) = explode("\t", trim($v));

            $result = DB::table('t_credit')
                ->where('year', '=', $year)
                ->where('month', '=', sprintf("%02d", $month))
                ->where('day', '=', sprintf("%02d", $day))
                ->where('price', '=', $price)
                ->get();

            if (empty($result[0])) {
                echo $year;
                echo "\n";
                echo $month;
                echo "\n";
                echo $day;
                echo "\n";
                echo $price;
                echo "\n";
                echo "\n";
            } else {

                echo $result[0]->id;
                echo "\n";

                DB::table('t_credit')->where('id', '=', $result[0]->id)->update(['item' => 'credit']);
            }
        }
    }
}
