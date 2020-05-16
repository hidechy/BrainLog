<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$inputNum = 30;
?>

@extends('layouts.brain')

@section('title', 'まとめて')

@section('content')
    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <div>
        <form method="POST" action="{{ url('/article/multiinsert') }}" id="form_article_multiinsert">
            {{ csrf_field() }}

            @for($i=0 ; $i<$inputNum ; $i++)
            <div class="insert_div">
                <table>
                    <tr>
                        <td>
                            <div>
                                <input type="date" id="insert_date_{{ $i }}" name="insert_date[{{ $i }}]">
                            </div>

                            <div style="margin : 5px;">
                                <select id="insert_tag_{{ $i }}" name="insert_tag[{{ $i }}]">
                                <option></option>
                                @foreach ($tag as $_tag)
                                <option value="{{ $_tag }}">{{ $_tag }}</option>
                                @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <textarea class="insert_article" id="insert_article_{{ $i }}" name="insert_article[{{ $i }}]"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            @endfor

            <br style="clear : both;">
        </form>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <style type="text/css">
    <!--
    .insert_div {border : 2px solid #cccccc; margin : 5px; padding : 5px; float : left; }
    .insert_div td {vertical-align : top; }
    .insert_article {width : 200px; height : 50px; }
    #btn_article_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_input").click(function (){
        var inputNum = ("{{ $inputNum }}" * 1);

        var num_insert_date = 0;
        var num_insert_tag = 0;
        var num_insert_article = 0;
        for (var i=0 ; i<inputNum ; i++){
            if ($("#insert_date_" + i).val() != ""){num_insert_date++;}
            if ($("#insert_tag_" + i).val() != ""){num_insert_tag++;}
            if ($("#insert_article_" + i).val() != ""){num_insert_article++;}
        }

        var flag = 1;
//if (num_insert_date != num_insert_tag){flag = 0;}
        if (num_insert_date != num_insert_article){flag = 0;}
//if (num_insert_tag != num_insert_article){flag = 0;}

        if (flag == 1){
            $("#form_article_multiinsert").submit();
        }else{
            window.alert("入力数が揃っていない");
            return 0;
        }
    });

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});
    -->
    </script>

@stop
