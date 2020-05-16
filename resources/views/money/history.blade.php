<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '所持金歴史')

@section('content')

    <div>
        <img src="{{ $public_path }}/img/list.png" id="btn_money_list">
        <img src="{{ $public_path }}/img/graph.png" id="btn_money_graph">
    </div>

    {!! $data !!}

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    #btn_money_list {cursor : pointer; margin : 2px; }
    #btn_money_graph {cursor : pointer; margin : 2px; }

    .div_year {background : #339933; color : #ffffff; font-weight : bold; padding : 3px; margin : 3px;}
    #table_history td {border : 1px solid #cccccc; width : 70px; text-align : right;}
    .td_date {background : #99ff99; width : 30px;}
    .td_month {background : #99ff99;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

    $("#btn_money_graph").width(38);
    $("#btn_money_graph").click(function (){location.href = "{{ url('/money/graph') }}";});
    -->
    </script>

@stop
