<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>route input</title>
</head>
<body>


<div class="container">
    <div class="row">
        <div class="col-sm-12">

            <form method="POST" action="{{ url('/other/routemap') }}" id="form_route_input">
                {{ csrf_field() }}

                <input type="hidden" name="selected_tb" id="selected_tb">

                <div class="p-3">
                    ■出発地<br>
                    <input type="text" name="origin" id="origin" class="form-control"
                           onclick="javascript:setTbBorder('origin')" autocomplete="off">
                </div>

                <div class="p-3">
                    ■目的地<br>
                    <input type="text" name="destination" id="destination" class="form-control"
                           onclick="javascript:setTbBorder('destination')" autocomplete="off">
                </div>

                <div class="p-2">
                    <span class="badge badge-success p-2 m-2" onclick="javascript:setSpecificPoint('自宅')">自宅</span>
                    <span class="badge badge-success p-2 m-2" onclick="javascript:setSpecificPoint('実家')">実家</span>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">ルート検索</button>
                </div>

            </form>

        </div>
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
    function setTbBorder(name) {
        let selected_tb = $("#selected_tb").val();

        $("#selected_tb").val('');
        $("#origin").css('border', '3px solid #cccccc');
        $("#destination").css('border', '3px solid #cccccc');

        if (name != selected_tb) {
            $("#selected_tb").val(name);
            $("#" + name).css('border', '3px solid #c71585');
        }
    }

    function setSpecificPoint(point) {
        let selected_tb = $("#selected_tb").val();
        if (selected_tb == '') {
            return false;
        }
        $("#" + selected_tb).val(point);
    }
</script>

<style type="text/css">
    .badge {
        cursor: pointer;
    }
</style>

</body>
</html>
