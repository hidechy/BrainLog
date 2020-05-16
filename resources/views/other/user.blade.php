<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', 'ユーザー入力')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <form method="POST" action="{{ url('/other/userinput') }}" id="form_user_input">
        {{ csrf_field() }}

        <div>
            <input type="text" name="user" value="{{ $user }}">
        </div>

    </form>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type='text/javascript'>
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_input").click(function (){
        $("#form_user_input").submit();
    });
    -->
    </script>

@stop
