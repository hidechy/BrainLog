<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '休日入力')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <form method="POST" action="{{ url('/other/holidayinput') }}" id="form_holiday_input">
        {{ csrf_field() }}

        <div>
<textarea name="holiday" id="ta_holiday">
<?php
echo implode("\n" , $holiday);
?>
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

    <script type="text/javascript">
    <!--
    $("#btn_input").click(function (){
        $("#form_holiday_input").submit();
    });

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 100;
    var divBottom = 30;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#ta_holiday").width(divWidth);
    $("#ta_holiday").height(divHeight);
    $("#ta_holiday").css("margin-left" , divLeft);
    -->
    </script>

@stop
