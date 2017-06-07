 		   <?
           require("head.php"); ?>

           <?php  
           require("../inc/includes.php"); 

           $datum = date("d.m.Y H:i");
           echo "Heute ist der $datum<br>";
           

			if (isset($_SESSION['nutzer_mail'])) 		//Wurde sich bereits eingelogt?
		    {
				$nutzer_mail=$_SESSION['nutzer_mail'];
				echo "<br>Hallo, $nutzer_mail, Sie sind eingelogged.<br>";

				if(isset($_SESSION['nutzer_mailAnzahl'])) 		//Wurde ein Anzahl Eintag erstellt? (Wird gesetzt sobald die zu Login verwändete Mail mehr als einmal in der DB steht.)
				{
					$anzahl=$_SESSION['nutzer_mailAnzahl'];

					echo ("Leider wird ihre Email $anzahl mal verwendet.<br>
					Setzen sie sich bitte mit unserem Support in verbindung.");
				}

				session_destroy();
				echo "<br>Sie wurden zu Testzwecken gleich wieder ausgelogged.";
			}
			else
			{
				if(isset($_POST['nutzer_mail']) && isset($_POST['nutzer_password']))  		//Wurde etwas eingetragen?
				{
					if ($_POST['nutzer_mail']!="" && $_POST['nutzer_password']!="") 		//Wurden für Email und Passwort Werte eingetragen?
					{
						$nutzer_mail = $_POST['nutzer_mail'];
						$nutzer_password = $_POST['nutzer_password'];

						if(userVorhandenPruefen($nutzer_mail) >=1)		//Gibt es einen Eintag mit dieser Email?
						{	
							if(userVorhandenPruefen($nutzer_mail) >=2) 		//Wurde die Email 2 mal oder öfter in der DB gefunden?
							{
								$_SESSION['nutzer_mailAnzahl']=userVorhandenPruefen($nutzer_mail);
							}

							if(password_korrekt($nutzer_mail,$nutzer_password)) 		//Ist das Passwort korrekt?
							{
								$rechte = rechte($nutzer_mail);
								$_SESSION['nutzer_rechte'] = $rechte;
								$_SESSION['nutzer_mail'] = $nutzer_mail;		//Nutzer in die Session schreiben.
								echo("hat funktioniert");
								if($rechte=0)
								{
									header('Location: index.php');			//Die Seite neu laden.
								}
								else
								{
									header('Location: ../admin/admin.php');			//Zu Adminseite gehen.
								}
								
							}
							else 
							{
								echo"<br><h3>Falsches Password. Versuchen Sie es nochmals<br></h3>";
								loginformular_ausgeben('index.php');	
							}
						}
						else
						{
							echo("<br>User nicht vorhanden<br>");
							loginformular_ausgeben('index.php');
						}
					}
					else 
					{	
						echo("<br>Füllen sie alle Felder aus<br>");
						loginformular_ausgeben('index.php');
					}
				}
				else
				{
					loginformular_ausgeben('index.php');
				}
		    } 
?>	

<?require("foot.php");?>