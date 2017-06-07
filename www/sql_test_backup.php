<?php

require("../inc/includes.php"); 

global $db;
$sql="SELECT klasse.klasse FROM klasse
JOIN lehrer
ON lehrer.lehrer_id=klasse.lehrer_id
WHERE lehrer.wins='Kier'
";

$result=$db->query($sql);


echo "<p></p><table border='1'>";
$l=1;
foreach($result AS $key=>$k)
{
	$nk=$k['klasse'];
	$narray1="$nk"."["."0"."]";
	echo ("<input type = 'hidden' name='$narray1' value='$nk'>");

	global $db;
	$sql="SELECT abweich.v_h, abweich.z_h, abweich.e_h, abweich.a_h FROM klasse
	JOIN abweich
	on abweich.klasse_id=klasse.klasse_id
	WHERE klasse.klasse='$nk'
	and abweich.monat_id='37'
	";
	$abweich=$db->query($sql);

	echo_r($abweich);

	if(!isset($abweich[0]))
	{
		$insert[]=$nk;
		$abweich=array(array("v_h"=>"", "z_h"=>"", "e_h"=>"", "a_h"=>""));
	}

	echo("<tr>");

	echo("<td>$nk</td>");
	$i=1;
	foreach($abweich[0] AS $NR=>$W)
	{
		$id="$l"."_"."$i";
		$narray2="$nk"."["."$i"."]";
		echo("<td width='20%'><input type='text' value='$W' name='$narray2' id='$id' onchange='Function$id()'  size='3'></td>
		<script>
		function Function$id() {
    	document.getElementById('$id').className = 'updated';
		}
		</script>");

		$i++;
	}

	echo("</tr>");

	
$l++;

}

echo_r($insert);


echo ("</table>
<style>
.updated 
{
	background-color: #bcd9ff
}
</style>");


?>