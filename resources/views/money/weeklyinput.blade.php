<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$link_prevWeek = "/money/" . $prev_sunday . "/weeklyinput";
$link_nextWeek = "/money/" . $next_sunday . "/weeklyinput";

$link_dispBack = "/money/" . $ymd . "/weeklydisp";
?>

@extends('layouts.brain')

@section('title', '週間消費入力')

@section('content')

    <img src="{{ $public_path }}/img/souvenir.png" id="btn_other_souvenir">

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

    <form method="POST" action="{{ url('/money/weeklyinsert') }}" id="form_weekly_input">
        {{ csrf_field() }}

        {!! $inputTable !!}
        {!! $inputTable2 !!}

    </form>
    <input type="button" id="insert_btn" value="insert">

    <style type="text/css">
    <!--
    #inputTable td {border : 1px solid #cccccc;}
    .align_right {text-align : right;}
    .price_tb {width : 50px; text-align : right;}
    .koumoku_select {width : 80px;}
    .sagaku_div {background : #ffffcc; padding : 2px;}
    #inputTable2 td {border : 1px solid #cccccc;}
    .btn_prev_next {width : 28px; }
    #btn_other_souvenir {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type='text/javascript'>
    <!--
    $(".price_tb").blur(function (){
        var id = $(this).attr('id');
        var ex_id = (id).split(":");
        var ex_id_0 = (ex_id[0]).split("_");

        var total = 0;
        for (var i=0 ; i<=6 ; i++){
            var pri = (document.getElementById("price_" + ex_id_0[1] + ":" + i).value != "") ? parseInt(document.getElementById("price_" + ex_id_0[1] + ":" + i).value) : parseInt(0);
            total += pri;
        }
        var dailytotal = parseInt($("#dailytotal_" + ex_id_0[1]).text());
        var sagaku = ((dailytotal - total) > 0) ? (dailytotal - total) : 0;
        $("#sagaku_" + ex_id_0[1]).text(sagaku);
    });

    $("#insert_btn").click(function (){
        $("#form_weekly_input").submit();
    });

	$('#all_chk').change(function() {
		var hiduke_all = ($("#hiduke_all").val()).split("/");
		for (var i=0 ; i<hiduke_all.length ; i++){
			document.getElementById("change_" + hiduke_all[i]).checked = document.getElementById("all_chk").checked;
		}
	});

    $("#btn_other_souvenir").width(38);
    $("#btn_other_souvenir").click(function (){location.href = "{{ url($link_dispBack) }}";});
    -->
    </script>

@stop
