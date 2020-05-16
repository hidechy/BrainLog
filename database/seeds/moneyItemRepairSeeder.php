<?php

use Illuminate\Database\Seeder;

class moneyItemRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



$sql = "update t_credit set item = '支払い' where item = '（振込）';";
DB::statement($sql);

$sql = "update t_credit set item = '支払い' where item = 'ジャックスカード';";
DB::statement($sql);

$sql = "update t_credit set item = '支払い' where item = 'ユーシーカード';";
DB::statement($sql);

$sql = "update t_credit set item = 'ジム会費' where item = 'ファストジム';";
DB::statement($sql);

$sql = "update t_credit set item = '保険料' where item = '住友生命';";
DB::statement($sql);

$sql = "update t_credit set item = '保険料' where item = '国民健康保険';";
DB::statement($sql);

$sql = "update t_credit set item = '年金' where item = '国民年金';";
DB::statement($sql);

$sql = "update t_credit set item = '住居費' where item = '家賃';";
DB::statement($sql);

$sql = "update t_credit set item = '住居費' where item = '部屋更新費用';";
DB::statement($sql);

$sql = "update t_credit set item = '手数料' where item = '振込手数料';";
DB::statement($sql);

$sql = "update t_credit set item = '通信費' where item = '携帯代';";
DB::statement($sql);

$sql = "update t_credit set item = '水道光熱費' where item = '水道代';";
DB::statement($sql);

$sql = "update t_credit set item = '水道光熱費' where item = '電気代';";
DB::statement($sql);

$sql = "update t_credit set item = '共済代' where item = '神奈川県民共済';";
DB::statement($sql);


$sql = "update t_credit set item = '水道光熱費' where item = 'ガス代';";
DB::statement($sql);




$sql = "update t_dailyspend set koumoku = '被服費' where koumoku = '衣料費';";
DB::statement($sql);
















/*
        mysql> select item from t_credit group by item;
        +--------------------------+
        | item                     |
        +--------------------------+
        | （振込）             |
        | ガス代                |
        | ジム会費             |
        | ジャックスカード |
        | ファストジム       |
        | ペイペイ             |
        | ユーシーカード    |
        | 住友生命             |
        | 国民健康保険       |
        | 国民年金             |
        | 家賃                   |
        | 手数料                |
        | 振込手数料          |
        | 携帯代                |
        | 支払い                |
        | 水道代                |
        | 神奈川県民共済    |
        | 部屋更新費用       |
        | 電気代                |
        +--------------------------+
        19 rows in set (0.00 sec)
        
        mysql> select koumoku from t_dailyspend group by koumoku;
        +-----------+
        | koumoku   |
        +-----------+
        | お賽銭 |
        | プラス |
        | 不明    |
        | 交通費 |
        | 交際費 |
        | 医療費 |
        | 教育費 |
        | 美容費 |
        | 衣料費 |
        | 被服費 |
        | 遊興費 |
        | 雑費    |
        | 食費    |
        +-----------+
        13 rows in set (0.00 sec)
*/        








    }
}
