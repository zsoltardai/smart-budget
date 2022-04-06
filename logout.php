<?php

session_start();

if (isset($_SESSION['user'])) {
    session_destroy();
    unset($_SESSION);
}

header('location: login.php');