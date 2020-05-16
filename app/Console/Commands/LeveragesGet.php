<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeveragesGet extends Command
{

    protected $signature = 'LeveragesGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $filePath = "/var/www/html/BrainLog/public/mySetting/Leverages.data";
        if (file_exists($filePath)){unlink($filePath);}
        $fp = fopen($filePath , 'a');

        $url = "https://freelance.levtech.jp/project/skill-5/";
        $content = file_get_contents($url);
        $ex_content = explode("\n" , $content);

        foreach ($ex_content as $oneline){
            if (preg_match("/search__result__summary/" , $oneline)){
                $str = trim(strip_tags($oneline));
                break;
            }
        }

        preg_match("/該当案件数(.+)件中/" , $str , $m);

        $pageNum = ceil($m[1] / 20);

        $urlbase = "https://freelance.levtech.jp/project/skill-5/p{NO}/";

        $midashi = [
            '単価' => 'Price' , 
            '契約形態' => 'Keiyakukeitai' , 
            '最寄り駅' => 'Moyorieki' , 
            '職種・ポジション' => 'Syokushu' , 
            '職務内容' => 'Syokumunaiyou' , 
            '求めるスキル' => 'Skill' , 
            'この会社が扱う技術' => 'Gijutsu' , 
            '精算・お支払い' => 'Jikan'
        ];

        $outMidashi = [
            'Price' , 'Keiyakukeitai' , 'Moyorieki' , 'Syokushu' , 'Syokumunaiyou' ,
            'Skill' , 'Gijutsu' , 'Jikan'
        ];

        for ($i=1 ; $i<$pageNum ; $i++){



//if ($i>1){break;}



            if ($i == 1){
                $url = "https://freelance.levtech.jp/project/skill-5/";
            }else{
                $url = strtr($urlbase , ['{NO}' => $i]);
            }

            $content = file_get_contents($url);
            $ex_content = explode("\n" , $content);

            $Title = [];
            $q=0;
            foreach ($ex_content as $oneline){
                if (preg_match("/js-link_rel/" , $oneline)){

                    preg_match("/href=\"(.+)\" class/" , $oneline , $m);
                    $Title[$q]['url'] = "https://freelance.levtech.jp" . trim($m[1]);

                    $Title[$q]['title'] = trim(strip_tags($oneline));

                    $q++;
                }
            }

            foreach ($Title as $k=>$v){



//if ($k>0){break;}



                $content2 = file_get_contents($v['url']);
                $ex_content2 = explode("\n" , $content2);

                $str = "";
                foreach ($ex_content2 as $onelineno2 => $oneline2){
                    $str .= trim($oneline2);
                }

                $ex_str = explode("|" , strtr($str , ['><' => '>|<' , '<br/>' => '<br/>|']));

                ///////////////////////////////////
                $SummaryStart = 0;
                $SummaryEnd = 0;

                $DetailStart = 0;
                $DetailEnd = 0;

                foreach ($ex_str as $onelineno2 => $oneline2){
                    if (preg_match("/<div class=\"pjtSummary\">/" , $oneline2)){$SummaryStart = $onelineno2;}
                    if (preg_match("/<div class=\"pjtDetail\">/" , $oneline2)){$SummaryEnd = $onelineno2;}

                    if (preg_match("/<div class=\"pjtComment\">/" , $oneline2)){$DetailEnd = $onelineno2;}
                }

                $DetailStart = $SummaryEnd;
                ///////////////////////////////////

                $Pos = [];
                for ($j=$SummaryStart ; $j<$SummaryEnd ; $j++){
                    if (preg_match("/<p class=\"pjtSummary__row__ttl\">.+<\/p>/" , $ex_str[$j])){
                        $Pos[] = $j;
                    }
                }
                $Pos[] = $SummaryEnd;

                $PARAM = [];

$priceLimit = 0;

                for ($j=0 ; $j<count($Pos)-1 ; $j++){

                    $ttl = trim(strip_tags($ex_str[$Pos[$j]]));

                    $_desc = [];
                    for ($n=($Pos[$j] + 1) ; $n<($Pos[$j + 1]) ; $n++){
                        if (trim(strip_tags($ex_str[$n])) != ""){
                            $_desc[] = trim(strip_tags($ex_str[$n]));
                        }
                    }

                    $__desc = implode(" / " , $_desc);
                    if ($ttl == "単価"){
                        preg_match("/([,0-9]+)/" , $__desc , $m);
                        $__desc = trim(strtr($m[1] , [',' => '']));

if ($__desc < 700000){
$priceLimit = 1;
}

                    }

                    $MIDASHI = (isset($midashi[$ttl])) ? $midashi[$ttl] : $ttl;
                    $PARAM[$MIDASHI] = "(" . $MIDASHI . "):" . $__desc;
                }

if ($priceLimit == 1){continue;}

                $Pos = [];
                for ($j=$DetailStart ; $j<$DetailEnd ; $j++){
                    if (preg_match("/<p class=\"pjtDetail__row__ttl\">.+<\/p>/" , $ex_str[$j])){
                        $Pos[] = $j;
                    }
                }

                for ($j=0 ; $j<count($Pos)-2 ; $j++){

                    $ttl = trim(strip_tags($ex_str[$Pos[$j]]));

                    $_desc = [];
                    for ($n=($Pos[$j] + 1) ; $n<($Pos[$j + 1]) ; $n++){
                        if (trim(strip_tags($ex_str[$n])) != ""){
                            $_desc[] = trim(strip_tags($ex_str[$n]));
                        }
                    }

                    switch ($ttl){
                        case "職務内容":
                            $__desc = implode("@" , $_desc);
                            break;

                        case "求めるスキル":
                            $x = [];
                            foreach ($_desc as $w){
                                if (preg_match("/※上記に似た経験やスキルを/" , $w)){continue;}
                                $x[] = $w;
                            }
                            $__desc = implode("@" , $x);
                            break;

                        case "この会社が扱う技術":
                            $x = [];
                            foreach ($_desc as $w){
                                $x[] = strtr($w , [',&nbsp; ' => ' / ']);
                            }
                            $__desc = implode("@" , $x);
                            break;

                        case "精算・お支払い":
                            $x = [];
                            foreach ($_desc as $w){
                                if (preg_match("/標準となる稼働時間が定められている場合、/" , $w)){continue;}
                                if (preg_match("/標準となる稼働時間の上限値・下限値です。/" , $w)){continue;}
                                if (preg_match("/報酬お支払いまでの期間です。/" , $w)){continue;}
                                $x[] = $w;
                            }
                            $__desc = implode("@" , $x);
                            break;
                    }

                    $MIDASHI = (isset($midashi[$ttl])) ? $midashi[$ttl] : $ttl;
                    $PARAM[$MIDASHI] = "(" . $MIDASHI . "):" . $__desc;
                }

                $OUT = [];

                $OUT[] = $v['title'];
                $OUT[] = $v['url'];

                foreach ($outMidashi as $om){
                    if (isset($PARAM[$om])){
                        $OUT[$om] = $PARAM[$om];
                    }
                }

                fwrite ($fp , implode("|" , $OUT) . "\n");
            }
        }
    }
}
