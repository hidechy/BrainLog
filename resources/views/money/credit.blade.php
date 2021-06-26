<?php
//---------//
$ex_phpself = explode("/", $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/", $ex_phpself);
//---------//

$max_year = date("Y");
?>

@extends('layouts.brain')

@section('title', '通帳記録入力')

@section('content')
    <script type="text/javascript" src="{{ $public_path }}/js/jquery.highlight-3.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous"></script>

    <table style="margin:5px;">
        <tr>
            <td>
                <img src="{{ $public_path }}/img/list.png" id="btn_money_list">
                <img src="{{ $public_path }}/img/credit.png" id="btn_money_credit">
            </td>
            <td>
                <div>
                    <select id="word">
                        <option></option>
                        @foreach ($itemAry as $v)
                            <option value="{{ $v }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="button" onclick="doHighlight()" value="入力された文字をハイライト"/>
            </td>
        </tr>
    </table>

    <?php
    /*
?>
<div id="div_credit_input">
<div id="div_credit_open">[<span id="span_credit_open" onclick="javascript:openCreditForm();">OPEN</span>]</div>
<input type="hidden" id="credit_form_open" value="close">
<div id="credit_form" style="display : none;">
    <div>（記入例）2017-08-07/ユーシーカード/16366</div>
    <form method="POST" action="{{ url('/money/creditinsert') }}" id="form_credit_input">
        {{ csrf_field() }}
        <textarea name="credit" id="ta_credit"></textarea>
    </form>
    <input type="button" value="insert" id="btn_credit_insert">
</div>
</div>
<?php
*/
    ?>

    <div id="target">
        <table border="0" cellspacing="2" cellpadding="2" id="tbl_money_credit">
            @for ($i=2014 ; $i<=$max_year ; $i++)

                <?php
                $bgColor = ($i % 2 == 0) ? "background : #eeffee;" : "";
                ?>

                <tr style="{{ $bgColor }}">
                    <td style="border : 1px solid #cccccc; vertical-align : top; width : 80px;">{{ $i }}</td>
                    <td>
                        <table border="0" cellspacing="2" cellpadding="2" class="tbl_money_credit_month">

                            <tr>
                                @for ($j=1 ; $j<=6 ; $j++)
                                    <td>
                                        <?php
                                        if (!isset($data2[$i][sprintf("%02d", $j)])) {
                                            echo "<br>";
                                        } else {
                                            echo "<span style='font-weight : bold;'>" . $j . "</span>　";
                                            echo $data2[$i][sprintf("%02d", $j)] . "円";
                                            echo "<hr>";
                                            if (isset($data[$i][sprintf("%02d", $j)])) {
                                                foreach ($data[$i][sprintf("%02d", $j)] as $v) {
                                                    echo "<div>" . $v . "</div>";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                @endfor
                            </tr>

                            <tr>
                                @for ($j=7 ; $j<=12 ; $j++)
                                    <td>
                                        <?php
                                        if (!isset($data2[$i][sprintf("%02d", $j)])) {
                                            echo "<br>";
                                        } else {
                                            echo "<span style='font-weight : bold;'>" . $j . "</span>　";
                                            echo $data2[$i][sprintf("%02d", $j)] . "円";
                                            echo "<hr>";
                                            if (isset($data[$i][sprintf("%02d", $j)])) {
                                                foreach ($data[$i][sprintf("%02d", $j)] as $v) {
                                                    echo "<div>" . $v . "</div>";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                @endfor
                            </tr>

                        </table>
                    </td>
                </tr>
            @endfor
        </table>
    </div>

    <a href="{{ url('/money/creditdatainput') }}" class="btn btn-info">input</a>

    <div><br><br><br></div>

    <style type="text/css">
        <!--
        .tbl_money_credit_month td {
            border: 1px solid #cccccc;
            width: 180px;
            vertical-align: top;
        }

        #btn_money_list {
            cursor: pointer;
            margin: 2px;
        }

        .highlight {
            background-color: yellow;
        }

        #btn_money_credit {
            cursor: pointer;
            margin: 2px;
        }

        #div_credit_input {
            border: 1px solid #cccccc;
            background: #ccffcc;
            margin: 10px;
        }

        #div_credit_open {
            background: #339933;
            color: #ffffff;
            padding: 5px;
        }

        #ta_credit {
            width: 800px;
            height: 200px;
        }

        #span_credit_open {
            cursor: pointer;
        }

        -->
    </style>

    <script type="text/javascript">
        <!--
        $("#btn_money_list").width(38);
        $("#btn_money_list").click(function () {
            location.href = "{{ url('/money/index') }}";
        });

        function doHighlight() {
            var word = $("#word").val();
            $("#target").highlight(word);
        }

        $("#btn_money_credit").width(38);
        $("#btn_money_credit").click(function () {
            location.href = "{{ url('/money/credit') }}";
        });

        function openCreditForm() {
            var credit_form_open = $("#credit_form_open").val();

            if (credit_form_open == "close") {
                $("#credit_form_open").val("open");
                $("#credit_form").css("display", "block");
                $("#span_credit_open").text("CLOSE");
            } else {
                $("#credit_form_open").val("close");
                $("#credit_form").css("display", "none");
                $("#span_credit_open").text("OPEN");
            }
        }

        $("#btn_credit_insert").click(function () {
            $("#form_credit_input").submit();
        });
        -->
    </script>

@stop
