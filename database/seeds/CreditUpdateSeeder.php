<?php

use Illuminate\Database\Seeder;

class CreditUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

$str = "
2020	1	8	神奈川県民共済	3000	B
2020	1	9	電気代	4470	B
2020	1	13	ペイペイ	2000	C
2020	1	16	水道代	1200	C
2020	1	27	支払い	1718	C
2020	1	27	ファストジム	9320	C
2020	1	27	携帯代	4980	C
2020	1	31	携帯代	16900	C
2020	1	31	国民健康保険	34700	D
2020	1	27	住友生命	3787	A
2020	1	27	住友生命	55880	A
2020	1	27	ジム会費	5000	A
";

$ex_str = explode("\n" , $str);

foreach ($ex_str as $v){
if (trim($v) == ""){continue;}

list($year , $month , $day , $item , $price , $bank) = explode("\t" , trim($v));

$insert = [];
$insert['year'] = trim($year);
$insert['month'] = sprintf("%02d" , trim($month));
$insert['day'] = sprintf("%02d" , trim($day));

$insert['item'] = trim($item);
$insert['price'] = trim($price);
$insert['bank'] = trim($bank);

$insert['created_at'] = date("Y-m-d H:i:s");
$insert['updated_at'] = date("Y-m-d H:i:s");

DB::table('t_credit')->insert($insert);

}

    }
}
