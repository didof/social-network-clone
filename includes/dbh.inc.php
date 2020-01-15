<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPwd = "";
$dbName = "loginsystemtut";

$conn = mysqli_connect($dbHost, $dbUser, $dbPwd, $dbName) or die(mysqli_connect_error());
