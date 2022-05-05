<?php

namespace App\Http\Controllers\MoneyHistory;

use App\Http\Controllers\Controller;
use DB;

class MoneyHistoryController extends Controller
{

    public function moneyHistoryDisplay()
    {

        $data_json = '{"date":"'. trim($_GET['date']) .'"}';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'http://toyohide.work/BrainLog/api/everydaySpendSearch');
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        curl_close($ch);

        return view('moneyhistory.moneyhistory')
            ->with('data', $result['data']);
    }

}
