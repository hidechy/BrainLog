<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KotowazaGet extends Command
{

    protected $signature = 'KotowazaGet';

    protected $description = 'Command description';

    public function handle()
    {

        $url = "https://tomomi965.com/ichiran.html";
        $content = file_get_contents($url);
        $ex_content = explode("\n", $content);

        $str = "";
        foreach ($ex_content as $val) {
            $str .= trim($val);
        }

        $ex_str = explode("|", strtr($str, ['><' => '>|<']));
        $linkList = [];
        foreach ($ex_str as $val) {
            if (\preg_match("/<a /", $val) and \preg_match("/title/", $val) and !\preg_match("/<li/", $val)) {
                $linkList[] = trim($val);
            }
        }

        $line = [];
        foreach ($linkList as $val) {
            if (trim($val) == "") {continue;}

            $ex_val = explode(">", trim($val));
            $ex_val0 = explode(" ", trim($ex_val[0]));

            $word = "";
            $url = "";
            foreach ($ex_val0 as $val2) {

                if (\preg_match("/title=\"(.+)\"/", $val2, $m)) {
                    $word = trim($m[1]);
                }

                if (\preg_match("/href=\"(.+)\"/", $val2, $m)) {
                    $url = trim($m[1]);
                }

                if (trim($url) != "" and trim($word) != "") {

                    $content10 = \file_get_contents("https://tomomi965.com/". $url);

                    $ex_content10 = explode("\n", $content10);

                    $str10 = "";
                    foreach ($ex_content10 as $val10) {
                        $str10 .= trim($val10);
                    }

                    $ex_str10 = explode("|", strtr($str10, ['><' => '>|<']));

                    $a = 0;
                    $b = [];
                    foreach ($ex_str10 as $key10 => $val10) {
                        if ($a == 0) {
                            if (\preg_match("/class=\"box-content\"/", $val10)) {
                                $a = $key10;
                            }
                        }

                        if (\preg_match("/<\/div>/", $val10)) {
                            $b[] = $key10;
                        }
                    }

                    $end = 0;
                    rsort($b);
                    foreach ($b as $bb) {
                        if ($bb > $a) {
                            $end = $bb;
                        }
                    }

                    $mean = "";
                    for ($p = $a; $p < $end; $p++) {
                        $mean .= $ex_str10[$p];
                    }
                    $mean = trim(\strip_tags($mean));

                    if (trim($mean) != ""){

                        echo $word;
                        echo "\n";
                        echo $mean;
                        echo "\n";
                        echo "\n";
                        echo "\n";

                        $line[] = $word . "|" . $mean;
                    }
                }
            }
        }

        print_r($line);

        $file = "/var/www/html/BrainLog/public/mySetting/kotowaza.data";
        \file_put_contents($file, implode("\n", $line));

    }
}
