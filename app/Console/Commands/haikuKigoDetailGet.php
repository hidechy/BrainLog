<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class haikuKigoDetailGet extends Command
{

    protected $signature = 'haikuKigoDetailGet';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $sql = "select * from t_haiku_kigo where detail is null;";
        $result = DB::select($sql);

        foreach ($result as $v) {
            $url = "https://ouchidehaiku.com/contents/{$v->number}";
            $content = file_get_contents($url);
            $ex_content = explode("\n", $content);

            $str = "";
            foreach ($ex_content as $v2) {
                $str .= trim($v2);
            }

            $ex_str = explode("|", strtr($str, ['><' => '>|<']));

            $detail = '';
            foreach ($ex_str as $v2) {
                if (preg_match("/<div class=\"body\">.+<\/div>/", trim($v2))) {
                    $detail = trim(strip_tags($v2));
                    break;
                }
            }

            echo $v->number;
            echo "\n";
            echo $v->season;
            echo "\n";
            echo $v->title;
            echo "\n";
            echo $v->yomi;
            echo "\n";
            echo $detail;
            echo "\n";
            echo "\n";

            $update = [
                "detail" => $detail
            ];

            DB::table('t_haiku_kigo')->where('id', '=', $v->id)->update($update);
        }
    }
}
