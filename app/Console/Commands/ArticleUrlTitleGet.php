<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class ArticleUrlTitleGet extends Command
{

    protected $signature = 'ArticleUrlTitleGet';

    protected $description = 'Command description';

    public function handle()
    {

        $result = DB::table('t_urllist')->where('flag', '=', '0')->orderBy('id')->get();

        foreach ($result as $v) {

            $update = [];
            $update['flag'] = 9;
            DB::table('t_urllist')->where('id', '=', $v->id)->update($update);

            try {

                if (!is_string($v->url)) {
                    continue;
                }

                if (preg_match("/127\.0\.0\.1/", trim($v->url))) {
                    continue;
                }

                if (preg_match("/160\.16\.86\.159/", trim($v->url))) {
                    continue;
                }

                if (preg_match("/localhost/", trim($v->url))) {
                    continue;
                }

                echo $v->id;
                echo "\n";
                echo $v->url;
                echo "\n";

                $content = file_get_contents($v->url);
                $ex_content = explode("\n", $content);

                foreach ($ex_content as $v2) {
                    if (preg_match("/<title>(.+)<\/title>/", trim($v2), $m)) {

                        $update = [];
                        $update['title'] = trim($m[1]);
                        $update['flag'] = 1;

                        print_r($update);
                        echo "\n";
                        echo "\n";
                        echo "\n";

                        DB::table('t_urllist')->where('id', '=', $v->id)->update($update);

                        break;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
