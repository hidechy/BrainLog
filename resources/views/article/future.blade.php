<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '未来の予定')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

	<div>
	    <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
	</div>

	@foreach ($article as $date=>$v)

	<?php
	$bgColor = (strtotime($date) < strtotime(date("Ymd")) + (86400 * 7)) ? "#ccccff" : "#ccffcc";
	?>

	<div class="div_date" style="background : {!! $bgColor !!}">{{ $date }}（
	<?php
	$w = date("w" , strtotime($date));
	$week = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];
	echo $week[$w];
	?>
	）
	</div>

		@foreach ($v as $v2)

			<div class="div_article">{!! nl2br($v2) !!}</div>

		@endforeach

	@endforeach

	<div><br><br><br></div>

	<style type="text/css">
	<!--
	.div_date {margin-top : 30px; padding : 5px;}
	.div_article {border : 1px solid #cccccc; padding : 5px; margin : 5px;}

    #btn_article_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});
    -->
    </script>

@stop
