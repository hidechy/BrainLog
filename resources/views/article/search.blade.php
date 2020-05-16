<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '検索')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
        <img src="{{ $public_path }}/img/tag.png" id="btn_other_tag">
    </div>

    <div>
        <form method="POST" action="{{ url('/article/searchresult') }}" id="form_article_search" style="display : inline;">
            {{ csrf_field() }}
            <input type="text" name="word" id="word">
        </form>
        <input type="button" id="btn_search" value="search">
    </div>

    <hr>

    @foreach ($tag_article as $tag=>$cnt)
    	<div class="div_tag">
    		<a href="{{ url('/article/' . $tag . '/searchresult') }}">
    			{{ $tag }}({{ $cnt }})
    		</a>
    	</div>
    @endforeach

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_other_tag {cursor : pointer; margin : 2px; }
    .div_tag {border : 1px solid #cccccc; margin : 5px; padding : 5px;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_search").click(function (){
    	if ($("#word").val() == ""){
    		window.alert("no word");
    		return false;
    	}

        $("#form_article_search").submit();
    });

    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_other_tag").width(38);
    $("#btn_other_tag").click(function (){location.href = "{{ url('/other/tag') }}";});
    -->
    </script>

@stop
