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

            $update = [];
            $update['item'] = '国民健康保険';
//DB::table('t_credit')->where('id', '=', '850')->update($update);
            DB::table('t_credit')->whereIn('id', [791, 789, 797, 806])->update($update);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
