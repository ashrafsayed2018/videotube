<?php
require_once "../includes/config.php";
require_once "../includes/classes/Video.php";

$username = $_SESSION['userLoggedIn'];
$videoId = $_POST['videoId'];

$video = new Video($con,$videoId,$username);

echo ($video->disLike());
