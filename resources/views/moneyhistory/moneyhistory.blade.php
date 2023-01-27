<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>MoneyHistory</title>
</head>
<body>

<div class="container">

    <br>

    @php
        $ary = [];
    @endphp



    <table class="table-bordered" id="tbl_display">
        @foreach($data as $v)

            @php
                $ex_record = explode("/", trim($v['record']))
            @endphp

            @foreach($ex_record as $v2)

                @php
                    $ex_record = explode("|", trim($v2))
                @endphp

                @php
                    $ex_date = explode("-", trim($v['date']))
                @endphp

@if(isset($ex_record[2]))
@if($ex_record[2] > 0)
<tr>
<td>{{ $ex_date[0] }}</td>
<td>{{ $ex_date[1] }}</td>
<td>{{ $ex_date[2]??'' }}</td>
<td>{{ $ex_record[1] }}</td>
<td class="alignRight">{{ $ex_record[2]??'' }}</td>
</tr>
@endif
                @else
    @php
    $ary[] = trim($v['record']);
    @endphp
@endif









            @endforeach

        @endforeach
    </table>



    @php
print_r($ary);
@endphp


    <br>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<style type="text/css">
    #tbl_display td {
        padding: 5px 20px;
    }

    .alignRight {
        text-align: right;
    }
</style>

</body>
</html>
