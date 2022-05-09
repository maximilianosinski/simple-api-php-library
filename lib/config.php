<?php
$ProjectName = "";
$Domain = "";
include_once "functions.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$Host = null;
if($_SERVER["SERVER_NAME"] == "localhost"){
    $Host = "http://localhost/$ProjectName";
} else {
    $Host = "https://$Domain";
}