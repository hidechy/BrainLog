<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '複数日入力')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <div id="nowDiff">{{ $now_diff }}</div>
    <div id="culcDiff">{{ $now_diff }}</div>

    @for($j=0 ; $j < 5 ; $j++)
    <div>
        <form method="POST" action="{{ url('/money/multiinsert') }}" id="form_money_insert">
            {{ csrf_field() }}

            <input type="hidden" name="ob_sum" value="{{ $ob_sum }}">
            <input type="hidden" name="YENTYPE" value="{{ $YENTYPE }}">
            <input type="hidden" name="inputYen" value="{{ $inputYen }}">

            <div>[<span id="span_opentext_{{ $j }}" class="open_span" onclick="javascript:boxOpen({{ $j }});">open</span>]</div>
            <div id="div_open_{{ $j }}" style="display : none;">
                <table border="0" cellspacing="2" cellpadding="2" class="tbl_spend">
                    @for($i=1 ; $i <= 10 ; $i++)
                        <?php $lineno = ($j*10) + $i; ?>
                        <tr>
                            <td>
                                <select id="date_select_{{ $lineno }}" name="date_select[{{ $lineno }}]">
                                    <option></option>
                                    @foreach($select_date as $v)
                                        <option value="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" style="width : 100px;"></td>
                            <td><input type="text" id="spend_money_{{ $lineno }}" name="spend_money[{{ $lineno }}]" class="spend_money" onblur="javascript:calcNowSum();"></td>
                        </tr>
                    @endfor
                </table>
            </div>
        </form>
    </div>
    @endfor

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    .open_span {cursor : pointer; }
    .tbl_spend td {border : 1px solid #cccccc; }
    .spend_money {width : 70px; text-align : right; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    function boxOpen(no){
        var flag = $("#span_opentext_" + no).html();
        if (flag == "open"){
            $("#span_opentext_" + no).html("close");
            $("#div_open_" + no).css("display" , "block");
        }else{
            $("#span_opentext_" + no).html("open");
            $("#div_open_" + no).css("display" , "none");
        }
    }

    function calcNowSum()
    {
        var sum = 0;
        for (var i=1 ; i<=50 ; i++){
            if ($("#spend_money_" + i).val() == ""){continue;}
            sum += parseInt($("#spend_money_" + i).val());
        }

        if (sum > 0){
            var nowDiff = $("#nowDiff").html();
            $("#culcDiff").html(parseInt(nowDiff) - parseInt(sum));
        }
    }

    $("#btn_input").click(function (){
        if (parseInt($("#culcDiff").html()) > 0){
            window.alert("no finish!!");
        }else{
            var j=0;
            for (var i=1 ; i<=50 ; i++){
                if ($("#spend_money_" + i).val() != ""){
                    j++;
                }
            }

            var sendFlag = 1;
            for (var i=1 ; i<=j ; i++){
                if ($("#date_select_" + i).val() == ""){
                    sendFlag = 0;
                }
            }

            if (sendFlag == 0){
                window.alert("date no select!!");
            }else{
                $("#form_money_insert").submit();
            }
        }
    });

    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});
    -->
    </script>

@stop
