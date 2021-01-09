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

//
//$str = "
//2020	1	8	神奈川県民共済	3000	B
//2020	1	9	電気代	4470	B
//2020	1	13	ペイペイ	2000	C
//2020	1	16	水道代	1200	C
//2020	1	27	支払い	1718	C
//2020	1	27	ファストジム	9320	C
//2020	1	27	携帯代	4980	C
//2020	1	31	携帯代	16900	C
//2020	1	31	国民健康保険	34700	D
//2020	1	27	住友生命	3787	A
//2020	1	27	住友生命	55880	A
//2020	1	27	ジム会費	5000	A
//";
//
//$ex_str = explode("\n" , $str);
//
//foreach ($ex_str as $v){
//if (trim($v) == ""){continue;}
//
//list($year , $month , $day , $item , $price , $bank) = explode("\t" , trim($v));
//
//$insert = [];
//$insert['year'] = trim($year);
//$insert['month'] = sprintf("%02d" , trim($month));
//$insert['day'] = sprintf("%02d" , trim($day));
//
//$insert['item'] = trim($item);
//$insert['price'] = trim($price);
//$insert['bank'] = trim($bank);
//
//$insert['created_at'] = date("Y-m-d H:i:s");
//$insert['updated_at'] = date("Y-m-d H:i:s");
//
//DB::table('t_credit')->insert($insert);
//
//}


$str = "
| 865 | 2020 | 07    | 29  | ???  | 14850 | C    | 2020-08-01 20:53:30 | 2020-08-01 20:53:30 |
| 887 | 2020 | 08    | 21  | ???  | 14850 | D    | 2020-09-05 20:40:26 | 2020-09-05 20:40:26 |
| 908 | 2020 | 09    | 23  | ???  | 14850 | D    | 2020-10-02 08:33:26 | 2020-10-02 08:33:26 |
| 925 | 2020 | 10    | 27  | ???  | 14850 | D    | 2020-11-06 23:15:57 | 2020-11-06 23:15:57 |
| 932 | 2020 | 11    | 20  | ???  | 14850 | D    | 2020-11-30 20:35:57 | 2020-11-30 20:35:57 |
| 961 | 2020 | 12    | 19  | ???  | 14850 | D    | 2020-12-20 18:32:01 | 2020-12-20 18:32:01 |
| 843 | 2020 | 06    | 26  | ???  | 83875 | D    | 2020-06-28 20:31:55 | 2020-06-28 20:31:55 |
| 812 | 2020 | 04    | 16  | ???  | 49895 | C    | 2020-04-16 20:31:04 | 2020-04-16 20:31:04 |
| 794 | 2020 | 02    | 20  | ???  | 29700 | D    | 2020-03-17 07:02:21 | 2020-03-17 07:02:21 |
";

$ex_str = explode("\n" , $str);

$ids = [];
foreach ($ex_str as $v) {
    if (trim($v) == "") {
        continue;
    }

    $ex_v = explode("|",trim($v));

    $ids[] = trim($ex_v[1]);
}

$update = [];
$update['item'] = "アイアールシー";

DB::table('t_credit')->whereIn('id', $ids)->update($update);

    }
}
