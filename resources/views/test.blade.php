<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />



    <!-- Compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">


    <!-- materializecss icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- jqury CDN -->
    <!-- <script src="{{ asset('js/jquery-3.4.1.min.js') }}" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $("textarea.AutoHeight").css("overflow", "hidden").bind("keydown keyup", function() {
                $(this).height('0px').height($(this).prop("scrollHeight") + "px");
            }).keydown();
        });
    </script>
    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js/materialize.min.js') }}"></script>

    <!-- font-awesom -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->


    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!--css樣式-->

    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}" charset="utf-8"> -->
    <link rel="stylesheet" href="{{ asset('css/total.css') }}" charset="utf-8">
  <link rel="stylesheet" href="{{ asset('css/menu.css') }}" charset="utf-8">

  <link rel="stylesheet" href="{{ asset('css/input.css') }}" charset="utf-8">

    <!-- <link rel="Shortcut Icon" type="image/x-icon" href="img/PBLAP_logo_small_c.png"> -->

</head>

<body>
    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s3"><a href="#test1">Test 1</a></li>
                <li class="tab col s3"><a class="active" href="#test2">Test 2</a></li>
                <li class="tab col s3 disabled"><a href="#test3">Disabled Tab</a></li>
                <li class="tab col s3"><a href="#test4">Test 4</a></li>
            </ul>
        </div>
        <div id="test1" class="col s12">Test 1</div>
        <div id="test2" class="col s12">Test 2</div>
        <div id="test3" class="col s12">Test 3</div>
        <div id="test4" class="col s12">Test 4</div>
    </div>
    <script>
        $(document).ready(function() {
            $('.tabs').tabs();
        });
    </script>
</body>

</html>