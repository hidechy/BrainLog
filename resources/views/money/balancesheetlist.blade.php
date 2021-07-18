<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>balancesheetlist</title>
</head>
<body>
<h1>balancesheetlist</h1>

<div class="container">
    <a href="{{ url('/money/balancesheetinput') }}" class="btn btn-success">input</a>

    <div>
        <?php
        $midashi = [
            'year',
            'month',
            'assets_total_deposit_start',
            'assets_total_deposit_debit',
            'assets_total_deposit_credit',
            'assets_total_deposit_end',
            'assets_total_receivable_start',
            'assets_total_receivable_debit',
            'assets_total_receivable_credit',
            'assets_total_receivable_end',
            'assets_total_fixed_start',
            'assets_total_fixed_debit',
            'assets_total_fixed_credit',
            'assets_total_fixed_end',
            'assets_total_lending_start',
            'assets_total_lending_debit',
            'assets_total_lending_credit',
            'assets_total_lending_end',
            'capital_total_liabilities_start',
            'capital_total_liabilities_debit',
            'capital_total_liabilities_credit',
            'capital_total_liabilities_end',
            'capital_total_borrow_start',
            'capital_total_borrow_debit',
            'capital_total_borrow_credit',
            'capital_total_borrow_end',
            'capital_total_principal_start',
            'capital_total_principal_debit',
            'capital_total_principal_credit',
            'capital_total_principal_end',
            'capital_total_income_start',
            'capital_total_income_debit',
            'capital_total_income_credit',
            'capital_total_income_end'
        ];

        echo "<table class='table table-bordered'>";

        echo "<tr>";
        foreach ($midashi as $v2) {
            echo "<td>";
            echo $v2;
            echo "</td>";
        }
        echo "</tr>";

        foreach ($result as $v) {
            echo "<tr>";
            foreach ($midashi as $v2) {
                echo "<td>";
                echo $v->$v2;
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

<style type="text/css">
    body{
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

</body>
</html>
