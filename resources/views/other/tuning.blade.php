<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', 'チューニング')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
        <img src="{{ $public_path }}/img/holiday.png" id="btn_other_holiday">
<!--img src="{{ $public_path }}/img/user.png" id="btn_other_user"-->
        <img src="{{ $public_path }}/img/tag.png" id="btn_other_tag">
        <img src="{{ $public_path }}/img/taging.png" id="btn_other_taging">
        <img src="{{ $public_path }}/img/multi.png" id="btn_other_multi">
        <img src="{{ $public_path }}/img/work.png" id="btn_other_work">
<!--img src="{{ $public_path }}/img/workmulti.png" id="btn_other_workmulti"-->
        <img src="{{ $public_path }}/img/shokureki.png" id="btn_other_shokureki">
        <img src="{{ $public_path }}/img/souvenir.png" id="btn_other_souvenir">
        <img src="{{ $public_path }}/img/old.png" id="btn_article_old">

        <img src="{{ $public_path }}/img/route.png" id="btn_other_route">

<?php
/*
<img src="{{ $public_path }}/img/affi.png" id="btn_affi_index">
        <img src="{{ $public_path }}/img/temple.png" id="btn_temple_index">

*/
?>

    </div>

    <hr>
    <div><a href="{{ url('/other/youtubedatalist') }}">youtubedatalist</a></div>

    <hr>
    <div><a href="{{ url('/other/walkdatalist') }}">walkdatalist</a></div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_other_holiday {cursor : pointer; margin : 2px; }
    #btn_other_user {cursor : pointer; margin : 2px; }
    #btn_other_tag {cursor : pointer; margin : 2px; }
    #btn_other_multi {cursor : pointer; margin : 2px; }
    #btn_other_work {cursor : pointer; margin : 2px; }
    #btn_other_workmulti {cursor : pointer; margin : 2px; }
    #btn_other_shokureki {cursor : pointer; margin : 2px; }
    #btn_other_souvenir {cursor : pointer; margin : 2px; }
    #btn_article_old {cursor : pointer; margin : 2px; }
    #btn_affi_index {cursor : pointer; margin : 2px; }
    #btn_temple_index {cursor : pointer; margin : 2px; }
    #btn_other_taging {cursor : pointer; margin : 2px; }
    #btn_other_route {cursor : pointer; margin : 2px; }
    -->
    </style>

    <?php
        $tagingUrl = "/article/" . date("Y-m") . "/taging";
    ?>

    <script type='text/javascript'>
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_other_holiday").width(38);
    $("#btn_other_holiday").click(function (){location.href = "{{ url('/other/holiday') }}";});

    $("#btn_other_user").width(38);
    $("#btn_other_user").click(function (){location.href = "{{ url('/other/user') }}";});

    $("#btn_other_tag").width(38);
    $("#btn_other_tag").click(function (){location.href = "{{ url('/other/tag') }}";});

    $("#btn_other_multi").width(38);
    $("#btn_other_multi").click(function (){location.href = "{{ url('/article/multiinput') }}";});

    $("#btn_other_work").width(38);
    $("#btn_other_work").click(function (){location.href = "{{ url('/other/kinmu') }}";});

    $("#btn_other_workmulti").width(38);
    $("#btn_other_workmulti").click(function (){location.href = "{{ url('/other/work') }}";});

    $("#btn_other_shokureki").width(38);
    $("#btn_other_shokureki").click(function (){location.href = "{{ url('/other/shokureki') }}";});

    $("#btn_other_souvenir").width(38);
    $("#btn_other_souvenir").click(function (){location.href = "{{ url('/other/souvenir') }}";});

    $("#btn_article_old").width(38);
    $("#btn_article_old").click(function (){location.href = "http://160.16.86.159/aaaaa/";});

    $("#btn_affi_index").width(38);
    $("#btn_affi_index").click(function (){location.href = "{{ url('/affi/index') }}";});

    $("#btn_temple_index").width(38);
    $("#btn_temple_index").click(function (){location.href = "{{ url('/temple/index') }}";});

    $("#btn_other_taging").width(38);
    $("#btn_other_taging").click(function (){location.href = "{{ url($tagingUrl) }}";});

    $("#btn_other_route").width(38);
    $("#btn_other_route").click(function (){location.href = "{{ url('/other/route') }}";});

    -->
    </script>

@stop
