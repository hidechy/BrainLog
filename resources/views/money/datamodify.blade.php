<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>money | datamodify</title>
</head>
<body>

<div class="container">
    <form method="POST" action="{{ url('/money/creditdatainputexecute') }}" id="form_credit_input">
        {{ csrf_field() }}

        <table class="table table-bordered">
            @foreach($data as $lineno=>$linevalue)
                <tr>
                    <td>
                        {{ $linevalue['bank'] }}
                        <input type="hidden" name="bank[{{ $lineno }}]" value="{{ $linevalue['bank'] }}">
                    </td>
                    <td>
                        {{ $linevalue['year'] }}-{{ $linevalue['month'] }}-{{ $linevalue['day'] }}
                        <input type="hidden" name="year[{{ $lineno }}]" value="{{ $linevalue['year'] }}">
                        <input type="hidden" name="month[{{ $lineno }}]" value="{{ $linevalue['month'] }}">
                        <input type="hidden" name="day[{{ $lineno }}]" value="{{ $linevalue['day'] }}">
                    </td>
                    <td>
                        <input type="text" name="item[{{ $lineno }}]" value="{{ $linevalue['item'] }}"
                               style="width: 500px;">
                    </td>
                    <td style="text-align: right;">
                        {!! number_format($linevalue['price']) !!}
                        <input type="hidden" name="price[{{ $lineno }}]" value="{{ $linevalue['price'] }}">
                    </td>
                    <td style="text-align: right;">
                        {!! number_format($linevalue['bank_price']) !!}
                        <input type="hidden" name="bank_price[{{ $lineno }}]" value="{{ $linevalue['bank_price'] }}">
                    </td>
                </tr>
            @endforeach
        </table>

        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

</body>
</html>
