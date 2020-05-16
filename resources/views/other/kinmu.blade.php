<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$link_prevMonth = "/other/" . $prevMonth . "/kinmu";
$link_nextMonth = "/other/" . $nextMonth . "/kinmu";

$ThisMonth = (date("Y-m") == $thisMonthYear . "-" . $thisMonthMonth) ? 1 : 0;
?>

@extends('layouts.brain')

@section('title', '勤務時間')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

	<div id="calenderDiv">
	    <div>
	    	{{ $thisMonthYear }} - {{ $thisMonthMonth }}

	        @if($prevMonth != 0)
	            <a href="{{ url($link_prevMonth) }}"><img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next"></a>
	        @else
	            <img src="{{ $public_path }}/img/cal_back_blank.png" class="btn_prev_next">
	        @endif

	        @if($nextMonth != 0)
	            <a href="{{ url($link_nextMonth) }}"><img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next"></a>
	        @else
	            <img src="{{ $public_path }}/img/cal_next_blank.png" class="btn_prev_next">
	        @endif

	        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">

	    </div>

		<form method="POST" action="{{ url('/other/kinmuinput') }}" id="form_kinmu_input">
			{{ csrf_field() }}
			{!! $calender !!}
		</form>

		<div id="divTotalWorkTime">{{ $totalWorkTime }}</div>

	    <div><input type="hidden" id="selectedItem"></div>

	    <div><input type="hidden" id="selectedHour" value="9"></div>
	    <div><input type="hidden" id="selectedMinute" value="30"></div>

	    <div><br><br></div>

    </div>

	<div id="selectDiv">
		<div>
			<?php
			echo "<div class='timetip' id='btn_clear' onclick='javascript:setTime(\"clear\" , \"\");' style='border : 1px solid #ff9000; color : #ff9000;'>clear</div>";
			echo "<div class='timetip' id='btn_send' style='border : 1px solid #ff9000; color : #ff9000;'>send</div>";
			echo "<br style='clear : both;'>";
			?>
		</div>

		<hr class="time_border_hr">

		<div>
		<?php
		for($i=8 ; $i<=23 ; $i++){echo "<div class='timetip' onclick='javascript:setTime(\"hour\" , \"" . $i . "\");'>" . $i . "</div>";}
		echo "<br style='clear : both;'>";
		?>
		</div>

		<hr class="time_border_hr">

		<div>
		<?php
		$_minute = ['00' , '15' , '30' , '45'];
		foreach ($_minute as $min){echo "<div class='timetip' onclick='javascript:setTime(\"minute\" , \"" . $min . "\");'>" . $min . "</div>";}
		echo "<br style='clear : both;'>";
		?>
		</div>
    <div>

	<style type='text/css'>
	<!--
	body {font-size : 30px;}
	td {font-size : 30px;}
	#calenderDiv {overflow : auto;}
	#tbl_calender td{border : 1px solid #cccccc;}
	#selectDiv {background : #ccccff;}
	.TimeTd {width : 100px; text-align : center; cursor : pointer;}
	.time_border_hr {margin : 5px 0px;}

	.timetip {
		float : left;
		background : #ffffff;
		color : #3333ff;
		font-size : 15px;
		margin : 3px;
		padding : 3px;
		font-weight : bold;
		width : 30px;
		text-align : center;
		border : 1px solid #6666ff;
		cursor : pointer;
	}

	.btn_prev_next {width : 28px; }
    #btn_article_index {cursor : pointer; margin : 2px; }
    #divTotalWorkTime {font-size : 15px; padding-left : 200px;}
	-->
	</style>

	<script type="text/javascript">
	<!--
	var Height = $(window).height();
	if ("<?=$ThisMonth?>" == 1){
		var calenderHeight = (Height * 0.7);
		var selectHeight = (Height * 0.3);
		$("#calenderDiv").height(calenderHeight);
		$("#selectDiv").height(selectHeight);

		$("#StartHourTd").width($(window).width() - 150);

		$(".TimeTd").click(function (){
			var ymdStr = "<?=$ymdStr?>";
			var ex_ymdStr = (ymdStr).split("|");
			for (var i=0 ; i<ex_ymdStr.length ; i++){
				$("#StartTime_" + ex_ymdStr[i]).css("background" , "#ffffff");
				$("#EndTime_" + ex_ymdStr[i]).css("background" , "#ffffff");
			}
			$("#" + $(this).attr('id')).css("background" , "#ffffcc");

			$("#selectedItem").val($(this).attr('id'));
		});

		function setTime(item , value){

			if ($("#selectedItem").val() == ""){
				window.alert("カレンダー未選択");
				return false;
			}

			var disptime = "";
			switch (item){
				case "hour":
					$("#selectedHour").val(value);
					disptime = $("#selectedHour").val() + ":" + $("#selectedMinute").val();
					break;
				case "minute":
					$("#selectedMinute").val(value);
					disptime = $("#selectedHour").val() + ":" + $("#selectedMinute").val();
					break;
			}
			$("#disp_" + $("#selectedItem").val()).text(disptime);
			$("#value_" + $("#selectedItem").val()).val(disptime);
		}

		$("#btn_send").click(function (){
			$("#form_kinmu_input").submit();
		});
	}else{
		$("#calenderDiv").height(Height);
		$("#selectDiv").css("display" , "none");
	}

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});
	-->
	</script>

@stop
