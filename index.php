<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="res/css/vendor/normalize.min.css"/>
    <link rel="stylesheet" type="text/css" href="res/css/app.css?id=<?= filemtime(__DIR__ . "/res/css/app.css"); ?>"/>

    <script type="text/javascript" src="src/js/vendor/jquery.min.js"></script>
    <script type="text/javascript" src="src/js/vendor/notify.js"></script>
    <script type="text/javascript" src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>


</head>
<body>
<header>
    <h1><a href="#">HireMe</a> <span id="header-title"></span></h1>

    <ul>
        <li><a href="javascript:App.Actions.reloadApp()" title="Reload Application"><i class="fa fal fa-redo"></i></a></li>
        <li><a href="javascript:window.location.reload()" title="Refresh Page"><i class="fas fa-sync"></i></a></li>
    </ul>
</header>
<main id="main-container"></main>

<script type="text/javascript" src="src/js/app.js?id=<?= filemtime(__DIR__ . "/src/js/app.js"); ?>"></script>
</body>
</html>
