<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>

<?php
$ta_default = "
受信日時：


送信者：


案件：


概要：


スキル：


場所：


期間：


募集人数：


単金：


精算：


面談：


end
";
?>

<form method="POST" action="{{ url('/anken/store') }}" id="form_anken_input">
    {{ csrf_field() }}

    <div>
        <textarea name="anken" id="ta_anken">
            {{ $ta_default }}
        </textarea>
    </div>

</form>

<div>
    <input type="button" id="btn_input" value="input">
</div>

<style type="text/css">
    <!--
    #ta_anken {font-size: 12px;}
    -->
</style>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>

<script type="text/javascript">
    <!--
    $("#btn_input").click(function () {
        $("#form_anken_input").submit();
    });

    $(function () {
        var divLeft = 10;
        var divWidth = ($(window).width() - (divLeft * 2) - 10 - 20);

        var divTop = 100;
        var divBottom = 30;
        var divHeight = ($(window).height() - divTop - divBottom);

        $("#ta_anken").width(divWidth);
        $("#ta_anken").height(divHeight);
        $("#ta_anken").css("margin-left", divLeft);
    });
    -->
</script>

</body>
</html>
