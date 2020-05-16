<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$wDay = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];
$youbi = $wDay[date("w" , strtotime($dispdate))];
?>

@extends('layouts.brain')

@section('title', '記事編集')

@section('content')
  <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <?php $dispFlag = "none"; ?>

    <table style="margin:5px;"><tr>
    <td>
        <div style='font-size:30px;font-weight:bold;'>
        {{ $dispdate }}
        <span style="font-size:15px;">（{{ $youbi }}）</span>
        </div>
    </td>
    <td>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </td>

<td>
<button onclick="javascript:doLineMarker();">強調</button>
</td>

    </tr></table>

    <div>
        <form method="POST" action="{{ url('/article/confirm') }}" id="form_article_input">
            {{ csrf_field() }}

            <div style="display : {{ $dispFlag }};">
            <input type="text" name="date" value="{{ $dispdate }}">
            </div>

            <div>
<textarea name="article" id="ta_article">
{!! implode("\n@\n" , $data) !!}
</textarea>
            </div>

        </form>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_input").click(function (){
        $("#form_article_input").submit();
    });

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 100;
    var divBottom = 30;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#ta_article").width(divWidth);
    $("#ta_article").height(divHeight);
    $("#ta_article").css("margin-left" , divLeft);

    function doLineMarker(){
      var textarea = document.querySelector('textarea');
      var pos_start = textarea.selectionStart;
      var pos_end = textarea.selectionEnd;
      var val = textarea.value;
      var range = val.slice(pos_start, pos_end);
      var beforeNode = val.slice(0, pos_start);
      var afterNode = val.slice(pos_end);
      var insertNode = "<b style='background:#ffff99'>" + range + "</b>";
      textarea.value = beforeNode + insertNode + afterNode;
    }
    -->
    </script>

@stop
