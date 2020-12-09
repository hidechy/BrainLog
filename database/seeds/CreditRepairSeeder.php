<?php

use Illuminate\Database\Seeder;

class CreditRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        try {
            DB::beginTransaction();



/*
            $update = [];
            $update['item'] = '不明';
            DB::table('t_credit')->where('id', '=', 956)->update($update);
*/









/*
            mysql> select * from t_credit where price = '26625';
+-----+------+-------+-----+--------+-------+------+---------------------+---------------------+
| id  | year | month | day | item   | price | bank | created_at          | updated_at          |
            +-----+------+-------+-----+--------+-------+------+---------------------+---------------------+
| 824 | 2020 | 05    | 01  | ?????? | 26625 | D    | 2020-05-08 18:09:24 | 2020-05-08 18:09:24 |
| 834 | 2020 | 06    | 01  | ???    | 26625 | D    | 2020-06-02 23:35:40 | 2020-06-02 23:35:40 |
| 851 | 2020 | 07    | 01  | ?????? | 26625 | D    | 2020-07-07 20:52:22 | 2020-07-07 20:52:22 |
| 879 | 2020 | 08    | 03  | ?????? | 26625 | D    | 2020-09-05 20:40:26 | 2020-09-05 20:40:26 |
| 891 | 2020 | 09    | 01  | ?????? | 26625 | D    | 2020-09-05 20:40:26 | 2020-09-05 20:40:26 |
| 912 | 2020 | 10    | 01  | ?????? | 26625 | D    | 2020-10-02 08:33:26 | 2020-10-02 08:33:26 |
| 928 | 2020 | 11    | 02  | ?????? | 26625 | D    | 2020-11-06 23:15:57 | 2020-11-06 23:15:57 |
| 946 | 2020 | 12    | 01  | ????   | 26625 | D    | 2020-12-04 21:37:31 | 2020-12-04 21:37:31 |
            +-----+------+-------+-----+--------+-------+------+---------------------+---------------------+
            8 rows in set (0.01 sec)
*/



$update = [];
$update['item'] = '国民年金基金';
DB::table('t_credit')->whereIn('id', [824, 834, 851, 879, 891, 912, 928, 946])->update($update);








            /*
            $update = [];
            $update['price'] = '67000';
            DB::table('t_credit')->whereIn('id', [792, 790, 798, 814, 825, 841])->update($update);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '01';
            $insert['day'] = '06';
            $insert['item'] = '手数料';
            $insert['price'] = '110';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '02';
            $insert['day'] = '05';
            $insert['item'] = '手数料';
            $insert['price'] = '330';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '03';
            $insert['day'] = '06';
            $insert['item'] = '手数料';
            $insert['price'] = '330';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '04';
            $insert['day'] = '05';
            $insert['item'] = '手数料';
            $insert['price'] = '330';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '05';
            $insert['day'] = '06';
            $insert['item'] = '手数料';
            $insert['price'] = '440';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);

            $insert = [];
            $insert['year'] = '2020';
            $insert['month'] = '06';
            $insert['day'] = '06';
            $insert['item'] = '手数料';
            $insert['price'] = '418';
            $insert['bank'] = 'D';
            DB::table('t_credit')->insert($insert);
*/








            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
