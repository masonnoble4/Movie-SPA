<?php
/**
 * Author: Mason Noble
 * Date: 8/1/2020
 * Description: common code for the header
 */
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- css for the signin page -->
    <link rel="shortcut icon" href="img/favicon.png" type="image/png">

    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/movie.css">
    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="css/person.css">
    <link rel="stylesheet" href="css/genre.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=News+Cycle:wght@400;700&display=swap" rel="stylesheet">
    <title>Film Finder</title>

</head>
<body class="d-flex flex-column h-100">

<header style="font-family:Poppins, Arial, Helvetica, sans-serif;font-weight:700;">
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
        <div class="container" style="width:500px;margin:0 auto;">
            <a class="navbar-brand" href="#signin" style="color:#F0E607; font-weight: bold"><img
                        src="img/filmfinder.png"
                        style="width:100px;">&nbsp</a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                <ul class="navbar-nav mr-auto" style="font-size:18px;">
                    <li class="nav-item" id="li-movie">
                        <a class="nav-link disabled" href="#movie" style="color:black;">Movies<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" id="li-person">
                        <a class="nav-link disabled" href="#person" style="color:black;">Credits</a>
                    </li>
                    <li class="nav-item" id="li-cast">
                        <a class="nav-link disabled" href="#genre" style="color:black;">Genre</a>
                    </li>
                    <li class="nav-item" id="li-signin">
                        <a class="nav-link" href="#signin">Sign in</a>
                    </li>
                    <li class="nav-item" id="li-signup" style="display: none;">
                        <a class="nav-link" href="#signup">Sign up</a>
                    </li>
                    <li class="nav-item" id="li-signout" style="display: none;">
                        <a class="nav-link" href="#signout">Sign out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
