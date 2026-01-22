<?php 

$server="localhost";
$user="root";
$password="";
$dbase="skillprovk";

$conn=mysqli_connect($server, $user, $password, $dbase );


if(!$conn){

	die("Connection failed: ".mysqli_error());

}
 ?>
