<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '勤務時間')

@section('content')
    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <div>
        <div>
        （SBC勤務表からコピーする）<br>
        201710<br>
        //                                                        <br>
        13:00                    21:30                    1:00                <br>
        9:30                    21:00                    1:00                <br>
        </div>
        <form method="POST" action="{{ url('/other/workinput') }}" id="form_other_workinput">
            {{ csrf_field() }}

            <div>
                <textarea name="work" id="ta_work"></textarea>
            </div>

        </form>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_input").click(function (){
        $("#form_other_workinput").submit();
    });

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 100;
    var divBottom = 150;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#ta_work").width(divWidth);
    $("#ta_work").height(divHeight);
    $("#ta_work").css("margin-left" , divLeft);
    -->
    </script>

@stop
