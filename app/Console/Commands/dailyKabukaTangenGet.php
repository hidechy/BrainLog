<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class dailyKabukaTangenGet extends Command
{

    protected $signature = 'dailyKabukaTangenGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $sql = "select code from t_stock where (tangen = '' or tangen is null) group by code;";
        $result = DB::select($sql);

        $tnf = ['t', 'n', 'f'];

        if (isset($result[0])){
            foreach ($result as $k=>$v){

//if ($k>0){break;}

                foreach ($tnf as $_tnf){
                    $url = "https://stocks.finance.yahoo.co.jp/stocks/detail/?code=" . $v->code . "." . $_tnf;

                    $content = file_get_contents($url);
                    $ex_content = explode("\n", $content);

                    $aa = [];
                    foreach ($ex_content as $k2=>$v2){
                        if (preg_match("/単元/", $v2)){
                            $aa[] = $k2;
                        }
                    }

                    if (isset($aa[0])){

                        $str = "";
                        for ($i=$aa[0]; $i<$aa[count($aa)-1]; $i++){
                            $str .= trim(strip_tags($ex_content[$i]));
                        }

                        preg_match("/値段([0-9]+)株/", $str, $m);

                        if (isset($m[1])){

                            $update = [];
                            $update['tangen'] = trim($m[1]);

                            DB::table('t_stock')->where('code', '=', $v->code)->update($update);

print_r($v->code);
echo "\n";
echo $m[1];
echo "\n\n\n";

                        }
                    }
                }
            }
        }
    }
}
