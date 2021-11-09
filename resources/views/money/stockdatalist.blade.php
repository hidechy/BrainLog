<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>stockdatalist</title>
</head>
<body>
<h1>stockdatalist</h1>

<div class="container">
    <a href="{{ url('/money/stockdatainput') }}" class="btn btn-success">input</a>

    <div>
        <?php
        $midashi = ["year", "month", "day", "time", "ticker", "name", "hoyuu_suuryou", "heikin_shutoku_kagaku", "genzaichi", "jika_hyoukagaku", "hyouka_soneki", "soneki_ritsu"];

        $_youbi = ["日", "月", "火", "水", "木", "金", "土"];

        echo "<table class='table table-bordered mb-5'>";

        echo "<tr>";
        foreach ($midashi as $v) {
            echo "<td>";
            echo $v;
            echo "</td>";
            if ($v == "heikin_shutoku_kagaku"){
                echo "<td>price</td>";
            }
        }
        echo "</tr>";

        foreach ($result as $v) {
            echo "<tr>";

            $date = $v->year . "-" . $v->month . "-" . $v->day;
            $youbi = $_youbi[date("w", strtotime($date))];

            foreach ($midashi as $v2) {

                $bgColor = ($v2 == "jika_hyoukagaku") ? "background: #ffff99;" : "";

                echo "<td style='{$bgColor}'>";
                echo $v->$v2;

                if ($v2 == "day") {
                    echo " / " . $youbi;
                }

                echo "</td>";

                if ($v2 == "heikin_shutoku_kagaku") {
                    echo "<td style='background: #ffff99;'>";
                    echo($v->hoyuu_suuryou * trim(strtr($v->heikin_shutoku_kagaku, [',' => ''])));
                    echo "</td>";
                }


            }
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>

    <a href="{{ url('/money/stockdatainput') }}" class="btn btn-success">input</a>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

<style type="text/css">
    td {
        font-size: 12px;
    }
</style>

</body>
</html>
