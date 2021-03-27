<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class KotowazaRepair extends Command
{

    protected $signature = 'KotowazaRepair';

    protected $description = 'Command description';

    public function handle()
    {


        $str = "
望蜀（ぼうしょく）|既に隴（ろう）の国を得ているのにまた蜀の国をも望むという意味から、一つの望みを遂（と）げ、更にその上を望むこと。人の欲望というものは無限で、足ることを知らないこと。
坊主憎けりゃ袈裟まで憎い（ぼうずにくけりゃけさまでにくい）|ある人や物を憎むと、それに関係ある全てのものも憎く思われるということ。
墨守（ぼくしゅ）|城や領地などを頑固に守り通すこと。転じて、古い習慣や自説などを堅く守って変えないこと。例：「旧習を墨守する」
仏作って魂入れず（ほとけつくってたましいいれず）|努力して物事を殆ど成し遂げながら、最も肝要な一事が抜け落ちているということ。肝心な一点が抜けていたら、完成したとは言えない。
仏の顔も三度（ほとけのかおもさんど）|いかに慈悲深い仏様といえども、その顔を三度も撫でられれば腹を立てるという意味で、どんなに温厚な人でも、無法なことを何度もされれば、終（しま）いには怒る。
";

        $ex_str = explode("\n", $str);
        foreach ($ex_str as $v) {
            if (trim($v) == "") {
                continue;
            }

            echo trim($v);
            echo "\n\n";

            preg_match("/(.+)（(.+)）\|/", trim($v), $m);

            $update = [];
            $update['head'] = "ほ";
            $update['yomi'] = trim($m[2]);

print_r($update);

            DB::table('t_kotowaza')->where('word', '=', trim($m[1]))->update($update);

        }
    }
}
