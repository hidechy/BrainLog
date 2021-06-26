<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>money | datainput</title>
</head>
<body>


<div class="bg-dark text-white p-1">[A] mizuho 046 2961375 / 88615227
    <a href="https://web.ib.mizuhobank.co.jp/servlet/LOGBNK0000000B.do" target="_blank" class="btn btn-primary">link</a>
</div>
<div>
    <?php
    print_r($lastData['A']);
    echo "<br>";
    print_r($bankData['bank_a']);
    ?>
</div>

<div id="div_credit_input">
    <form method="POST" action="{{ url('/money/creditdatamodify') }}" id="form_credit_input">
        {{ csrf_field() }}
        <input type="hidden" name="bank" value="A">
        <textarea name="credit" class="ta_credit"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<hr>


<div class="bg-dark text-white p-1">[B] sumitomo 547 8981660
    <a href="https://direct.smbc.co.jp/aib/aibgsjsw5001.jsp" target="_blank" class="btn btn-primary">link</a>
</div>
<div>
    <?php
    print_r($lastData['B']);
    echo "<br>";
    print_r($bankData['bank_b']);
    ?>
</div>

<div id="div_credit_input">
    <form method="POST" action="{{ url('/money/creditdatamodify') }}" id="form_credit_input">
        {{ csrf_field() }}
        <input type="hidden" name="bank" value="B">
        <textarea name="credit" class="ta_credit"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<hr>


<div class="bg-dark text-white p-1">[C] sumitomo 259 2967733
    <a href="https://direct.smbc.co.jp/aib/aibgsjsw5001.jsp" target="_blank" class="btn btn-primary">link</a>
</div>
<div>
    <?php
    print_r($lastData['C']);
    echo "<br>";
    print_r($bankData['bank_c']);
    ?>
</div>

<div id="div_credit_input">
    <form method="POST" action="{{ url('/money/creditdatamodify') }}" id="form_credit_input">
        {{ csrf_field() }}
        <input type="hidden" name="bank" value="C">
        <textarea name="credit" class="ta_credit"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<hr>


<div class="bg-dark text-white p-1">[D] ufj 271 0782619
    <a href="https://entry11.bk.mufg.jp/ibg/dfw/APLIN/loginib/login?_TRANID=AA000_001&_ga=2.142824253.941972633.1621663340-1229651531.1609328022" target="_blank" class="btn btn-primary">link</a>
</div>
<div>
    <?php
    print_r($lastData['D']);
    echo "<br>";
    print_r($bankData['bank_d']);
    ?>
</div>

<div id="div_credit_input">
    <form method="POST" action="{{ url('/money/creditdatamodify') }}" id="form_credit_input">
        {{ csrf_field() }}
        <input type="hidden" name="bank" value="D">
        <textarea name="credit" class="ta_credit"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<hr>


<div class="bg-dark text-white p-1">[E] 楽天 226 2994905
    <a href="https://fes.rakuten-bank.co.jp/MS/main/RbS?CurrentPageID=START&&COMMAND=LOGIN" target="_blank" class="btn btn-primary">link</a>
</div>
<div>
    <?php
    print_r($lastData['E']);
    echo "<br>";
    print_r($bankData['bank_e']);
    ?>
</div>

<div id="div_credit_input">
    <form method="POST" action="{{ url('/money/creditdatamodify') }}" id="form_credit_input">
        {{ csrf_field() }}
        <input type="hidden" name="bank" value="E">
        <textarea name="credit" class="ta_credit"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>


<div><br><br><br></div>

<style type="text/css">
    .ta_credit {
        width: 700px;
        height: 300px;
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

</body>
</html>
