<?php

$DB_SERVER = "127.0.0.1";
$DB_USERNAME = "root";
$DB_PASSWORD = "";
$DB_DATABASE = "pawpark";

ini_set('display_errors', 1);
error_reporting(E_ALL);
// $koneksi = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$koneksi = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
if($koneksi->connect_error){
    echo "Error database:".$koneksi->connect_error."<br>";
}
?>