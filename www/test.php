<?php

require("../inc/includes.php"); 

echo_r($_POST);

if (isset($_POST['formaction']))
			{
			$formaction = $_POST["formaction"];
			echo "action=$formaction<br>";
			}
		else
		{
			$formaction="";
		}	
		
		switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
			{   // Löschbefehl erteilt
				case 'save2':
				$sql="DELETE FROM nutzer WHERE id= $formobject";
				$db->query($sql);
				$formobject = "";
				$data = array();
				break;     //Raus aus Switch-Statement
							
			}	


global $db;
$sql="SELECT klasse.klasse,abweich.v_h,abweich.z_h,abweich.e_h,abweich.a_h,abweich.davon_krank_h,lehrer.wins,monat.monat_id
FROM klasse
INNER JOIN monat
INNER JOIN abweich
INNER JOIN lehrer
ON klasse.lehrer_id=abweich.eing_lehrer_id
AND klasse.klasse_id=abweich.klasse_id
And lehrer.lehrer_id =klasse.lehrer_id
Where klasse.lehrer_id = 22
AND abweich.monat_id = monat.monat_id
AND abweich.monat_id =30
";

$result=$db->query($sql);

echo_r($result);



echo '<form name="form1" action="test.php" method="post" enctype="multipart/form-data">';
echo "<p></p><table border='1'>";


				foreach ($result AS $id=>$nutzer)
				 { 	
                     $nummer=$nutzer['id'];
                     echo "<tr>"; 
					 echo("<td>$nummer</td>");
					foreach ($nutzer AS $ding=>$spalte)
						{
                            $name="$nummer"."["."$ding"."]";

							echo "<td width='20%'><input type='text' value='$spalte' name='$name' onchange='' size='3' ></td>";
							
						}
		
		
			echo"</tr>";
		
        
				  } // of foreach ($alle_nutzer
					
			echo '</table>';

            ?>

             <input type="submit"value="Speichern" onclick="if(confirm('Wirklich Speichern?'))
							document.form1.formaction.value='save';
							document.form1.submit();"><br>

            <?php


            echo '<input type = "hidden" name="formaction" value="add" " />';

			echo "</form>";
?>
   