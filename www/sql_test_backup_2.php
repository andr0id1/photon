<?php

require("../inc/includes.php"); 


$monat_id=7;


if (isset($_POST['formaction']))
			{
			$formaction = $_POST["formaction"];
			}
		else
		{
			$formaction="";
		}	
		
		switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
			{   // Löschbefehl erteilt
				case 'save':
				$alles=$_POST;
				$datetime = date("Y-m-d H:i:s");
				$alleklassen=$alles['klassen'];
				$sql="SELECT lehrer.lehrer_id
				FROM lehrer
				WHERE lehrer.wins='Bold'";
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
						$c=1;
						while($c < 5)
						{
							if($neuinsert[$c]=="")
							{
								$neuinsert[$c]='NULL';
							}
							$c++;
						}
						$v=$neuinsert[1];
						$z=$neuinsert[2];
						$e=$neuinsert[3];
						$a=$neuinsert[4];
						$sql = 'insert into abweich (klasse_id,monat_id,v_h,z_h,e_h,a_h,eing_lehrer_id,eing_dat) values ((select klasse_id from klasse where klasse = "'.$klasse.'"),"'.$monat_id.'","'.$v.'","'.$z.'","'.$e.'","'.$a.'","'.$lehrer_id.'","'. $datetime.'")';
						echo($sql);
						$result=$db->query($sql);
						if($result==false)
						{
							echo("Es konnte kein neuer Eintrag erstellt werden.");
							break;
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
WHERE lehrer.wins='Kier'
";

$result=$db->query($sql);

echo '<form name="form1" action="sql_test.php" method="post" enctype="multipart/form-data">';

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