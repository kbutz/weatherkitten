<html>
    <head>
        <meta charset="utf-8">
        <title>Open Weather API pull</title>
        <meta name="description" content="WeatherCat, the weather as cats.">
        <!-- loads google fonts -->
        <link href="http://fonts.googleapis.com/css?family=Cantarell:400,400italic,700italic,700" rel="stylesheet" type="text/css" />
        <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/styles.css">
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-75604432-1', 'auto');
          ga('send', 'pageview');

        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <p style="float:right;"><a href="?img=all">View all the cats!</a></p>

                <h1 class="masthead"><a href="<?php echo BASE_URL ?>">WeatherKitten</a></h1>
                <p>Pulling weather data from the <a href="http://openweathermap.org/api" target="_blank" alt="OpenWeatherMap API">OpenWeatherMap API</a> and displaying the data with the help of the <a href="https://developers.google.com/maps/documentation/geocoding/start" target="_blank" alt="Google Geocoding API">Google Maps Geocoding API</a> and cat gifs.</p>
