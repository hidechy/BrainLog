<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$wDay = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];
$youbi = $wDay[date("w" , strtotime($dispdate))];

$linkUrl = "/article/" . $dispdate . "/display";
?>

@extends('layouts.brain')

@section('title', '検索結果')

@section('content')
    <script type="text/javascript" src="{{ $public_path }}/js/jQueryRotate.js"></script>

    <table style="margin:5px;"><tr>
    <td>
        <div style='font-size:30px;font-weight:bold;'>
        {{ $dispdate }}
        <span style="font-size:15px;">（{{ $youbi }}）</span>
        </div>
    </td>
    <td>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
        <img src="{{ $public_path }}/img/page.png" id="btn_article_display">
    </td>
    </tr></table>

	<div id="div_calender_jump">
		<input type="date" name="calender_date" id="calender_date">
		<input type="button" id="btn_calenderjump" value="jump">
	</div>

    <div id="div_photo_jump">
        <img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next" id="btn_prev">
        <img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next" id="btn_next">

        <input type="hidden" id="prevDate" value="{{ $prevDate }}">
        <input type="hidden" id="nextDate" value="{{ $nextDate }}">

        <form method="POST" action="{{ url('/article/photojump') }}" id="form_article_photojump" style="display : inline;">
            {{ csrf_field() }}

            <select id="select_photo_date" name="jumpdate">
                <option></option>
                @foreach ($photoDate as $pDate)
                    <?php
                    $selected = ($dispdate == $pDate) ? " selected" : "";
                    ?>
                    <option value="{{ $pDate }}"{{ $selected }}>{{ $pDate }}</option>
                @endforeach
            </select>

            <input type="hidden" id="tb_jumpdate" name="tb_jumpdate">
        </form>
        <input type="button" id="btn_photojump" value="jump">
    </div>

    <div id="div_photo_upload">
        <form method="POST" action="{{ url('/article/photoupload') }}" id="form_article_photoupload" style="display : inline;" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="dispdate" value="{{ $dispdate }}">
            <input type="file" name="image[]" multiple="multiple" id="file_photo_upload">
            <div>
                <label for="return_here"><input type="checkbox" name="return_here" id="return_here" value="1">この画面に戻る</label>
            </div>
        </form>
        <input type="button" id="btn_photoupload" value="upload">
    </div>

    <div id="div_photo_disp">

        <form method="POST" action="{{ url('/article/photoedit') }}" id="form_article_photoedit">
            {{ csrf_field() }}

            @foreach ($folderPhoto as $k=>$photo)
                <div class="photo_div" id="photo_div_{{ $k }}">
                    <img src="{!! strtr($folderPath , ['/var/www/html' => 'http://' . $_SERVER['SERVER_ADDR']]) !!}/{{ $photo }}" class="disp_photo" onclick="javascript:photoRotation('{{ $k }}');">
                    <div>
                    <?php
                    echo "<div><input type='hidden' name='photoName[" . $k . "]' value='" . $photo . "'></div>";

                    echo "<div  class='photo_delete_div'>";
                    echo "<label for='photo_delete_" . $k . "'>";
                    echo "<input type='checkbox' id='photo_delete_" . $k . "' name='photoDelete[" . $k . "]' value='1'>";
                    echo "<img src='dummy.gif' style='width : 80px; height : 1px;'>";
                    echo "</label>";
                    echo "</div>";

                    echo "<div  class='photo_order_div'>";
                    echo "<select name='photoOrder[" . $k . "]'>";
                    echo "<option></option>";
                    foreach ($folderPhoto as $k2=>$v2){
                        echo "<option value='" . $k2 . "'>" . $k2 . "</option>";
                    }
                    echo "</select>";
                    echo "</div>";
                    ?>
                    </div>
                </div>
            @endforeach
            <br style="clear : both;">

        </form>

        <input type="button" id="btn_photo_edit" value="click">
        <input type="button" id="btn_photo_rotate" value="rotate">
    </div>

    <style type="text/css">
    <!--
    #div_photo_upload {border : 1px solid #cccccc; background : #ccffcc; margin : 10px; padding : 10px;}
    #div_photo_disp {margin : 10px; padding : 10px;}
    .photo_div {float : left; margin : 3px;}
    .disp_photo {width : 100px; cursor : pointer; }
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_article_display {cursor : pointer; margin : 2px; }
    #div_photo_jump {border : 1px solid #cccccc; background : #ffccff; margin : 10px; padding : 10px;}
    .btn_prev_next {width : 28px; cursor : pointer; }
    .photo_delete_div {text-align : center; background : #ccccff;}
    .photo_order_div {text-align : center; background : #ccffcc;}
    #div_calender_jump{border : 1px solid #cccccc; background : #ffffcc; margin : 10px; padding : 10px;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_photoupload").click(function (){
        var file_photo_upload = $("#file_photo_upload").val();

        if (file_photo_upload == ""){
            window.alert("no file.");
            return false;
        }else{
            $("#form_article_photoupload").submit();
        }
    });

    function photoRotation(num){
        $("#photo_div_" + num + " img").rotate(90);
    }

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_article_display").width(38);
    $("#btn_article_display").click(function (){
        location.href = "{{ url($linkUrl) }}";
    });

    $("#btn_photojump").click(function (){
        var select_photo_date = $("#select_photo_date").val();

        if (select_photo_date == ""){
            window.alert("no select.");
            return false;
        }else{
            $("#form_article_photojump").submit();
        }
    });

    $("#btn_prev").click(function (){
        var prevDate = $("#prevDate").val();

        if (prevDate == ""){
            window.alert("no date.");
            return false;
        }else{
            $("#tb_jumpdate").val(prevDate);
            $("#form_article_photojump").submit();
        }
    });

    $("#btn_next").click(function (){
        var nextDate = $("#nextDate").val();

        if (nextDate == ""){
            window.alert("no date.");
            return false;
        }else{
            $("#tb_jumpdate").val(nextDate);
            $("#form_article_photojump").submit();
        }
    });

    $("#btn_photo_edit").click(function (){
        $("#form_article_photoedit").submit();
    });

    $("#btn_photo_rotate").click(function (){
        var action = "{{ url('/article/photorotate') }}";
        $("#form_article_photoedit").attr('action', action);
        $("#form_article_photoedit").submit();
    });

    $("#btn_calenderjump").click(function (){

    	var calender_date = $("#calender_date").val();
    	location.href = "/BrainLog/public/article/" + calender_date + "/photo";

















//window.alert(calender_date);





//$linkUrl = "/article/" . $dispdate . "/display";







    });
    -->
    </script>

@stop
