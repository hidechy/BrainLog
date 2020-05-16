<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$wDay = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];

$prevLink = "/article/" . $prevYm . "/index";
$nextLink = "/article/" . $nextYm . "/index";

$sevenDaysAgoLink = "/article/index#" . $sevenDaysAgo;
?>

@extends('layouts.brain')

@section('title', 'BrainLog')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <table style="margin:5px;"><tr>
    <td>
        <div style='font-size:30px;font-weight:bold;color:#dba901;'>
        BrainLog
        </div>
    </td>
    <td>
        @if ($user == "hidechy")
            <img src="{{ $public_path }}/img/list.png" id="btn_money_list">
        @endif

        <img src="{{ $public_path }}/img/search.png" id="btn_article_search">

        <img src="{{ $public_path }}/img/tuning.png" id="btn_other_tuning">



<?php
/*
<img src="{{ $public_path }}/img/seiyuu.png" id="btn_other_seiyuu">
*/
?>



    </td>
    </tr></table>

    <div id='floatDiv' style='position:relative;z-index:50;'>

        <table border="0" cellspacing="2" cellpadding="2" id="tbl_calender_float">
            <tr>
                @for($i=0 ; $i<=6 ; $i++)
                    <td>{!! $wDay[$i] !!}</td>
                @endfor
            </tr>
        </table>

        <div id='topImageDiv' style='background:rgba(0,255,0,0.1);'>
            <img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next" id="btn_prev">
            <img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next" id="btn_next">
            <input type="hidden" id="ym_flag" value="{{ $ym_flag }}">

            <img src="{{ $public_path }}/img/reposit.png" id="btn_futureNews">
        </div>

    </div>

    <table border="0" cellspacing="2" cellpadding="2" id="tbl_article_calender">
        @for ($i=0 ; $i<200 ; $i++)
            <?php
            if (!empty($calDate[$i])){
                $date = $calDate[$i];

                $_workTime = (isset($WorkTime[$date])) ? $WorkTime[$date] : "";
            }else{
                if ($i<=6){
                    //月初の空白には<br>を入れる
                    $date = "<br>";
                }else{
                    break;
                }
            }
            ?>

            @if ($i%7==0)
            <tr>
            @endif

            <?php
            $bgColor = "#ffffff";
            $todayBorder = "";
            if ($date != "<br>"){
                $wNum = date("w" , strtotime($date));
                switch ($wNum){
                    case 0:
                        $bgColor = "#ffcccc";
                        break;
                    case 6:
                        $bgColor = "#ccccff";
                        break;
                }

                if (in_array($date , $holiday , true)){$bgColor = "#ffcccc";}

                $linkUrl = "/article/" . $date . "/display";

                $todayBorder = ($date == date("Y-m-d")) ? "border:2px solid #ff9000;" : "border:1px solid #cccccc;";

                $_allMoney = (isset($allMoney[$date])) ? $allMoney[$date] : "";
                $_spend = (isset($spend[$date])) ? $spend[$date] : "";
            }
            ?>

            <td style="background : {{ $bgColor }};{{ $todayBorder }}">

                @if ($sevenDaysAgo == $date)
                    <a name="{{ $date }}"></a>
                @endif

                @if ($date != "<br>")
                    <a href="{{ url($linkUrl) }}"><?php /* ★link_start★ */ ?>
                @endif

                <?php
                if ($date != "<br>"){
                    $_articleNum = (isset($article_num[$date])) ? $article_num[$date] : "";
                    list( , $month , $day) = explode("-" , $date);
                    if (($day * 1) == 1){$date = "<div style='font-size : 20px;'>" . $month . "</div>";}
                }
                echo $date;
                ?>

                @if ($date != "<br>")
                    </a><?php /* ★link_end★ */ ?>

	                @if (isset($_workTime))
	                	@if (trim($_workTime) != "")
			                <div>
			                <div style="float : left; background : #000080; color : #ffffff; padding : 2px;">{{ $_workTime }}</div>
			                <br style="clear : both;">
			                </div>
			            @endif
	                @endif

                    <?php
                    if ($user == "hidechy"){
                    	if ($useDevice == "pc"){
	                        if (trim($_allMoney) != ""){
	                            echo "<div>" . number_format($_allMoney) . "円</div>";
	                        }

	                        if (trim($_spend) != ""){
	                            echo "<div>" . number_format($_spend) . "円</div>";
	                        }
                    	}
                    }
                    ?>

                    <div>{{ $_articleNum }}</div>
                @endif

            </td>

            @if ($i%7==6)
            </tr>
            @endif

        @endfor
    </table>

    <style type="text/css">
    <!--
    #tbl_article_calender td {vertical-align : top; word-wrap : break-word; }
    #tbl_calender_float {background : #339933; }
    #tbl_calender_float td {border : 1px solid #ffffff; text-align : center; font-weight : bold; color : #ffffff;}
    #topImageDiv {padding : 3px; }
    .btn_prev_next {width : 28px; cursor : pointer; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    #btn_other_tuning {cursor : pointer; margin : 2px; }
    #btn_article_search {cursor : pointer; margin : 2px; }
    #btn_other_seiyuu {cursor : pointer; margin : 2px; }
    #btn_futureNews {width : 20px; cursor : pointer;}
    -->
    </style>

    <script type='text/javascript'>
    <!--
    var oneWidth = Math.floor($(window).width() / 7);
    var oneHeight = Math.floor($(window).height() / 5);
    $("#tbl_article_calender td").width(oneWidth);
    $("#tbl_article_calender td").height(oneHeight);

    $("#tbl_calender_float td").width(oneWidth);

    $("#btn_prev").click(function (){
        location.href = "{{ url($prevLink) }}";
    });

    $("#btn_next").click(function (){
        location.href = "{{ url($nextLink) }}";
    });

    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

    $( document ).ready( function (){
        if ($("#ym_flag").val() == 0){
            location.href = "{{ url($sevenDaysAgoLink) }}";
        }
    });

    $("#btn_other_tuning").width(38);
    $("#btn_other_tuning").click(function (){location.href = "{{ url('/other/tuning') }}";});

	$("#btn_article_search").width(38);
    $("#btn_article_search").click(function (){location.href = "{{ url("/article/search") }}";});

    $("#btn_other_seiyuu").width(38);
    $("#btn_other_seiyuu").click(function (){location.href = "{{ url('/other/seiyuu') }}";});

    $("#btn_futureNews").click(function (){location.href = "{{ url('/article/future') }}";});
    -->
    </script>

@stop
