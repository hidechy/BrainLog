<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '職歴')

@section('content')
    <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">

    <table id='outerTable'><tr>
    <td>{!! $data !!}</td>
    <td>{!! $hanrei !!}</td>
    </tr></table>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    .dispTable td {border : 1px solid #cccccc; vertical-align : top;}
    #outerTable td {vertical-align : top;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});
    -->
    </script>

@stop
