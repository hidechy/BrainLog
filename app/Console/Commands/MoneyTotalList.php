<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\MyClass\Utility;

class MoneyTotalList extends Command
{

    protected $signature = 'MoneyTotalList';

    protected $description = 'Command description';

    var $Utility;
    public function __construct()
    {
        parent::__construct();

        $this->Utility = new Utility;
    }

    public function handle()
    {
        $result = DB::table('t_money')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        if (isset($result[0])){
            $param = [];
            foreach ($result as $v){
                $lineSum = $this->Utility->makeLineSum($v);
                $sum = $lineSum[0];
                $bank = $lineSum[1];
                $pay = $lineSum[2];
                $total = array_sum($sum) + array_sum($bank) + array_sum($pay);
                $param[] = $v->year . "-" . $v->month . "-" . $v->day . "|" . $total;
            }

            if (!empty($param)){
                $param2 = [];
                for ($i=0 ; $i<=99999 ; $i++){
                    if (isset($param[$i])){
                        $sagaku = 0;
                        $date = "2014-06-01";
                        $total = "1370938";
                        if ($i>0){
                            $data_yesterday = $param[$i-1];
                            $data_today = $param[$i];

                            list( , $total_yesterday) = explode("|" , $data_yesterday);
                            list($date , $total) = explode("|" , $data_today);

                            $sagaku = ($i>1) ? ($total_yesterday - $total) : (1370938 - $total);
                        }

                        $youbi = date("w" , strtotime($date));
                        $param2[] = $date . "|" . $youbi . "|" . $total . "|" . $sagaku;
                    }
                }

                if (!empty($param2)){
                    $file = "/var/www/html/BrainLog/public/mySetting/MoneyTotal.data";
                    if (file_exists($file)){unlink($file);}

                    file_put_contents($file , implode("\n" , $param2));
                    chmod($file , 0777);
                }
            }
        }
    }
}
