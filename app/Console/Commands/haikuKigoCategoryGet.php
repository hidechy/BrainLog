<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class haikuKigoCategoryGet extends Command
{

    protected $signature = 'haikuKigoCategoryGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $pageNum = [
//            "%E4%BA%BA%E4%BA%8B" => 682,
//            "%E6%A4%8D%E7%89%A9" => 501,
//            "%E5%AE%97%E6%95%99" => 301,
//            "%E5%9C%B0%E7%90%86" => 62,
            "%E5%8B%95%E7%89%A9" => 285,
            "%E6%99%82%E5%80%99" => 142,
            "%E5%A4%A9%E6%96%87" => 125
        ];

        $cat = [
            "%E4%BA%BA%E4%BA%8B" => "人事",
            "%E6%A4%8D%E7%89%A9" => "植物",
            "%E5%AE%97%E6%95%99" => "宗教",
            "%E5%9C%B0%E7%90%86" => "地理",
            "%E5%8B%95%E7%89%A9" => "動物",
            "%E6%99%82%E5%80%99" => "時候",
            "%E5%A4%A9%E6%96%87" => "天文"
        ];

        foreach ($pageNum as $k => $v) {

            $category = $cat[$k];

            for ($i = 1; $i <= $v; $i++) {

                $url = "https://ouchidehaiku.com/contents/seasonwords/{$k}/page/{$i}";
                $content = file_get_contents($url);
                $ex_content = explode("\n", $content);

                $str = "";
                foreach ($ex_content as $v2) {
                    $str .= trim($v2);
                }

                $ex_str = explode("|", strtr($str, ['<li' => '|<li', 'li>' => 'li>|']));

                foreach ($ex_str as $v2) {
                    if (preg_match("/li/", trim($v2))) {
                        if (preg_match("/class=\"title/", trim($v2))) {
                            if (preg_match("/contents/", trim($v2))) {

                                try {

                                    $number = 0;
                                    preg_match("/href=.+contents(.+)\"><div/", trim($v2), $m1);
                                    $number = trim(strtr($m1[1], ['/' => '']));

                                    echo $category;
                                    echo "\n";
                                    echo $number;
                                    echo "\n";
                                    echo "\n";

                                    $update = [];
                                    $update['category'] = $category;
                                    DB::table('t_haiku_kigo')->where('number', $number)->update($update);

                                } catch (\Exception $e) {
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
