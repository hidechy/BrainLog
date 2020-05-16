<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class YahooUranaiGet extends Command
{

    protected $signature = 'YahooUranaiGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
    	$url = "https://fortune.yahoo.co.jp/12astro/leo";
    	$content = file_get_contents($url);
    	$ex_content = explode("\n" , mb_convert_encoding($content , "utf8" , "euc-jp"));

    	$str = "";
    	foreach ($ex_content as $v){
    		$str .= trim($v);
    	}

		$uranai = [];
		$uranai['date'] = date("Y-m-d");

    	$ex_str = explode("<!-- yftn12a-bg01/ -->" , $str);

		$ary = [];
    	$a = explode("dl" , $ex_str[1]);
    	$b = strip_tags("<div" . $a[1] . "div>" , "<dt>");
    	$c = strtr($b , ['</dt>' => '<br>']);
    	$d = strip_tags($c , "<br>");
    	$ary[0] = trim($d);
    	$c = explode("点中" , $a[0]);
    	$d = explode("点" , $c[1]);
    	$ary[1] = trim($d[0]);
    	$uranai['total'] = implode(";" , $ary);

		$ary = [];
    	$a = explode("<p>" , $ex_str[2]);
    	$b = explode("</p>" , $a[1]);
    	$ary[0] = trim($b[0]);
    	$c = explode("点中" , $a[0]);
    	$d = explode("点" , $c[1]);
    	$ary[1] = trim($d[0]);
    	$uranai['love'] = implode(";" , $ary);

		$ary = [];
    	$a = explode("<p>" , $ex_str[3]);
    	$b = explode("</p>" , $a[1]);
    	$ary[0] = trim($b[0]);
    	$c = explode("点中" , $a[0]);
    	$d = explode("点" , $c[1]);
    	$ary[1] = trim($d[0]);
    	$uranai['money'] = implode(";" , $ary);

		$ary = [];
    	$a = explode("<p>" , $ex_str[4]);
    	$b = explode("</p>" , $a[1]);
    	$ary[0] = trim($b[0]);
    	$c = explode("点中" , $a[0]);
    	$d = explode("点" , $c[1]);
    	$ary[1] = trim($d[0]);
    	$uranai['work'] = implode(";" , $ary);

//print_r($uranai);

		$data = [];

		$file = "/var/www/html/BrainLog/public/mySetting/uranai.data";
		$aaa = file_get_contents($file);
		$ex_aaa = explode("\n" , $aaa);
		foreach ($ex_aaa as $v){
			if (trim($v) == ""){continue;}
			$ex_v = explode("|" , trim($v));
			$data[trim($ex_v[0])] = trim($v);
		}

		$data[date("Y-m-d")] = implode("|" , $uranai);
		ksort($data);

        file_put_contents($file , implode("\n" , $data));
        chmod($file , 0777);
    }
}
