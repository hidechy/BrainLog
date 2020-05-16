<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$link_prevWeek = "/money/" . $prev_sunday . "/weeklydisp";
$link_nextWeek = "/money/" . $next_sunday . "/weeklydisp";

$link_weeklyinput = "money/" . $ymd . "/weeklyinput";
?>

@extends('layouts.brain')

@section('title', '週間消費表示')

@section('content')
    <div>
        <img src="{{ $public_path }}/img/list.png" id="btn_money_list">
        <img src="{{ $public_path }}/img/money.png" id="btn_money_weeklyinput">
    </div>

    @if($prev == 1)
        <a href="{{ url($link_prevWeek) }}"><img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next"></a>
    @else
        <img src="{{ $public_path }}/img/cal_back_blank.png" class="btn_prev_next">
    @endif

    @if($next == 1)
        <a href="{{ url($link_nextWeek) }}"><img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next"></a>
    @else
        <img src="{{ $public_path }}/img/cal_next_blank.png" class="btn_prev_next">
    @endif

<div><a href="http://160.16.86.159/test/20190609/money.php" target="_blank">[input]</a></div>
<hr>

    {!! $dispTable !!}

<?php
echo "<hr>";
echo "<pre>";
print_r($Spend);
echo "</pre>";
echo "<hr>";
print_r($CreditItems);
?>

    <style type="text/css">
    <!--
    #dispTable td {border : 1px solid #cccccc;}
    .align_right {text-align : right;}
    .btn_prev_next {width : 28px; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    #btn_money_weeklyinput {cursor : pointer; margin : 2px; }
    .midashi {background : #6666ff; color : #ffffff; text-align : center;}
    .bg_green1 {background : #339933; color : #ffffff;}
    .bg_green2 {background : #ccffcc;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

    $("#btn_money_weeklyinput").width(38);
    $("#btn_money_weeklyinput").click(function (){location.href = "{{ url($link_weeklyinput) }}";});
    -->
    </script>

@stop
