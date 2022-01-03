<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>youtubedatalist</title>
</head>
<body>
<h1>youtubedatalist</h1>

<div class="container">
    <a href="{{ url('/other/youtubedatainput') }}" class="btn btn-success">input</a>

    <div>
        <table class='table table-striped mb-5'>
        @foreach($result as $v)
            <tr>
                <td>{{ $v->youtube_id }}</td>

                <td>
                    <a href="{{ $v->url }}" target="_blank">link</a>
                </td>

                <td>{{ $v->title }}</td>

                <td>{{ $v->bunrui }}</td>

                <td>{{ $v->getdate }}</td>

            </tr>
        @endforeach
        </table>
    </div>

    <a href="{{ url('/other/youtubedatainput') }}" class="btn btn-success">input</a>


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
