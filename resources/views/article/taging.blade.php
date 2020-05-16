<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$link_prevMonth = "/article/" . $prevMonth . "/taging";
$link_nextMonth = "/article/" . $nextMonth . "/taging";
?>

@extends('layouts.brain')

@section('title', 'タグ付け')

@section('content')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
    <img src="{{ $public_path }}/img/tuning.png" id="btn_other_tuning">

    <hr>

    <table border="0"><tr><td>

    <div style="font-size : 20px; font-weight : bold;">{{ $yearmonth }}</div>

    </td><td>
        @if($prevMonth != 0)
            <a href="{{ url($link_prevMonth) }}"><img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next"></a>
        @else
            <img src="{{ $public_path }}/img/cal_back_blank.png" class="btn_prev_next">
        @endif

        @if($nextMonth != 0)
            <a href="{{ url($link_nextMonth) }}"><img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next"></a>
        @else
            <img src="{{ $public_path }}/img/cal_next_blank.png" class="btn_prev_next">
        @endif

    </td></tr></table>

    <div class="my-3">
    <button class="btn btn-primary">すでにタグ付けされているものを隠す</button>
    </div>

    <form method="POST" action="{{ url('/article/taginput') }}" id="form_tag_input">
        {{ csrf_field() }}

        <?php
            foreach ($data as $date => $v){
                echo "<div>";

                    echo "<div class='dateDiv'>" . $date . "</div>";

                    foreach ($v as $num=>$v2){

                        echo "<div class='articleDiv";
                        if (trim($v2['tag']) != ""){echo " tagged";}
                        echo "'>";

                            echo nl2br($v2['article']);

                            if (trim($v2['tag']) != ""){echo "<div class='redBlock'>■</div>";}

                            echo "<div class='tagDiv'>";
                            foreach ($tag as $tagno=>$_tag){
                                $checked = ($_tag == $v2['tag']) ? "checked" : "";
                                echo "<label for='tag_" . $date . "_" . $num . "_" . $tagno . "' class='tagLabel'>";
                                    echo "<input type='radio' name='tag[" . $date . "][" . $num . "]' id='tag_" . $date . "_" . $num . "_" . $tagno . "' " . $checked . " value='" . $_tag . "'>";
                                    echo $_tag;
                                echo "</label>";
                                if ($tagno%10 == 9){echo "<br>";}
                            }
                            echo "</div>";

                        echo "</div>";
                    }
                echo "</div>";
            }
        ?>

    </form>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <div><br><br><br><br><br></div>

    <style type="text/css">
    <!--
    .btn_prev_next {width : 28px; }
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_other_tuning {cursor : pointer; margin : 2px; }
    .dateDiv {background : #339933; color : #ffffff; font-weight : bold; padding : 2px;}
    .articleDiv {border : 1px solid #cccccc; padding : 10px; margin : 10px;}
    .tagDiv {background : #ffff99; padding : 3px;}
    .tagLabel {padding : 2px; margin : 3px;}
    .redBlock {color : #ff3333; font-size : 20px;}
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_other_tuning").width(38);
    $("#btn_other_tuning").click(function (){location.href = "{{ url('/other/tuning') }}";});

    $("#btn_input").click(function (){
        $("#form_tag_input").submit();
    });

    $(".btn-primary").click(function (){
        $(".tagged").map(function(index, element) {
            $(this).css("display", "none");
        });
    });
    -->
    </script>

@stop
