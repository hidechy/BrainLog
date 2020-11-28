<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>item summary</title>
</head>
<body>

<div class="container my-5">
    <div class="col-sm-12">
        <select name="year" id="year" class="form-control w-25">
            @for($i=2019; $i<date("Y")+3; $i++)
                <option value="{{ $i }}" @if($i == $year) selected @endif>{{ $i }}</option>
            @endfor
        </select>
        <select name="month" id="month" class="form-control w-25">
            <option value="X">未指定</option>
            @for($i=1; $i<=12; $i++)
                <option value="{!! sprintf("%02d", $i) !!}"
                        @if($i == $month) selected @endif>{!! sprintf("%02d", $i) !!}</option>
            @endfor
        </select>
        <button class="btn btn-primary" id="btn_ym_change">変更</button>
    </div>
</div>

<div class="container mb-5">
    <div class="col-sm-12">

        <table class="table table-striped">
            @foreach($item as $im)
                @if(isset($summary[$im]))
                    <tr>
                        <td>{{ $im }}</td>
                        <td class="text-right">{!! $summary[$im]['sum'] !!}</td>
                        <td class="text-right">{{ $summary[$im]['percent'] }}</td>
                        <td style="width: 50px;">
                            <form method="POST" action="{{ url('/money/repairsummary') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="year" value="{{ $year }}">
                                <input type="hidden" name="month" value="{{ $month }}">
                                <input type="hidden" name="item" value="{{ $im }}">
                                <input type="submit" value="change">
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach

            <tr>
                <td></td>
                <td class="text-right">{{ $total }}</td>
            </tr>
        </table>

    </div>
</div>

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
    $("#btn_ym_change").click(function () {
        location.href = 'http://toyohide.work/BrainLog/money/' + $("#year").val() + '-' + $("#month").val() + '/itemsummary';
    });
</script>

</body>
</html>
