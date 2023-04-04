<?php
$servername='localhost';
$username='steinbergkarina';
$password='%9!pplkW?s*F';
$dbname = "steinbergkarina_haaletus2";
$conn=mysqli_connect($servername,$username,$password,"$dbname");
if(!$conn){
   die('Could not Connect My Sql:' .mysql_error());
}
?>
