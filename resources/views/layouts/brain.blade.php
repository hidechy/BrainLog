<?php
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{ $public_path }}/css/styles.css">

        <script type="text/javascript" src="{{ $public_path }}/js/jquery-1.2.6.min.js"></script>
        <script type="text/javascript" src="{{ $public_path }}/js/ui.core.js"></script>
        <script type="text/javascript" src="{{ $public_path }}/js/jquery.scrollfollow.js"></script>
        <script type="text/javascript">
        <!--
        $( document ).ready( function (){
            $( '#floatDiv' ).scrollFollow();
        });
        -->
        </script>

        <meta name="csrf-token" content="{{ csrf_token() }}" />

    </head>
    <body>
        <div class="container">
            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
