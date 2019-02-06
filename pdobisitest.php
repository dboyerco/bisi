<?php
$dbhost_name = "triton.csdco.com";
$database = "bisitest";
$user = "bisitest";
$password = "bis#bang";

//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host='.$dbhost_name.';dbname='.$database, $user, $password);
} catch (PDOException $e) {
$hellofrom = "ajax send email <info@bisi.com>";
$helloto = "Dennis Boyer <dennis.boyer@bisi.com>";
$hellosubject = "pdobisitest";
$hellobody = "\nError!: " . $e->getMessage() . "\n";
mail($helloto, $hellosubject, $hellobody, "From: $hellofrom");

#print "Error!: " . $e->getMessage() . "<br/>";
die();
}
?>