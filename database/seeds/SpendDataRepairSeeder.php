<?php

use Illuminate\Database\Seeder;

class SpendDataRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $result = DB::table('t_dailyspend')->where('created_at', '=', '0000-00-00 00:00:00')->get();
        foreach ($result as $v){

            $update = [];
            $update['created_at'] = date("Y-m-d H:i:s");
            $update['updated_at'] = date("Y-m-d H:i:s");

            DB::table('t_dailyspend')->where('id', '=', $v->id)->update($update);

print_r($v);
echo "\n\n";

        }
    }
}
