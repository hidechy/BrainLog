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

            $sql = " update t_credit set item = '保険料' where id in (1334, 1335); ";
            DB::update($sql);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
