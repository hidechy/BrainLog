<?php

//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$bankAry = ['bank_a' , 'bank_b' , 'bank_c' , 'bank_d' , 'pay_a' , 'pay_b', 'pay_c'];
?>

@extends('layouts.brain')

@section('title', '口座入力')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <table border="0" cellpadding="2" cellspacing="2" id="tbl_bank_list">
        <tr>
            <td>
                <div id="div_bank_list">
                    <?php
                    echo "<pre>";
                    print_r($data);
                    echo "</pre>";
                    ?>
                </div>
            </td>
            <td style="width : 700px;">
                <form method="POST" action="{{ url('/money/bankinput') }}" id="form_bank_input">
                    {{ csrf_field() }}

                    <table border="0" cellpadding="2" cellspacing="2" id="tbl_bank_input">
                        @for($i=0 ; $i<15 ; $i++)
                            <tr>
                                <td style="width : 300px;">
                                    @foreach($bankAry as $v)
                                        <label for="{{ $v }}_{{ $i }}">
                                        <input type="radio" id="{{ $v }}_{{ $i }}" name="bankradio[{{ $i }}]" value="{{ $v }}">{{ $v }}
                                        </label>

                                        @if ($v == "bank_d")
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td style="width : 140px;"><input type="date" name="bankdate[{{ $i }}]"></td>
                                <td style="width : 150px;"><input type="text" name="bankmoney[{{ $i }}]" style="text-align : right;"></td>
                            </tr>
                        @endfor
                    </table>
                </form>

                <div>
                    <input type="button" id="btn_input" value="input">
                    <input type="button" id="btn_clear" value="clear">
                </div>

            </td>
        </tr>
    </table>

    <style type="text/css">
    <!--
    #tbl_bank_list td {border : 1px solid #cccccc; vertical-align : top; }
    #div_bank_list {width : 320px; height : 500px; overflow : auto; }
    #tbl_bank_input td {border : 1px solid #cccccc; }
    #btn_money_list {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

    $("#btn_input").click(function (){
        $("#form_bank_input").submit();
    });

    $("#btn_clear").click(function (){
        location.href = "{{ url('/money/bank') }}";
    });
    -->
    </script>

@stop
