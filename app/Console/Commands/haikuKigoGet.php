<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class haikuKigoGet extends Command
{

    protected $signature = 'haikuKigoGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $pageNum = [
            "spring" => 428,
            "summer" => 593,
            "autumn" => 475,
            "winter" => 368,
            "newyear" => 230,
        ];

        foreach ($pageNum as $k => $v) {

            for ($i = 1; $i <= $v; $i++) {

                $url = "https://ouchidehaiku.com/{$k}/contents/seasonwords/page/{$i}";
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
                                    $title = '';
                                    $yomi = '';

                                    preg_match("/href=.+contents(.+)\"><div/", trim($v2), $m1);
                                    $number = trim(strtr($m1[1], ['/' => '']));

                                    $result = DB::table('t_haiku_kigo')->where('number', '=', $number)->first();
                                    if (isset($result)) {
                                        continue;
                                    }

                                    preg_match("/<h3>(.+)<\/h3>/", trim($v2), $m2);

                                    $ex_m2_1 = explode("（", trim($m2[1]));
                                    if (count($ex_m2_1) > 1) {
                                        $title = trim($ex_m2_1[0]);

                                        $_yo = trim(strtr($ex_m2_1[1], ['）' => '']));
                                        $ex_yo = explode("・", trim($_yo));
                                        $yomi = trim($ex_yo[0]);

                                    } else {
                                        $title = trim($m2[1]);
                                        $yomi = trim($m2[1]);
                                    }

                                    $insert = [
                                        'season' => $k,
                                        'number' => $number,
                                        'title' => $title,
                                        'yomi' => $yomi,
                                        'length' => mb_strlen($yomi),
                                    ];

                                    print_r($insert);

                                    DB::table('t_haiku_kigo')->insert($insert);

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
