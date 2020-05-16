<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeiyuuPriceGet extends Command
{

    protected $signature = 'SeiyuuPriceGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = "/var/www/html/BrainLog/public/mySetting/seiyuu.data";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , $content);

        $data = [];
        foreach ($ex_content as $k=>$v){
            if (trim($v) == ""){continue;}

            $oneitem = [];

            list($itemid , $itemname , $price , $url) = explode("|" , trim($v));

            $ex_url = explode("/" , $url);

            $oneitem['itemid'] = $ex_url[count($ex_url) - 2];

            $content2 = file_get_contents($url);
            $ex_content2 = explode("\n" , $content2);

            $str = "";
            foreach ($ex_content2 as $v2){$str .= trim($v2);}

            $ex_str = explode("|" , strtr($str , ['><' => '>|<']));

            $a = [];
            $b = "";
            $c = "";
            foreach ($ex_str as $k2=>$v2){
                if (preg_match("/productName/" , trim($v2))){
                    $oneitem['itemname'] = trim(strip_tags($v2));
                }

                if (preg_match("/<\/div>/" , trim($v2))){$a[] = $k2;}

                if (preg_match("/class=\"photo\">/" , trim($v2))){$b = $k2;}

                if (preg_match("/class=\"priceBox\">/" , trim($v2))){$c = $k2;}
            }

            if (!isset($oneitem['itemname'])){continue;}

            $end1 = "";
            foreach ($a as $v2){
                if ($v2>$b){
                    $end1 = $v2;
                    break;
                }
            }

            $end2 = "";
            foreach ($a as $v2){
                if ($v2>$c){
                    $end2 = $v2;
                    break;
                }
            }

            $price = "";
            for ($i=$c ; $i<$end2 ; $i++){$price .= trim(strip_tags($ex_str[$i]));}
            $oneitem['price'] = strtr($price , ['&yen;' => '' , '(税抜)' => '']);

/*
print_r($oneitem);
continue;
*/

            $oneitem['url'] = $url;

            for ($i=$b ; $i<$end1 ; $i++){
                if (preg_match("/<img/" , $ex_str[$i])){
                    $ex_img = explode("\"" , $ex_str[$i]);
                    foreach ($ex_img as $v2){
                        if (preg_match("/https/" , trim($v2))){
                            $oneitem['img'] = trim($v2);
                            break;
                        }
                    }

                    if (!empty($oneitem['img'])){break;}
                }
            }

            $data[] = implode("|" , $oneitem);
        }

        file_put_contents($file , implode("\n" , $data));

        chmod($file , 0777);
    }
}
