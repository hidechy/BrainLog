<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', 'タグ入力')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <form method="POST" action="{{ url('/other/taginput') }}" id="form_tag_input">
        {{ csrf_field() }}

            <div>
<textarea name="tag" id="ta_tag">
{!! implode("\n" , $tag) !!}
</textarea>
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
        $("#form_tag_input").submit();
    });

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 100;
    var divBottom = 30;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#ta_tag").width(divWidth);
    $("#ta_tag").height(divHeight);
    $("#ta_tag").css("margin-left" , divLeft);
    -->
    </script>

@stop
