<?php

require("../inc/includes.php"); 


$monat_id=11;
$lehrer='Kier';

echo_r($_POST);

if (isset($_POST['formaction']))
{
	$formaction = $_POST["formaction"];
	$alles=$_POST;
	$_POST = array();
}
else
{
	$formaction="";
}	
		
switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
{   // Löschbefehl erteilt
	case 'save':
	unset($_POST['formaction']);
	
	$datetime = date("Y-m-d H:i:s");
	$alleklassen=$alles['klassen'];
	$sql="SELECT lehrer.lehrer_id
	FROM lehrer
	WHERE lehrer.wins='$lehrer'";
	$result=$db->query($sql);
	$lehrer_id0=$result[0];
	$lehrer_id=$lehrer_id0['lehrer_id'];

	if(isset($alles['insert']))
	{
		foreach($alles['insert'] AS $key=>$insertklasse)
		{
			$alleklassen=array_diff($alleklassen, array($insertklasse));
			$neuinsert=$alles[$insertklasse];
			$klasse=$neuinsert[0];
			$v=$neuinsert[1];
			$z=$neuinsert[2];
			$e=$neuinsert[3];
			$a=$neuinsert[4];
			if($v || $z || $e || $a !=="")
			{
				$sql = 'insert into abweich (klasse_id,monat_id,v_h,z_h,e_h,a_h,eing_lehrer_id,eing_dat) values ((select klasse_id from klasse where klasse = "'.$klasse.'"),"'.$monat_id.'","'.$v.'","'.$z.'","'.$e.'","'.$a.'","'.$lehrer_id.'","'. $datetime.'")';
				echo("eintrag für $insertklasse <br>");
				$result=$db->query($sql);
				if($result==false)
				{
					echo("Es konnte kein neuer Eintrag erstellt werden.");
					break;
				}

			}
						
		}
				
	}
	foreach($alleklassen AS $key=>$updateklasse)
	{
		$neuupdate=$alles[$updateklasse];
		$v=$neuupdate[1];
		$z=$neuupdate[2];
		$e=$neuupdate[3];
		$a=$neuupdate[4];
		$sql="SELECT klasse.klasse_id
		FROM klasse
		WHERE klasse.klasse='$updateklasse'";
		$result=$db->query($sql);
		$klasse_id0=$result[0];
		$klasse_id=$klasse_id0['klasse_id'];
		$sql = 'update abweich set v_h = "'.$v.'", z_h = "'.$z.'", e_h = "'.$e.'", a_h = "'.$a .'",eing_lehrer_id = "'.$lehrer_id.'", eing_dat = "'.$datetime.'" where monat_id = "'.$monat_id.'" and klasse_id = "'. $klasse_id.'"'; 
		echo("Update für $updateklasse. <br>");
		$result=$db->query($sql);
		if($result==false)
		{
			echo("Der Eintrag konnte nicht aktualisiert werden.");
			break;
		}

	}
	break;							
}	


global $db;
$sql="SELECT klasse.klasse FROM klasse
JOIN lehrer
ON lehrer.lehrer_id=klasse.lehrer_id
WHERE lehrer.wins='$lehrer'
";

$result=$db->query($sql);

echo '<form name="form1" action="sql_test_is.changed.php" method="post" enctype="multipart/form-data">';

echo "<p></p><table border='1'>";
$l=0;
foreach($result AS $key=>$k)
{
	$nk=$k['klasse'];
	$narray1="$nk"."["."0"."]";
	$klassen="klassen"."["."$l"."]";
	echo ("<input type = 'hidden' name='$narray1' value='$nk'>");
	echo ("<input type = 'hidden' name='$klassen' value='$nk'>");

	global $db;
	$sql="SELECT abweich.v_h, abweich.z_h, abweich.e_h, abweich.a_h FROM klasse
	JOIN abweich
	on abweich.klasse_id=klasse.klasse_id
	WHERE klasse.klasse='$nk'
	and abweich.monat_id='$monat_id'
	";
	$abweich=$db->query($sql);


	if(!isset($abweich[0]))
	{
		$insert="insert"."["."$l"."]";
		echo ("<input type = 'hidden' name='$insert' value='$nk'>");
		$abweich=array(array("v_h"=>"", "z_h"=>"", "e_h"=>"", "a_h"=>""));
	}

	echo("<tr>Klasse</tr>
	<tr>");
	echo("<td>$nk</td>");
	$i=1;
	foreach($abweich[0] AS $NR=>$W)
	{
		$id="$l"."_"."$i";
		$narray2="$nk"."["."$i"."]";
		$narray3="$nk"."["."5"."]";

		echo("<td width='20%'><input type='text' value='$W' name='$narray2' id='$id' onchange='Function$id()' onchange='Function$l()' size='3'></td>
		<script>
		function Function$id() 
		{
    		document.getElementById('$id').className = 'updated';
		}

		
		</script>");
		$i++;
	}
	echo("<script>
		function Function$l() 
		{
    		document.getElementById('changed').innerHTML = "); 
			echo("<input type = 'hidden' name='$narray3' value='1'>;
		}
		</script>");
	
	echo("</tr>
	<p id='changed'></p>
	");
	$l++;
}

echo_r($_POST);
echo ("</table>
<style>
.updated 
{
	background-color: #bcd9ff
}
</style>");


?>

<input type="submit"value="Speichern" onclick="if(confirm('Wirklich Speichern?'))
							document.form1.formaction.value='save';
							document.form1.submit();"><br>

            <?php


            echo '<input type = "hidden" name="formaction" value="add" " />';

			echo "</form>";
?>