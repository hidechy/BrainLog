<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class dailyKabukaUpperGet extends Command
{

    protected $signature = 'dailyKabukaUpperGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $update = [];
        $update['isCountOverTwo'] = '';
        $update['isUpper'] = '';
        DB::table('t_stock')->update($update);

        ////////////////////////////////////////
        $start = strtotime("2020-09-29");
        $end = strtotime(date("Y-m-d"));

        $_threedays = [];
        for ($i = $end; $i >= $start; $i -= 86400) {
            $date = date("Y-m-d", $i);

            $result = DB::table('t_stock')->where('created_at', 'like', $date . '%')->first();
            if (!empty($result)) {
                $_threedays[] = $date;
            }
        }

        $threedays = [
            $_threedays[0],
            $_threedays[1],
            $_threedays[2]
        ];

        sort($threedays);

        $start2 = $threedays[0] . " 00:00:00";
        $end2 = $threedays[count($threedays) - 1] . " 23:59:59";
        ////////////////////////////////////////

        $sql = " select code from t_stock where code != '' group by code; ";
        $result = DB::select($sql);

        foreach ($result as $v) {
            $result = DB::table('t_stock')->where('code', '=', $v->code)
                ->where('created_at', '>=', $start2)->where('created_at', '<=', $end2)
                ->orderBy('id')->get();

            $update = [];
            $update['isCountOverTwo'] = (count($result) >= 2) ? 1 : 0;
            $update['isUpper'] = 0;
            if ($update['isCountOverTwo'] == 1) {
                $firstPrice = $result[0]->torihikichi;
                $lastPrice = $result[count($result) - 1]->torihikichi;
                $update['isUpper'] = ($firstPrice < $lastPrice) ? 1 : 0;
            }

            DB::table('t_stock')->where('code', '=', $v->code)->update($update);
        }
    }
}
