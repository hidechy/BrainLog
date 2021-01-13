<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class ArticleUrlGet extends Command
{

    protected $signature = 'ArticleUrlGet';

    protected $description = 'Command description';

    public function handle()
    {

        $url = [];

        for ($i = 2017; $i <= date("Y"); $i++) {

            $table = "t_article" . $i;

            $result = DB::table($table)->where('article', 'like', '%http%')->get();

            foreach ($result as $v) {
                $ex_v = explode("\n", $v->article);

                foreach ($ex_v as $v2) {
                    if (preg_match("/^http.+:/", trim($v2))) {
                        $url[] = trim($v2);
                    }
                }
            }
        }

        sort($url);

        foreach ($url as $v) {

            $result = DB::table('t_urllist')->where('url', '=', $v)->get();

            if (empty($result[0])) {

                $insert = [];
                $insert['url'] = trim($v);

                DB::table('t_urllist')->insert($insert);
            }
        }
    }
}
