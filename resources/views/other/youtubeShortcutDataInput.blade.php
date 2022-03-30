<!doctype html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>youtubeShortcutDataInput</title>
</head>
<body>
<h1>youtubeShortcutDataInput</h1>

<div class="container">
    <div>windows</div>
    <form method="POST" action="{{ url('/other/youtubeShortcutDataInputExecute') }}" id="form_youtubedata_input">
        {{ csrf_field() }}
        <input type="hidden" name="mode" value="windows">
        <textarea name="youtubeShortcutData" style="width: 1000px; height: 300px;"></textarea>
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<hr>

<div class="container">
    <div>mac</div>
    <form method="POST" action="{{ url('/other/youtubeShortcutDataInputExecute') }}" id="form_youtubedata_input" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="mode" value="mac">
        <input type="file" name="youtubeShortcutData">
        <button class="btn bg-success text-white">submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

</body>
</html>
