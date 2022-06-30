<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CatTarotGet extends Command
{

    protected $signature = 'CatTarotGet';

    protected $description = 'Command description';

    public function handle()
    {

        $result = [];

        $l=1;
        $m=1;
        $n=1;
        $o=1;

        for ($i=1; $i<=78; $i++){
            $number = sprintf("%02d", $i);
            $url = "https://lunafactory.co.jp/blogs/uranai/uranaikekka$number";

            $content = file_get_contents($url);
            $ex_content = explode("\n", $content);

            $str = "";
            foreach ($ex_content as $v1){
                $str .= trim($v1);
            }

            $ex_str = explode("|", strtr($str, ['><' => '>|<']));

            $a = 0;
            $b = 0;

            foreach ($ex_str as $k1=>$v1){
                if (preg_match("/Article__Body Rte/", trim($v1))){
                    $a = $k1;
                }

                if (preg_match("/<\/article>/", trim($v1))){
                    $b = $k1;
                }
            }

            $image = "";
            $c = 0;
            for ($j=$a; $j<$b; $j++){
                if (preg_match("/<img/", $ex_str[$j])){
                    if (!preg_match("/次へ/", $ex_str[$j])){
                        $ex_image = explode('"', $ex_str[$j]);
                        foreach ($ex_image as $v2) {
                            if (preg_match("/https/", trim($v2))){
                                $image = trim($v2);
                                $c = $j;
                                break;
                            }
                        }
                    }
                }
            }

            $str2 = "";
            for ($j=$a; $j<$c; $j++){
                $str2 .= trim(strip_tags($ex_str[$j]));
            }
            $name = trim(strtr($str2, ['あなたの今日のカードは…' => '']));

            $img = "";
            if ($i>=1 && $i<=22){
                $num = sprintf("%02d", ($number * 1) - 1);
                $img = "big$num";
            } else if ($i>=23 && $i<=36){
                $numberL = sprintf("%02d", $l);
                $img = "wands$numberL";
                $l++;
            } else if ($i>=37 && $i<=50){
                $numberM = sprintf("%02d", $m);
                $img = "swords$numberM";
                $m++;
            } else if ($i>=51 && $i<=64){
                $numberN = sprintf("%02d", $n);
                $img = "cups$numberN";
                $n++;
            } else {
                $numberO = sprintf("%02d", $o);
                $img = "pentacles$numberO";
                $o++;
            }

            $description = [];
            for ($j=$c; $j<$b; $j++){
                if (trim(strip_tags($ex_str[$j])) != ""){
                    $description[] = trim(strip_tags($ex_str[$j]));
                }
            }

            $ary = [];
            $ary[] = $img;
            $ary[] = $image;
            $ary[] = implode("<br>", $description);

            print_r($ary);

            $result[] = implode("|", $ary);
        }//for ($i=1; $i<=78; $i++)

        $file = "/var/www/html/BrainLog/public/mySetting/CatTarot.data";
        if (file_exists($file)){unlink($file);}

        file_put_contents($file , implode("\n" , $result));
        chmod($file , 0777);

    }

}
