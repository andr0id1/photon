<?require("../www/head.php"); ?>

<?php  
require("../inc/includes.php"); 
require("adminHilfsfunktionen.php");

$datum = date("d.m.Y H:i");
echo "Heute ist der $datum<br>";
?>	

<?php

tabelleUser();

?>


<?require("../www/foot.php");?>