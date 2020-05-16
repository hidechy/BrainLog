<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeechsGet extends Command
{

    protected $signature = 'GeechsGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {


        $url_base = "https://geechs-job.com/project?freeWords%5B0%5D=php&page={NO}";

        $content = file_get_contents(strtr($url_base , ['{NO}' => 1]));
        $ex_content = explode("\n" , $content);

        foreach ($ex_content as $oneline){
            if (preg_match("/p-searchResult_count/" , $oneline)){
                $str = trim(strip_tags($oneline));
                break;
            }
        }

        preg_match("/([0-9]+)/" , $str , $m);

        $pageNum = ceil($m[1] / 10);

        for ($i=1 ; $i<$pageNum ; $i++){



if ($i>1){break;}



	        $content = file_get_contents(strtr($url_base , ['{NO}' => $i]));
	        $ex_content = explode("\n" , $content);

			$ary = [];
	        foreach ($ex_content as $oneline){
	        	if (preg_match("/c-card_title_link/" , $oneline)){
	        		$ary[] = trim($oneline);
	        	}
	        }
//print_r($ary);


			$param = [];
			foreach ($ary as $k=>$v){
				preg_match("/href=\"(.+)\">/" , $v , $m);
				$param[$k]['url'] = trim($m[1]);
				
				$param[$k]['title'] = trim(strip_tags($v));
			}

			foreach ($param as $k=>$v){

		        $content2 = file_get_contents($v['url']);
		        $ex_content2 = explode("\n" , $content2);

				$a = 0;
				$b = 0;
		        foreach ($ex_content2 as $onelineno2=>$oneline2){
		        	if (preg_match("/<section class=\"l-primary\">/" , $oneline2)){$a = $onelineno2;}
		        	if (preg_match("/c-entryButton/" , $oneline2)){$b = $onelineno2;}
		        }

				$c = [];
		        for ($o=$a ; $o<$b ; $o++){
		        	if (preg_match("/c-table_title-/" , $ex_content2[$o])){$c[] = $o;}
		        }

		        $c[] = $b;




$e = [];
foreach ($c as $d){
$e[] = trim(strip_tags($ex_content2[$d]));
}

$param[$k]['e'] = $e;



















			}



print_r($param);





        }
    }
}
