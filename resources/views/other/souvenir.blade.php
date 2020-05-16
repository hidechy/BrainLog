<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', 'お土産')

@section('content')
    <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">

    <div><a href='{{ url('/other/souvenir') }}'>reload</a></div>
    <div><a href='javascript:openSouvenir();'>souvenir dir</a></div>
    <hr>

    <form method="POST" action="{{ url('/other/souvenir') }}" enctype="multipart/form-data" id="form_souvenir_input">
        {{ csrf_field() }}
        <input type='file' name='aaaaa'>
        <input type='hidden' name='upload' value='1'>
    </form>

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
    $("#btn_input").click(function (){
        $("#form_souvenir_input").submit();
    });

    var upload = "<?=$upload?>";
    if (upload == 1){
        openSouvenir();
    }

    function openSouvenir(){
    	window.open("http://160.16.86.159/souvenir/");
    }

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});
    -->
    </script>

@stop
