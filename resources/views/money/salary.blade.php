<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '給与入力')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <form method="POST" action="{{ url('/money/salaryinput') }}" id="form_salary_input">
        {{ csrf_field() }}

        <table border="0" cellspacing="2" cellpadding="2" id="tbl_salary_input">
            <tr>
                <td style="background : #339933; color : #ffffff; text-align : center;">date</td>
                <td style="background : #339933; color : #ffffff; text-align : center;">company</td>
                <td style="background : #339933; color : #ffffff; text-align : center;">salary</td>
            </tr>
            <tr>
                <td><input type="date" name="salary_date" id="salary_date"></td>
                <td><input type="text" name="salary_company" id="salary_company"></td>
                <td><input type="text" name="salary_money" id="salary_money" style="text-align : right;"></td>
            </tr>
        </table>
    </form>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <hr>

    <table border="0" cellspacing="2" cellpadding="2" id="tbl_salary_list">

        <tr>
            <td style="background : #339933; color : #ffffff; text-align : center;">date</td>
            <td style="background : #339933; color : #ffffff; text-align : center;">company</td>
            <td style="background : #339933; color : #ffffff; text-align : center;">salary</td>
        </tr>

        @foreach($data as $line)
            <tr>
                <td style="width : 80px; text-align : center;">{{ $line['year'] }}-{{ $line['month'] }}-{{ $line['day'] }}</td>
                <td style="width : 150px;">{{ $line['company'] }}</td>
                <td style="width : 150px; text-align : right;">{!! number_format($line['salary']) !!}</td>
            </tr>
        @endforeach

    </table>

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    #tbl_salary_input td {border : 1px solid #cccccc; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    #tbl_salary_list td {border : 1px solid #cccccc; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_input").click(function (){
        var tbAry = new Array();
        tbAry[0] = "salary_date";
        tbAry[1] = "salary_company";
        tbAry[2] = "salary_money";

        var sendFlag = 1;
        for (var i=0 ; i<tbAry.length ; i++){
            if ($("#" + tbAry[i]).val() == ""){
                sendFlag = 0;
            }
        }

        if (sendFlag == 1){
            $("#form_salary_input").submit();
        }else{
            window.alert("no input!!");
            return false;
        }
    });

    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});
    -->
    </script>
@stop
