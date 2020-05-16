<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$link_prevMonth = "/money/" . $prevMonth . "/graph";
$link_nextMonth = "/money/" . $nextMonth . "/graph";
?>

@extends('layouts.brain')

@section('title', 'グラフ')

@section('content')
    <!--[if IE]><script type="text/javascript" src="{{ $public_path }}/js/html5jp/excanvas/excanvas.js"></script><![endif]-->
    <script type="text/javascript" src="{{ $public_path }}/js/html5jp/graph/line.js"></script>

    <table border="0"><tr><td>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
        <img src="{{ $public_path }}/img/history.png" id="btn_money_history">
    </td></tr></table>
    <hr>

    <table><tr>
    <td>
    <div>{{ $YM }}</div>
    </td>
    <td>
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
    </td>
    </tr></table>

    <table><tr>
    <td style='vertical-align : top;'>
    <div id="canvasDiv"></div>
    </td>
    <td style='vertical-align : top;'>
    <table border='0' cellspacing='2' cellpadding='2' id='moneyDispDiv'>
    <?php
    foreach ($data as $k=>$v){
        echo "<tr>";
        echo "<td>" . $k . "</td>";
        echo "<td>" . number_format($v) . "</td>";
        echo "</tr>";
    }
    ?>
    </table>
    </td>
    </tr></table>

    <style type="text/css">
    <!--
    .btn_prev_next {width : 28px; }
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_money_history {cursor : pointer; margin : 2px; }
    #moneyDispDiv td {border : 1px solid #cccccc;}
    -->
    </style>

    <script type="text/javascript">
    window.onload = function() {
        var divWidth = ($(window).width() - 300);
        $("#canvasDiv").html("<canvas id='sample' width='" + divWidth + "' height='500'></canvas>");

        var lg = new html5jp.graph.line("sample");
        if( ! lg ) { return; }
        var items = [
            [,<?=$graphdata?>]
        ];

        var params = {
            legend: false,
            yMax: {{ $max }},
            yMin: {{ $min }},
            lineWidth: 1,
            dotRadius: 3,
            dotType: "disc",
            backgroundColor : "#eeeeff",
            gbackgroundColor : "#ffffff",
            gGradation : false,
            hLineType : "dotted",
            hLineColor : "#333333",
            dLabel : true,
            dLabelColor : "#0000ff",
            dLabelFontSize : "10px",
        };

        lg.draw(items , params);
    };

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_money_history").width(38);
    $("#btn_money_history").click(function (){location.href = "{{ url('/money/history') }}";});
    </script>

@stop
