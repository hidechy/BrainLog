<?php
namespace App\MyClass;

use DB;

class Utility
{
    public function makeLineSum($linedata){

        $sum = [];
        $bank = [];
        $pay = [];
        foreach ($linedata as $k=>$v){
            if (preg_match("/^yen_(.+)/" , $k , $m)){
                list( , $yen) = $m;
                $sum[] = ($yen * $v);
            }

            if (preg_match("/^bank/" , $k)){
                $bank[] = $v;
            }

            if (preg_match("/^pay/" , $k)){
                $pay[] = $v;
            }
        }

        return [$sum , $bank , $pay];
    }



	function wp_is_mobile() {
		static $is_mobile;

		if (isset ( $is_mobile )) {
			return $is_mobile;
		}

		if (empty ( $_SERVER ['HTTP_USER_AGENT'] )) {
			$is_mobile = false;
		}

		elseif (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Mobile' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Android' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Silk/' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Kindle' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'BlackBerry' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Opera Mini' ) !== false || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false) {
			$is_mobile = true;
		}

		else {
			$is_mobile = false;
		}

		return $is_mobile;
	}



	function getArticleTable(){
		$ret = [];

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = database();";
        $result = DB::select($sql);

        foreach($result as $v){
            if (preg_match("/t_article/" , $v->table_name)){
                $ret[] = $v->table_name;
            }
        }

/*
		$result = DB::table('information_schema.columns')->lists('TABLE_NAME');
		if (!empty($result)){
			$_ret = [];
			foreach ($result as $v){
				if (preg_match("/t_article/" , $v)){
					$_ret[$v] = "";
				}
			}
		}

		$ret = array_keys($_ret);
		sort($ret);
*/







		return $ret;
	}



	function getFileList($dir) {
	    $files = scandir($dir);
	    $files = array_filter($files , function ($file) {
	        return !in_array($file , array('.' , '..'));
	    });

	    $list = array();
	    foreach ($files as $file) {
	        $fullpath = rtrim($dir , '/') . '/' . $file;
	        if (is_file($fullpath)) {
	            $list[] = $fullpath;
	        }
	        if (is_dir($fullpath)) {
	            $list = array_merge($list , getFileList($fullpath));
	        }
	    }

	    return $list;
	}



    public function getFolderPhoto($folderPath = null){
        $ret = [];

        if (!is_null($folderPath)){
            if (file_exists($folderPath)){
                $d = dir($folderPath);
                while (false !== ($entry = $d->read())) {
                    if (($entry == ".") or ($entry == "..")){continue;}
                    $ret[] = $entry;
                }
                $d->close();
            }
        }else{
            $ary1 = [];
            $d = dir("/var/www/html/BrainLog/public/UPPHOTO");
            while (false !== ($entry = $d->read())) {
                if (($entry == ".") or ($entry == "..")){continue;}
                $ary1[] = $entry;
            }
            $d->close();

            sort($ary1);

            $ary2 = [];
            foreach ($ary1 as $year){
                $d = dir("/var/www/html/BrainLog/public/UPPHOTO/" . $year);
                while (false !== ($entry = $d->read())) {
                    if (($entry == ".") or ($entry == "..")){continue;}
                    $ary2[$year][] = $entry;
                }
                $d->close();
            }

            $ary3 = [];
            foreach ($ary2 as $year=>$v){
                sort($v);
                $ary3[$year] = $v;
            }

            $ary4 = [];
            foreach ($ary3 as $year=>$v){
                foreach ($v as $date){
                    $d = dir("/var/www/html/BrainLog/public/UPPHOTO/" . $year . "/" . $date);
                    while (false !== ($entry = $d->read())) {
                        if (($entry == ".") or ($entry == "..")){continue;}
                        $ary4[$year][$date][] = $entry;
                    }
                    $d->close();
                }
            }

            $ary5 = [];
            foreach ($ary4 as $year=>$v){
                foreach ($v as $date=>$v2){
                    sort($v2);
                    $ary5[$year][$date] = $v2;
                }
            }

            $ret = $ary5;
        }

        return $ret;
    }



}
?>
