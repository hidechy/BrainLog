<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '編集確認')

@section('content')
<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

<div>
<img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
</div>

<div>
<form method="POST" action="{{ url('/article/input') }}" id="form_article_input">
{{ csrf_field() }}

<div style="font-weight : bold;">
{{ $input_year }}-{{ $input_month }}-{{ $input_day }}
<input type="hidden" name="input_year" value="{{ $input_year }}">
<input type="hidden" name="input_month" value="{{ $input_month }}">
<input type="hidden" name="input_day" value="{{ $input_day }}">
</div>

@foreach($data as $k=>$line)
<div class="article_div">

<div style="background : #ccffcc; padding : 10px;">

<table border="0" cellspacing="2" cellpadding="2">
<tr>
<td colspan="2">
<select id="num_{{ $k }}" name="num[{{ $k }}]" class="select_num">
<option></option>
@for ($i=0 ; $i<count($data) ; $i++)
<?php
$selected = ($line['num'] == $i) ? " selected" : "";
?>
<option{{ $selected }}>{{ $i }}</option>
@endfor
</select>

@if ($user == "hidechy")
<label for="hide_{{ $k }}">
<input type="checkbox" id="hide_{{ $k }}" name="hide[{{ $k }}]" value="1">hide
</label>
@endif

<label for="del_{{ $k }}">
<input type="checkbox" id="del_{{ $k }}" name="del[{{ $k }}]" value="1">del
</label>

<select id="tag_select_{{ $k }}" name="tag_select[{{ $k }}]">
<option></option>
@foreach ($tag as $_tag)
<option value="{{ $_tag }}">{{ $_tag }}</option>
@endforeach
</select>

<?php
$ex_article = explode("\n" , $line['article']);
$tagval_ = (isset($tagval[trim($ex_article[0])])) ? $tagval[trim($ex_article[0])] : "";
?>
<input type="text" id="tag_text_{{ $k }}" name="tag_text[{{ $k }}]" value="{{ $tagval_ }}">
</td>
</tr>
<tr>
<td style="width : 80px;">
<div class="div_move_btn" onclick="javascript:showMoveDate({{ $k }});">MOVE</div>
</td>
<td>
<div id="div_move_date_{{ $k }}" class="div_move_date">
<input type="date" name="move_date[{{ $k }}]">
</div>
</td>
</tr>
<tr>
<td style="width : 80px;">
<div class="div_copy_btn" onclick="javascript:showCopyDate({{ $k }});">COPY</div>
</td>
<td>
<div id="div_copy_date_{{ $k }}" class="div_copy_date">
<div id="copy_add_btn">ADD</div>
<div id="copy_add_cnt_div"><input type="text" id="copy_add_cnt" name="copy_add_cnt[{{ $k }}]"></div>
<br style="clear : both;">
</div>
</td>
</tr>
</table>

</div>

<div style="article_div">
<br>
</div>

<div style="article_div">
{!! nl2br($line['article']) !!}
<input type="hidden" id="article_{{ $k }}" name="article[{{ $k }}]" value="{{ $line['article'] }}">
</div>

</div>
@endforeach

</form>
</div>

<div>
<input type="button" id="btn_input" value="input">
</div>

<div><br><br><br></div>

<style type="text/css">
<!--
.article_div {border : 2px solid #cccccc; margin : 5px; padding : 5px; }
.select_num {width : 60px; }
.midashiTd {background : #339933; color : #ffffff; text-align : center; }
#btn_article_index {cursor : pointer; margin : 2px; }
.div_move_btn {background : #339933; color : #ffffff; text-align : center; cursor : pointer; }
.div_move_date {display : none; }
.div_copy_btn {background : #339933; color : #ffffff; text-align : center; cursor : pointer; }
.div_copy_date {display : none; }
#copy_add_btn {float : left; width : 60px; background : #3333ff; color : #ffffff; text-align : center; cursor : pointer;}
#copy_add_cnt_div {float : left;}
#copy_add_cnt {width : 60px;}
-->
</style>

<script type="text/javascript">
<!--
$("#btn_input").click(function (){
$("#form_article_input").submit();
});

$(window).keypress(function (e){
if ( e.which == 13 ) {
$("#btn_input").trigger("click")
}
} );

$("#btn_article_index").width(38);
$("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

function showMoveDate(num){
$("#div_move_date_" + num).css("display" , "block");
}

function showCopyDate(num){
$("#div_copy_date_" + num).css("display" , "block");
}

$("#copy_add_btn").click(function (){
var cnt = ($("#copy_add_cnt").val() != "") ? parseInt($("#copy_add_cnt").val()) : 0;
$("#copy_add_cnt").val(cnt + 1);
});
-->
</script>

@stop
