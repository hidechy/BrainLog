<?php

namespace App\Http\Controllers\Anken;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;
use Input;

class AnkenController extends Controller
{

    /**
     *
     */
    public function index()
    {
        return view('anken.index');
    }

    /**
     *
     */
    public function create()
    {
        return view('anken.create');
    }

    /**
     *
     */
    public function store()
    {

        $_ex_anken = explode("\n", $_POST['anken']);

        $here_01 = 0;//受信日時：
        $here_02 = 0;//送信者：
        $here_03 = 0;//案件：
        $here_04 = 0;//概要：
        $here_05 = 0;//スキル：
        $here_06 = 0;//場所：
        $here_07 = 0;//期間：
        $here_08 = 0;//募集人数：
        $here_09 = 0;//単金：
        $here_10 = 0;//精算：
        $here_11 = 0;//面談：
        $here_12 = 0;//end

        foreach ($_ex_anken as $k => $v) {
            if (preg_match("/受信日時：/", trim($v))) {
                $here_01 = $k;
            }

            if (preg_match("/送信者：/", trim($v))) {
                $here_02 = $k;
            }

            if (preg_match("/案件：/", trim($v))) {
                $here_03 = $k;
            }

            if (preg_match("/概要：/", trim($v))) {
                $here_04 = $k;
            }

            if (preg_match("/スキル：/", trim($v))) {
                $here_05 = $k;
            }

            if (preg_match("/場所：/", trim($v))) {
                $here_06 = $k;
            }

            if (preg_match("/期間：/", trim($v))) {
                $here_07 = $k;
            }

            if (preg_match("/募集人数：/", trim($v))) {
                $here_08 = $k;
            }

            if (preg_match("/単金：/", trim($v))) {
                $here_09 = $k;
            }

            if (preg_match("/精算：/", trim($v))) {
                $here_10 = $k;
            }

            if (preg_match("/面談：/", trim($v))) {
                $here_11 = $k;
            }

            if (preg_match("/end/", trim($v))) {
                $here_12 = $k;
            }
        }

        $insert = [];

        //received_at
        $ary = [];
        for ($i = ($here_01 + 1); $i < $here_02; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['received_at'] = implode("\n", $ary);
        }

        //send_from
        $ary = [];
        for ($i = ($here_02 + 1); $i < $here_03; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['send_from'] = implode("\n", $ary);
        }

        //anken
        $ary = [];
        for ($i = ($here_03 + 1); $i < $here_04; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['anken'] = implode("\n", $ary);
        }

        //gaiyou
        $ary = [];
        for ($i = ($here_04 + 1); $i < $here_05; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['gaiyou'] = implode("\n", $ary);
        }

        //skill
        $ary = [];
        for ($i = ($here_05 + 1); $i < $here_06; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['skill'] = implode("\n", $ary);
        }

        //basho
        $ary = [];
        for ($i = ($here_06 + 1); $i < $here_07; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['basho'] = implode("\n", $ary);
        }

        //kikan
        $ary = [];
        for ($i = ($here_07 + 1); $i < $here_08; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['kikan'] = implode("\n", $ary);
        }

        //boshuu
        $ary = [];
        for ($i = ($here_08 + 1); $i < $here_09; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['boshuu'] = implode("\n", $ary);
        }

        //tankin
        $ary = [];
        for ($i = ($here_09 + 1); $i < $here_10; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['tankin'] = implode("\n", $ary);
        }

        //seisan
        $ary = [];
        for ($i = ($here_10 + 1); $i < $here_11; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['seisan'] = implode("\n", $ary);
        }

        //mendan
        $ary = [];
        for ($i = ($here_11 + 1); $i < $here_12; $i++) {
            if (trim($_ex_anken[$i]) != "") {
                $ary[] = trim($_ex_anken[$i]);
            }
        }
        if (!empty($ary[0])) {
            $insert['mendan'] = implode("\n", $ary);
        }

        DB::table('t_anken')->insert($insert);

        return redirect("/anken/index");







        /*
                mysql> desc t_anken;
        +------------------+----------+------+-----+---------+----------------+
        | Field            | Type     | Null | Key | Default | Extra          |
            +------------------+----------+------+-----+---------+----------------+
        | id               | int(11)  | NO   | PRI | NULL    | auto_increment |


        | received_at      | datetime | YES  |     | NULL    |                |
        | send_from        | text     | YES  |     | NULL    |                |
        | anken            | text     | YES  |     | NULL    |                |
        | gaiyou           | text     | YES  |     | NULL    |                |
        | skill            | text     | YES  |     | NULL    |                |
        | basho            | text     | YES  |     | NULL    |                |
        | kikan            | text     | YES  |     | NULL    |                |
        | boshuu           | text     | YES  |     | NULL    |                |
        | tankin           | text     | YES  |     | NULL    |                |
        | seisan           | text     | YES  |     | NULL    |                |
        | mendan           | text     | YES  |     | NULL    |                |





        | hope_mendan_date | text     | YES  |     | NULL    |                |
        | fix_mendan_date  | text     | YES  |     | NULL    |                |
        | kekka            | char(1)  | YES  |     | NULL    |                |
        | bikou            | text     | YES  |     | NULL    |                |
        +------------------+----------+------+-----+---------+----------------+
        16 rows in set (0.00 sec)

        mysql>
        mysql>
        */


    }

    /**
     *
     */
    public function edit()
    {

    }

    /**
     *
     */
    public function update()
    {

    }

}
