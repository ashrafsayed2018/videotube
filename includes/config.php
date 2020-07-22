<?php 
ob_start();
session_start();
date_default_timezone_set("Asia/Kuwait");

try {
  $con = new PDO("mysql:host=localhost;dbname=videoTube","root","");
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "failed to connect " . $e->getMessage();
}
