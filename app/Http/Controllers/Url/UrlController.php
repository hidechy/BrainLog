<?php

namespace App\Http\Controllers\Url;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;
use Input;

class UrlController extends Controller
{

    /**
     *
     */
    public function index()
    {
        $result = DB::table('t_urllist')->where('flag', '=', '1')->get();

        echo "<pre>";
        print_r($result);
        echo "</pre>";







//        return view('url.index');
    }


}
