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

            $str = "
1190
1188
1085
1084
1083
1189
";


            $ex_str = explode("\n", trim($str));
            foreach ($ex_str as $v) {
                if (trim($v) == "") {
                    continue;
                }

                echo trim($v);
                echo "\n\n";
                
                $update = [];
                $update['item'] = "利息";
                DB::table('t_credit')->where('id', '=', trim($v))->update($update);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
