<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>mercaridatalist</title>
</head>
<body>
<h1>mercaridatalist</h1>

<div class="container">
    <a href="{{ url('/money/mercaridatainput') }}" class="btn btn-success">input</a>

    <div>
        <?php
        $midashi = ['buy_sell', 'title', 'sell_price', 'tesuuryou', 'shipping_fee', 'price', 'departured_at', 'settlement_at', 'receive_at'];

        echo "<table class='table table-bordered'>";
        foreach ($result as $v){
            echo "<tr>";
            foreach ($midashi as $v2){
                echo "<td>";

                switch ($v2){
                    case "departured_at":
                    case "settlement_at":
                    case "receive_at":
                        echo (trim($v->$v2) == "") ? "<br>" : date("Y-m-d H", strtotime($v->$v2));
                        break;
                    default:
                        echo $v->$v2;
                        break;
                }

                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

</body>
</html>
