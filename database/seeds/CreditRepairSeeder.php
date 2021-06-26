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

            $sql = " update t_credit set item = 'アイアールシー' where id in (1285); ";
            DB::update($sql);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
