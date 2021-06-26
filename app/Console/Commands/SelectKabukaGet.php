<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class SelectKabukaGet extends Command
{

    protected $signature = 'SelectKabukaGet';

    protected $description = 'Command description';

    public function handle()
    {

        $file = "/var/www/html/BrainLog/public/mySetting/stock.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n", $content);

        foreach ($ex_content as $v) {
            if (trim($v) == "") {
                continue;
            }

            list($code, $market, $company) = explode("|", trim($v));

            $url = "https://minkabu.jp/stock/" . $code;
            $content2 = file_get_contents($url);
            $ex_content2 = explode("\n", $content2);

            $a = 0;
            $b = [];
            foreach ($ex_content2 as $k2 => $v2) {
                if (preg_match("/<div class=\"stock_price\">/", $v2)) {
                    $a = $k2;
                }

                if (preg_match("/<\/div>/", $v2)) {
                    $b[] = $k2;
                }
            }

            rsort($b);
            $end = 0;
            foreach ($b as $v2) {
                if ($v2 < $a) {
                    break;
                }
                $end = $v2;
            }


            $str = "";
            for ($i = $a; $i <= $end; $i++) {
                $str .= trim(strip_tags($ex_content2[$i]));
            }

            $price = strtr($str, ['円' => '', ',' => '']);
            $ex_price = explode(".", $price);
            $price = $ex_price[0];

            $insert = [];
            $insert['year'] = date("Y");
            $insert['month'] = date("m");
            $insert['day'] = date("d");
            $insert['code'] = $code;
            $insert['price'] = $price;

            DB::table('t_selectstock')->insert($insert);

            print_r($insert);
        }
    }
}
