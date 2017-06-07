	<?php 
    	require("../inc/includes.php"); // Alle nützlichen Hilfsfunktionen, datenbankanbindung und session-Eröffnung
    // Ist das Skript korrekt aufgerufen worden?
	
	 
	  // Login ist gültig: Es folgt der Code zur Abarbeitung der Nutzeraktionen
	
		
		if (isset($_POST['daten']))
		 // JA, Formulardaten vorhanden
			$daten = $_POST['daten'];
		else
			$daten['nutzer_name'] = "";
			

		// Hat der Nutzer vorher einen Button gedrückt und so eine Aktion angefordert? (Ist Formacation gesetzt?)
		if (isset($_POST['formaction']))
			{
			$formaction = $_POST["formaction"];
			$formobject = $_POST["formobject"];
			echo "action=$formaction<br>";
			echo "object=$formobject";
			}
		else  // Ansonsten: Keine Aktion setzen
		{	$daten = array();
			$daten['nutzer_name']="";
			$daten['nutzer_email']="";

			$formaction="";
			$formobject="";
		}	
		switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
			{   // Löschbefehl erteilt, betroffene ID ist in formaction--> Nutzer mit dieser ID löschen
				case 'del':
				$sql="DELETE FROM nutzer WHERE id= $formobject";   //Alternativ: Feld Active in DB auf 0 Uodaten
				$db->query($sql);
				$formobject = "";
				$data = array();
				break;     //Raus aus Switch-Statement
				
				case 'add':
				if ($formobject=="")
				    { if(($daten['nutzer_name'] <>"") && ($daten['nutzer_email'] <>""))
						{
							$daten['nutzer_password'] = "8GryivdoabnotVejLomvorUk6";
							nutzer_eintragen_db($daten);
						}
					}
				else
					 { if(($daten['nutzer_name'] <>"") && ($daten['nutzer_email'] <>""))
						 {   $sql="UPDATE nutzer 
				             SET name='".$daten['nutzer_name']."',
				                 email='".$daten['nutzer_email']."'
				             WHERE id=".$formobject;     
							$db->query($sql);}
				}
				break;
				
				case 'edit':
				$sql="SELECT id,name AS nutzer_name,email AS nutzer_email FROM nutzer where id=".$formobject;
				$rs= $db->query($sql);  
				$daten=$rs[$formobject];
				//echo_r($daten);
				break;
							
			}	// of switch

			  // 2. Teil: Ergebnisse in der Nutzertabelle anzeigen
			 // Alle Nutzerdaten holen damit Sie weiter unten angezeigt werden können
				$sql="SELECT id,name,email FROM nutzer";
				$alle_nutzer = $db->query($sql);   // Hole alle Daten in das Array $alle_nutzer
				//echo_r($alle_nutzer);
			
			// In diesem Teil wird die Webseite mit dem Ergebnis der obigen Operationen aufbaut
			 require("head.php");
			 $datum = date("d.m.Y H:i");
			 echo "Heute ist der $datum<br><br>";
			 echo "<h1>Nutzerverwaltung</h1>";
		
		echo '<form name="form1" action="nutzer_verwaltung.php" method="post" enctype="multipart/form-data">';
		
		//Hier kann man einen neuen Nutzer anlegen
		?>
		<h4>Nutzer anlegen / ändern</h4>
		<label>Name:</label>
		<input type = "text" name="daten[nutzer_name]" id="daten[nutzer_name]"
		  value="<?=$daten['nutzer_name']?>" /> <br/><br/>
		  <label>Email:</label>
		<input type = "text" name="daten[nutzer_email]" id="daten[nutzer_email]"
		  value="<?=$daten['nutzer_email']?>" /> <br/><br/>
		 <input type="submit" name="speichern" value="Speichern" />
		 <br /><br/> <br/>
		 
		<h4>Bestehende Nutzer</h4>
		<?  echo "<p></p><table border='1'>";
				foreach ($alle_nutzer AS $id=>$nutzer)   // Und Zeige diese an
				 { 	echo "<tr>"; 
					foreach ($nutzer as $spalte)
						{
							echo '<td width="30%">'.$spalte.'</td>';
							
						}
		// Der Löschbutton
		?>
			<td width="15%">
				<input type="button" value="Löschen"
				  onclick="if(confirm('Wirklich löschen?'))
							document.form1.formaction.value='del';
							document.form1.formobject.value='<?=$id?>';
							document.form1.submit();" >
			</td> 
			
						<td width="15%">
				<input type="button" value="Ändern"
				  onclick="	document.form1.formaction.value='edit';
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
