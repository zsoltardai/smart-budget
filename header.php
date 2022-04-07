<?php

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] === '') {
    header('location: login.php');
}

include_once 'base/Common.php';
include_once 'base/FileSystem.php';
include_once 'base/User.php';
include_once 'base/AES.php';

?>

<html lang="eng">
    <head>
        <title>SmartBudget</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-light">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.php">SmartBudget</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fa-solid fa-grip"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['user']; ?>">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payments.php?id=<?php echo $_SESSION['user']; ?>">Payments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
