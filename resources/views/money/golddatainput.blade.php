<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>golddatainput</title>
</head>
<body>
<h1>golddatainput
    <a href="https://www.rakuten-sec.co.jp/ITS/V_ACT_Login.html" target="_blank" class="btn btn-primary">link</a>
</h1>

<div class="container">
    <form method="POST" action="{{ url('/money/golddatainputexecute') }}" id="form_golddata_input">
        {{ csrf_field() }}
        <textarea name="golddata" style="width: 1000px; height: 600px;"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

</body>
</html>
