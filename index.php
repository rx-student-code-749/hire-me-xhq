<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css"
          href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="res/css/vendor/normalize.min.css"/>
    <link rel="stylesheet" type="text/css" href="res/css/app.css?id=<?= filemtime(__DIR__ . "/res/css/app.css"); ?>"/>

    <script type="text/javascript" src="src/js/vendor/jquery.min.js"></script>
</head>
<body>
<header>
    <h1>HireMe <span id="header-title"></span></h1>
    <a href="#" onclick="App.Actions.reloadPage()" title="Refresh Page">Refresh</a>
</header>
<main id="main-container"></main>
<!---->
<!--<div class="loading"></div>-->
<script type="text/javascript" src="src/js/app.js?id=<?= filemtime(__DIR__ . "/src/js/app.js"); ?>"></script>
<script type="text/javascript">
    document.onload = App.bootstrap();
</script>
</body>
</html>
