<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>rsdatalist</title>
</head>
<body>
<h1>rsdatalist</h1>

<div class="container">
    <a href="{{ url('/money/rsdatainput') }}" class="btn btn-success">input</a>

    <div>
        <?php

        $midashi = ['year', 'month', 'day', 'rs_increase', 'rs_decrease', 'content', 'remarks', 'price'];

        $_youbi = ["日", "月", "火", "水", "木", "金", "土"];

        echo "<table class='table table-bordered mb-5'>";

        foreach ($result as $v) {
            echo "<tr>";

            $date = $v->year . "-" . $v->month . "-" . $v->day;
            $youbi = $_youbi[date("w", strtotime($date))];

            foreach ($midashi as $v2) {
                echo "<td>";
                echo $v->$v2;

                if ($v2 == "day") {
                    echo " / " . $youbi;
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
