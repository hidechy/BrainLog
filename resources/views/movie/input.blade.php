@extends('layouts.brain')

@section('title', '動画投入')

@section('content')
  <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>

        <form method="POST" action="{{ url('/movie/datainput') }}" id="form_movie_newentry">
            {{ csrf_field() }}

            <div><input type="text" style="padding: 5px;" name="newentry" id="newentry"></div>

        </form>

    </div>

    <div>
        <input type="button" id="btn_newentry" value="newentry">
    </div>

    <hr>

    <div>
        <form method="POST" action="{{ url('/movie/datainput') }}" id="form_movie_input">
            {{ csrf_field() }}

            <div>
<textarea name="movie" id="ta_movie">
{!! implode("\n" , $data) !!}
</textarea>
            </div>

        </form>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <style type="text/css">
    <!--
    #btn_movie_index {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--

    $("#btn_input").click(function (){
        $("#form_movie_input").submit();
    });

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 200;
    var divBottom = 30;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#ta_movie").width(divWidth);
    $("#ta_movie").height(divHeight);
    $("#ta_movie").css("margin-left" , divLeft);

    $("#ta_movie").width(divWidth);
    $("#ta_movie").height(divHeight);
    $("#ta_movie").css("margin-left" , divLeft);

    $("#btn_newentry").click(function (){
        $("#form_movie_newentry").submit();
    });

    $("#newentry").width(divWidth);
    $("#newentry").css("margin-left" , divLeft);

    -->
    </script>

@stop
