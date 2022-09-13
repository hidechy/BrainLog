<?php

use Illuminate\Database\Seeder;

class YoutubePlayUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $result = DB::table('t_youtube_data')
            ->where('special', '=', '1')
            ->get();

        foreach ($result as $v) {
            $update = [];
            $update['last_played_at'] = date("Y-m-d", strtotime($v->getdate));

            echo $v->id;
            echo "\n";
            echo $v->getdate;
            echo "\n";
            echo "\n";

            DB::table('t_youtube_data')->where('id', '=', $v->id)->update($update);

        }
    }
}
