<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>youtubeUrlList</title>
</head>
<body>
<h1>youtubeUrlList</h1>

<div class="container">
    <a href="{{ url('/other/youtubeUrlInput') }}" class="btn btn-success">input</a>

    <br>

    <table class="table table-bordered">
        <tr class="bg-info">
            <td>id</td>
            <td>title</td>
            <td>channel_title</td>
            <td>getdate</td>
        </tr>

        @foreach($result as $v)

            @php
            $bgColor = ($v->del == 1) ? "#dddddd" : "#ffffff";

            if ($bgColor == "#ffffff"){
                if ($v->special == 1){
                    $bgColor = "#eeeeff";
                }
            }
            @endphp

            <tr style="background: {{ $bgColor }}">
                <td>
                    {{ $v->id }}
                </td>

                <td>
                    <a href="{{ $v->url }}" target="_blank">{{ $v->title }}</a>
                    <div>{{ $v->youtube_id }}</div>
                </td>

                <td>{{ $v->channel_title }}</td>
                <td>{{ $v->getdate }}</td>
            </tr>
        @endforeach
    </table>

    <br>

    <a href="{{ url('/other/youtubeUrlInput') }}" class="btn btn-success">input</a>

    <br>


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
