	<?php 
    	require("../inc/includes.php"); // Alle nützlichen Hilfsfunktionen, datenbankanbindung und session-Eröffnung
    // Ist das Skript korrekt aufgerufen worden?
	
	 
	  // Gültiger Login: Es folgt der Code zur Abarbeitung der Nutzeraktionen
	
		
		if (isset($_POST['daten']))
		{ // JA, Formulardaten vorhanden
			$daten = $_POST['daten'];
			echo_r($daten);
		}
		if (isset($_POST['formaction']))
			{
			$formaction = $_POST["formaction"];
			$formobject = $_POST["formobject"];
			echo "action=$formaction<br>";
			echo "object=$formobject";
			}
		else
		{	$daten = array();
			$formaction="";
			$formobject="";
		}	
		
		switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
			{   // Löschbefehl erteilt
				case 'del':
				$sql="DELETE FROM nutzer WHERE id= $formobject";
				$db->query($sql);
				$formobject = "";
				$data = array();
				break;     //Raus aus Switch-Statement
							
			}	// of switch

			 // Alle Nutzerdaten holen damit Sie weiter unten angezeigt werden können
				$sql="SELECT id,vorname,email FROM user";
				$alle_nutzer = $db->query($sql);
				//echo_r($alle_nutzer);
			
			// In diesem Teil wird die Webseite mit dem Ergebnis der obigen Operationen aufbaut
			 require("head.php");
			 $datum = date("d.m.Y H:i");
			 echo "Heute ist der $datum<br><br>";
			 echo "<h1>Nutzerverwaltung</h1>";
		echo '<form name="form1" action="nutzer_verwaltung.php" method="post" enctype="multipart/form-data">';
			 echo "<p></p><table border='1'>";
				foreach ($alle_nutzer AS $id=>$nutzer)
				 { 	echo "<tr>"; 
					foreach ($nutzer as $spalte)
						{
							echo '<td width="70%">'.$spalte.'</td>';
							
						}
		?>
		
			<td width="15%">
				<input type="button" value="Loeschen"
				  onclick="if(confirm('Wirklich loeschen?'))
							document.form1.formaction.value='del';
							document.form1.formobject.value='<?=$id?>';
							document.form1.submit();" >
			</td> </tr>
		<?php
				  } // of foreach ($alle_nutzer
					
			echo '</table>';
			echo '<input type = "hidden" name="formaction" value="add" " />';
			echo '<input type = "hidden" name="formobject" value="'.$formobject.'"  />';

			echo "</form>";
			require("foot.php"); //Und am Ende kommt der Fussteil der HTML-Seite 
					
		?>
