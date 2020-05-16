<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//

$wDay = ['日' , '月' , '火' , '水' , '木' , '金' , '土'];
$youbi = $wDay[date("w" , strtotime($dispdate))];

$linkUrl = "/article/" . $dispdate . "/edit";

$prevLink = "/article/" . $prevDate . "/display";
$nextLink = "/article/" . $nextDate . "/display";
?>

@extends('layouts.brain')

@section('title', '記事表示')

@section('content')
    <script type="text/javascript" src="{{ $public_path }}/js/jQueryRotate.js"></script>

    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

    @if ($useDevice == "pc")
        <table style="margin:5px;"><tr>
        <td>
            <div style='font-size:30px;font-weight:bold;'>
            {{ $dispdate }}
            <span style="font-size:15px;">（{{ $youbi }}）</span>
            </div>
        </td>
        <td>
            <img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
            <img src="{{ $public_path }}/img/edit.png" id="btn_article_edit">
            <img src="{{ $public_path }}/img/search.png" id="btn_article_search">
            <img src="{{ $public_path }}/img/photo.png" id="btn_article_photo">
            <img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next" id="btn_prev">
            <img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next" id="btn_next">
        </td>
        <td>
            <form method="POST" action="{{ url('/article/datejump') }}" id="form_article_datejump" style="display : inline;">
                {{ csrf_field() }}
                <input type="date" name="jumpdate" id="jumpdate">
            </form>
            <input type="button" id="btn_datejump" value="jump">
        </td>
        <td>
            <img src="{{ $public_path }}/img/reposit.png" id="btn_article_reposit">
        </td>
        </tr></table>
    @endif

    @if ($useDevice == "mobile")
        <table style="margin:5px;"><tr>
        <td>
            <div style='font-size:30px;font-weight:bold;'>
            {{ $dispdate }}
            <span style="font-size:15px;">（{{ $youbi }}）</span>
            <img src="{{ $public_path }}/img/cal_back.png" class="btn_prev_next" id="btn_prev">
            <img src="{{ $public_path }}/img/cal_next.png" class="btn_prev_next" id="btn_next">
            </div>
        </td></tr>
        <tr><td>
            <?php
            $menuAry = ['calender' , 'edit' , 'search' , 'jump' , 'photoupload' , 'kinmu'];
            ?>
            <select id="select_menu" onchange="menu_select();">
            <option></option>
            @foreach ($menuAry as $v)
            <option value="{{ $v }}">{{ $v }}</option>
            @endforeach
            </select>
            <div id="div_mobile_jump_date" style="display : none;">
                <form method="POST" action="{{ url('/article/datejump') }}" id="form_article_datejump" style="display : inline;">
                    {{ csrf_field() }}
                    <input type="date" name="jumpdate" id="jumpdate">
                </form>
                <input type="button" id="btn_datejump" value="jump">
                <img src="{{ $public_path }}/img/reposit.png" id="btn_article_reposit">
            </div>

            <div id="div_photo_upload" style="display : none;">
                <form method="POST" action="{{ url('/article/photoupload') }}" id="form_article_photoupload" style="display : inline;" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="dispdate" value="{{ $dispdate }}">
                    <input type="hidden" name="return_here" value="0">
                    <input type="file" name="image[]" multiple="multiple">
                </form>
                <input type="button" id="btn_photoupload" value="upload">
            </div>

        </td>
        </tr></table>
    @endif

    <div id="div_article_display">

        @if ($todayMoney)
        <div id="div_money">
            <div>所持金：{!! number_format($todayMoney) !!}円</div>
            <div>使用額：{!! number_format($diff_day) !!}円</div>
            <div>先月比：{!! number_format($diff_month) !!}円</div>
            <div>平均額：{!! number_format($average) !!}円</div>

	        <?php
	        if (!empty($credit)){
	            echo "<div id='div_credit'>";
	            echo implode("<br>" , $credit);
	            echo "</div>";

	            $ary = [];
	            foreach ($credit as $cre){
	            	preg_match("/([0-9]+)円/" , strtr($cre , [',' => '']) , $m99);
	            	$ary[] = $m99[1];
	            }

	            echo "<div>";
	            echo "⇒";
	            echo ($diff_day - array_sum($ary));
	            echo "</div>";
	        }
	        ?>
        </div>
        @endif

        <?php
        if (!empty($uranai)){
            echo "<div>";
            echo "<div id='btn_uranai'>占</div>";
            echo "<br style='clear : both;'>";
            echo "<div style='display : none;'>";
            echo "<textarea id='uranai_text'>" . $uranai . "</textarea>";
            echo "</div>";
            echo "<div>";
        }
        ?>

        @if ($folderPhoto)
            <div id="div_photo_disp">

                <div id="div_photo_enlarge">
                <label for="btn_enlarge" id="label_photo_enlarge">
                <input type="checkbox" id="btn_enlarge">拡大表示する
                </label>
                </div>

                @foreach ($folderPhoto as $k=>$photo)
                    <div class="photo_div" id="photo_div_{{ $k }}">
                        <img src="{!! strtr($folderPath , ['/var/www/html' => 'http://' . $_SERVER['SERVER_ADDR']]) !!}/{{ $photo }}" class="disp_photo" onclick="javascript:photoRotation('{{ $k }}');">
                    </div>
                @endforeach
                <br style="clear : both;">
            </div>
        @endif

        @if ($weather)
            <div id="div_weather">{{ $weather }}</div>
        @endif

        @foreach ($data as $line)
            <div class="article_div">
                <?php
                $ary1 = [];
                $ex_article = explode("\n" , $line['article']);
                foreach ($ex_article as $v){
                    if (preg_match("/^http/" , $v)){
                        $ary1[] = "<a href='" . trim($v) . "' target='_blank'>" . trim($v) . "</a>";
                    }else if (preg_match("/^tel:/" , $v)){
                        $ary1[] = "<a href='" . trim($v) . "' target='_blank'>" . trim($v) . "</a>";
                    }else{
                        $ary1[] = trim($v);
                    }
                }
                $_article_ = implode("\n" , $ary1);
                ?>
                {!! nl2br($_article_) !!}

                @if ($line['tag'])
                    <div class="tag_div">（{{ $line['tag'] }}）</div>
                @endif
            </div>
        @endforeach
    </div>

    <style type="text/css">
    <!--
    #btn_article_index {cursor : pointer; margin : 2px; }
    #btn_article_edit {cursor : pointer; margin : 2px; }
    #div_article_display {border : 3px solid #cccccc; overflow : auto;}
    .article_div {border-bottom : 5px dotted #cccccc; margin : 10px; word-wrap : break-word; }
    .tag_div {font-size : 10px; }
    .btn_prev_next {width : 28px; cursor : pointer; }
    #div_weather {color : #ff99ff; text-align : right; font-weight : bold; margin : 10px; }
    #div_money {background : #ffffcc; padding : 5px; margin : 10px; }
    #btn_article_search {cursor : pointer; margin : 2px; }
    #btn_article_reposit {cursor : pointer; margin : 2px; }
    #btn_article_photo {cursor : pointer; margin : 2px; }
    #div_photo_disp {border : 1px solid #cccccc; background : #ccffcc; margin : 10px; }
    .photo_div {float : left; margin : 3px;}
    .disp_photo {width : 50px; cursor : pointer; }
    #div_photo_enlarge {background : #339933; color : #ffffff; margin-bottom : 20px; }
    #label_photo_enlarge {cursor : pointer; }
    #coverScreen {background : rgba(0,255,0,0.1); text-align : center; position : absolute; top : 0px; left : 0px; z-index : 10;}
    #div_close_coverScreen {background : #339933; color : #ffffff; text-align : left;}
    #div_item_display {background : #ffffff; }
    #div_credit {padding : 5px; background : #ffffee; border : 1px dotted #cccccc; border-radius : 5px;}
    #div_uranai {padding : 0px 5px; margin : 10px 0px; }
    #btn_uranai {float : right; background : #ff9900; color : #ffffff; margin : 3px 10px; padding : 5px; cursor : pointer; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_article_index").width(38);
    $("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

    $("#btn_article_edit").width(38);
    $("#btn_article_edit").click(function (){
        location.href = "{{ url($linkUrl) }}";
    });

    var divLeft = 10;
    var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

    var divTop = 100;
    var divBottom = 30;
    var divHeight = ($(window).height() - divTop - divBottom);

    $("#div_article_display").width(divWidth);
    $("#div_article_display").height(divHeight);
    $("#div_article_display").css("margin-left" , divLeft);

    $("#btn_prev").click(function (){
        location.href = "{{ url($prevLink) }}";
    });

    $("#btn_next").click(function (){
        location.href = "{{ url($nextLink) }}";
    });

    $("#btn_datejump").click(function (){
        $("#form_article_datejump").submit();
    });

    $("#btn_article_search").width(38);
    $("#btn_article_search").click(function (){location.href = "{{ url("/article/search") }}";});

    $("#btn_article_reposit").width(20);
    $("#btn_article_reposit").click(function (){
        $("#jumpdate").val("<?=date("Y-m-d")?>");
        $("#form_article_datejump").submit();
    });

    $("#btn_article_photo").width(38);
    $("#btn_article_photo").click(function (){location.href = "{{ url('/article/' . $dispdate . '/photo') }}";});

    $("#btn_photoupload").click(function (){
        $("#form_article_photoupload").submit();
    });

    function menu_select(){
        var menu = $("#select_menu").val();
        switch (menu){
            case "calender":
                location.href = "{{ url('/article/index') }}";
                break;
            case "edit":
                location.href = "{{ url($linkUrl) }}";
                break;
            case "search":
                location.href = "{{ url("/article/search") }}";
                break;
            case "jump":
                $("#div_mobile_jump_date").css("display" , "block");
                break;
            case "photoupload":
                $("#div_photo_upload").css("display" , "block");
                break;
            case "kinmu":
                location.href = "{{ url('/other/kinmu') }}";
                break;
        }
    }

    function photoRotation(num){
        if($("#btn_enlarge:checked").val()) {
            showCoverScreen();

            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            var ex_html = ($("#photo_div_" + num).html()).split('"');
            var img = "";
            var regex = /^http/gi;
            for (var i=0 ; i<ex_html.length ; i++){
                match = regex.exec(ex_html[i]);
                if (match){
                    img = ex_html[i];
                    break;
                }
            }

            var useDevice = "<?=$useDevice?>";
            if (useDevice == "mobile"){
                var img_width = Math.floor($(window).width() * 0.9);
            }else{
                var img_width = Math.floor($(window).width() * 0.4);
            }

            var img_tag = "<img src='" + img + "' style='width : " + img_width + "px;' id='LargePhoto' onclick='javascript:largePhotoRotation();'>";
            $("#div_item_display").html(img_tag);
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        }else{
            $("#photo_div_" + num + " img").rotate(90);
        }
    }

    function showCoverScreen(){
        $('#coverScreen').offset({ top: 100, left: 100 });
        $("#coverScreen").width($(window).width());
        $("#coverScreen").height($(window).height());

        var dccsText = "";
        dccsText += "<div style='padding : 10px;'>";
        dccsText += "<div style='float : left; margin-right : 20px;'><span onclick='javascript:hideCoverScreen();' style='cursor : pointer;'>CLOSE</span></div>";

        dccsText += "<div style='float : left;' id='changeBtnDiv'>";
        dccsText += "<input type='button' onclick='javascript:photoChange(\"back\");' value='<<'>";
        dccsText += "<input type='button' onclick='javascript:photoChange(\"next\");' value='>>'>";
        dccsText += "</div>";

        dccsText += "<br style='clear : both;'>";
        dccsText += "</div>";

        $("#div_close_coverScreen").html(dccsText);

        $("#div_item_display").css("margin" , "5px");
        $("#div_item_display").css("padding" , "5px");
    }

    function hideCoverScreen(){
        $("#div_close_coverScreen").html("");

        $("#div_item_display").html("");
        $("#div_item_display").css("margin" , "0px");
        $("#div_item_display").css("padding" , "0px");

        $('#coverScreen').offset({ top: 0, left: 0 });
        $("#coverScreen").width(1);
        $("#coverScreen").height(1);
    }

    function largePhotoRotation(){
        $("#div_item_display img").rotate(90);
    }

    $("#btn_uranai").click(function (){
        showCoverScreen();

        var str = "";
        str += "<div style='text-align : left; padding : 10px; line-height : 160%;'>";
        str += $("#uranai_text").val();
        str += "</div>";

        $("#div_item_display").html(str);

        $("#changeBtnDiv").html("");
    });

    function photoChange(flag){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ $appUrl }}/article/photochange',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN ,
                'flag' : flag , 
                'src' : $("#LargePhoto").attr('src')
            },
            success: function (data) {
                if (data != ""){
                    $("#LargePhoto").attr('src' , data);
                }
            }
        });
    }
    -->
    </script>

    <div id="coverScreen">
    <div id="div_close_coverScreen"></div>
    <div id="div_item_display"></div>
    </div>

@stop
