<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '所持金入力')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <?php $dispFlag = "none"; ?>

    <div style="display : {{ $dispFlag }};">
    <div id="date_yesterday">{{ $yesterday }}</div>

    <div>
    @foreach($date_diff as $k=>$v)
    <input type="text" id="dd_{{ $k }}" value="{{ $v }}">
    @endforeach
    </div>

    <hr>

    @foreach($ob_yen as $k=>$v)
    <div><input type="text" id="ob_{{ $k }}" value="{{ $v }}"></div>
    @endforeach
    <?php
    $key_yen = array_keys($ob_yen);
    echo "<input type='text' id='key_yen' value='" . implode("/" , $key_yen) . "'>";

    $yen_kind = [];
    foreach ($key_yen as $v){
        $yen_kind[] = strtr($v , ['yen_' => '']);
    }
    echo "<input type='text' id='yen_kind' value='" . implode("/" , $yen_kind) . "'>";
    ?>
    </div>

    <form method="POST" id="form_money_input">
        {{ csrf_field() }}
        <div>
            <div>
                入力日付：<span id="span_input_date">{{ $today }}</span>
            </div>
            <div>
                <div style="display : {{ $dispFlag }};">
                    <input type="text" id="input_date" name="input_date" value="{{ $today }}">
                    <input type="text" id="today_yesterday" value="today">
                </div>
            </div>
            <div>
                （前回：{{ $ob_date }}） {!! number_format($ob_sum) !!} 円
                <div style="display : {{ $dispFlag }};">
                    <input type="text" id="ob_date" name="ob_date" value="{{ $ob_date }}">
                    <input type="text" id="ob_sum" name="ob_sum" value="{{ $ob_sum }}">
                </div>
            </div>
            <div>
                [合計]　<span id="nowSum">0</span>　　[差額]　<span id="nowDiff">0</span>
            </div>
        </div>

        <div>
            <input type="button" id="btn_set_yesterday" value="日付-1">
            <input type="button" id="btn_last_money" value="最終">
        </div>

        <table border="0" cellspacing="2" cellpadding="2" id="tbl_money_input">
        <?php
        $i=0;
        $yenType = [];
        ?>
        @foreach($ob_yen as $k=>$v)
            @if($i%2==0)
                <tr>
            @endif
		            <td class="yen_midashi">
			            <?php
			            $yt = strtr($k , ['yen_' => '']);
			            $yenType[] = $yt;
			            ?>
			            {{ $yt }}
		            </td>
		            <td><input type="number" id="{{ $k }}" name="{{ $k }}" class="number_yen" onblur="javascript:calcNowSum();"></td>
            @if($i%2==1)
                </tr>
            @endif
            <?php
            $i++;
            ?>
        @endforeach
        </table>

    </form>

    <div style="margin : 5px 0px;">
	    <input type="text" id="tb_jogai_money">
	    <input type="button" value="除外" id="btn_jogai_money">
	    <?php
	    echo "<input type='hidden' id='yenType' value='" . implode("/" , $yenType) . "'>";
	    ?>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
        <input type="button" id="btn_clear" value="clear">
    </div>

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    #tbl_money_input td {border : 1px solid #cccccc; }
    .yen_midashi {background : #ccccff; }
    .number_yen {width : 70px; text-align : right; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    #tb_jogai_money {width : 100px; text-align : right; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_set_yesterday").click(function (){
        $("#today_yesterday").val("yesterday");
        $("#input_date").val($("#date_yesterday").text());
        $("#span_input_date").html($("#date_yesterday").text());
        $("#span_input_date").css("color" , "#ff0000");
    });

    $("#btn_last_money").click(function (){
        var ex_key_yen = ($("#key_yen").val()).split("/");
        for (var i=0 ; i < ex_key_yen.length ; i++){
            $("#" + ex_key_yen[i]).val($("#ob_" + ex_key_yen[i]).val());
        }
    });

    $("#btn_input").click(function (){
        var input_date = $("#input_date").val();
        var ob_date = $("#ob_date").val();

        if (input_date == ob_date){
            window.alert("input yet!!");
            return false;
        }

        var flag = $("#dd_" + $("#today_yesterday").val()).val();
        var action = (flag == 1) ? "{{ url('/money/multiinput') }}" : "{{ url('/money/singleinput') }}";
        $("#form_money_input").attr('action', action);
        $("#form_money_input").submit();
    });

    $("#btn_clear").click(function (){
        location.href = "{{ url('/money/input') }}";
    });

    function calcNowSum()
    {
        var ex_key_yen = ($("#key_yen").val()).split("/");
        var ex_yen_kind = ($("#yen_kind").val()).split("/");
        var sum = 0;
        for (var i=0;i<ex_key_yen.length;i++){sum += parseInt(ex_yen_kind[i] * $("#"+ex_key_yen[i]).val());}
        $("#nowSum").html(sum);

        var sagaku = parseInt($("#ob_sum").val()) - sum;
        $("#nowDiff").html(sagaku);
    }

    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

	$("#btn_jogai_money").click(function (){
	    if ($("#tb_jogai_money").val() != ""){
	        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	        $.ajax({
	            url: '{{ $appUrl }}/money/moneyjogai',
	            type: 'POST',
	            data: {
	            	_token: CSRF_TOKEN ,
	            	jogai_money: $("#tb_jogai_money").val() , 
	            	yenType : $("#yenType").val()
	            },
	            success: function (data) {
	                if (data != ""){
	                	var ex_data = (data).split("/");
	                	for (var i=0 ; i<ex_data.length ; i++){
	                		if (ex_data[i] == ""){continue;}
	                		var ex_v = (ex_data[i]).split("|");
	                		var nowval = ($("#" + ex_v[0]).val() != "") ? $("#" + ex_v[0]).val() : 0;
	                		$("#" + ex_v[0]).val(parseInt(nowval) + parseInt(ex_v[1]));
	                	}
	                	$("#nowDiff").html(parseInt($("#nowDiff").html()) - parseInt($("#tb_jogai_money").val()));
	                }
	            }
	        });
	    }
	});
    -->
    </script>

@stop
