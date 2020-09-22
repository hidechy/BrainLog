<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\MyClass\Utility;
use DB;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function input()
    {
        $data = [];
        $result = DB::table('t_movie')->orderBy('id')->get();

        foreach ($result as $movie){
            $data[] = $movie->movie;
        }

        return view('movie.input')
            ->with('data', $data);
    }



    public function datainput()
    {

        if (isset($_POST['newentry']) and trim($_POST['newentry']) != ""){
            DB::table('t_movie')->insert(['movie' => $_POST['newentry']]);
        }

        if (isset($_POST['movie']) and trim($_POST['movie']) != ""){

            foreach (explode("\n", trim($_POST['movie'])) as $movie){
                if (trim($movie) == ""){continue;}

                $result = DB::table('t_movie')->where('movie', '=', $movie)->get();
                if (isset($result[0])){continue;}
                
                DB::table('t_movie')->insert(['movie' => $movie]);
            }
        }

        return redirect('/movie/input');
    }



    public function api()
    {
        $data = [];
        $result = DB::table('t_movie')->orderBy('id')->get();

        foreach ($result as $k=>$movie){

            $data[$k]['movie'] = $movie->movie;
            $data[$k]['title'] = (trim($movie->title) != "" and !is_null($movie->title)) ? $movie->title : 'x';
            $data[$k]['movieflag'] = (preg_match("/youtu/", $movie->movie)) ? 1 : 0;
        }

        return view('movie.api')
            ->with('moviedata', ['data' => $data]);
    }

}
