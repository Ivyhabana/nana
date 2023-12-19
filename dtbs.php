<?php
$host='localhost';
$dbname='webts';
$db_user='root';
$db_pass='';

$con=mysqli_connect($host, $db_user, $db_pass, $dbname);

if(!$con){
    die();
}


?>