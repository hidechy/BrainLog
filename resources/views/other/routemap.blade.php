<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>route map</title>

    <script>
        function initMap() {
            var directionsRenderer = new google.maps.DirectionsRenderer();

            var map = new google.maps.Map(document.getElementById("gmap"), {
                zoom: 13,
                center: new google.maps.LatLng(40.750127, -73.981084),
                mapTypeId: "roadmap"
            });

            directionsRenderer.setMap(map);

            var directionsService = new google.maps.DirectionsService();

            let latlng = "<?=$latlng?>";
            let ex_latlng = (latlng).split("/");

            let start_lat = ex_latlng[0];
            let start_lng = ex_latlng[1];

            let end_lat = ex_latlng[2];
            let end_lng = ex_latlng[3];

            var start = new google.maps.LatLng(start_lat, start_lng);
            var end = new google.maps.LatLng(end_lat, end_lng);

            var request = {origin: start, destination: end, travelMode: 'WALKING'};

            directionsService.route(request, function (result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    alert("取得できませんでした：" + status);
                }
            });

        }
    </script>

</head>
<body>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU&callback=initMap" async
        defer></script>

<div id="gmap"></div>










<div>【出発地】</div>
<div>{{ $start_address }}</div>
<table class="tbl_station">
    <?php
    foreach ($station_start as $v) {
        echo "<tr>";
        echo "<td>" . $v['name'] . "</td>";
        echo "<td>" . $v['line'] . "</td>";
        echo "<td style='text-align: right;'>" . number_format($v['distance']) . " m</td>";
        echo "<td>" . $v['traveltime'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<hr>

<div>【目的地】</div>
<div>{{ $end_address }}</div>
<table class="tbl_station">
    <?php
    foreach ($station_end as $v) {
        echo "<tr>";
        echo "<td>" . $v['name'] . "</td>";
        echo "<td>" . $v['line'] . "</td>";
        echo "<td style='text-align: right;'>" . number_format($v['distance']) . " m</td>";
        echo "<td>" . $v['traveltime'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<hr>

<div>【距離】</div>
<div>{{ $distance }}</div>
<hr>

<div>【時間】</div>
<div>{{ $duration }}</div>
<hr>

<div class="text-center mt-3">
    <a href="{{ url('/other/route') }}" class="btn btn-success">戻る</a>
</div>

<style>
    #gmap {
        height: 400px;
        width: 100%;
    }

    #distance_table td {
        padding: 2px;
    }

    .tbl_station td {
        border: 1px solid #cccccc;
        padding: 2px;
        font-size: 10px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>
</html>
