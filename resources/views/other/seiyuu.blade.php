<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '西友入力')

@section('content')
    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    <div>
        <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    </div>

    <div>
        西友ネットスーパー<br>
        <a href="https://www.the-seiyu.com/front/contents/top/" target="_blank">https://www.the-seiyu.com/front/contents/top/</a>
    </div>

    @if ($data)
        <form method="POST" action="{{ url('/other/seiyuuinput') }}" id="form_seiyuu_input">
            {{ csrf_field() }}

            <table border="0" cellspacing="2" cellpadding="2" id="tbl_seiyuu_input">
            @foreach ($data as $k=>$v)

            <tr>
            <td><input type="checkbox" name="delete[{{ $k }}]" value="1"></td>
            <td>
                @if (isset($v['img']))
                    <img src="{{ $v['img'] }}" class="itemimg">
                @endif
            </td>
            <td>
                <div>
                    @if ($v['itemname'])
                        {{ $v['itemname'] }}　
                    @else
                    	@if (trim($v['url']) != "")
                    		＜待機中＞
                    	@endif
                    @endif
                </div>
                    @if (isset($v['price']) and trim($v['price']) != "")
                        <div>{{ $v['price'] }}円</div>
                    @endif
                <div>
                    @if (trim($v['url']) == "")
                    <input type="text" name="url[{{ $k }}]" value="{{ $v['url'] }}" class="tb_url">
                    @endif
                </div>
            </td>
            <td>
            @if (isset($v['itemid']))
                <input type="checkbox" name="article[{{ $k }}]" value="{{ $v['itemid'] }}">
            @endif
            </td>
            </tr>

            @endforeach
            </table>

        </form>

        <div>
            <input type="button" id="btn_input" value="input">
            <input type="button" id="btn_article" value="article">
        </div>

        <div><br><br><br></div>
    @endif

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    #tbl_seiyuu_input td {border : 1px solid #cccccc; }
    .itemimg {width : 50px; }
    .tb_url {width : 500px;}
    #btn_article {background : #6666ff; color : #ffffff; }
    -->
    </style>

    <script type='text/javascript'>
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_input").click(function (){
        $("#form_seiyuu_input").submit();
    });

    $("#btn_article").click(function (){
        $("#form_seiyuu_input").attr('action', "{{ url('/other/seiyuuarticle') }}");
        $("#form_seiyuu_input").submit();
    });
    -->
    </script>

@stop
