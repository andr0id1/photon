<?php
global $db;

//Stellt Inhalte auffälliger und sortierter da.
function echo_r($x)
{
	echo '<pre style="color:red">DEBUG:'.print_r($x,true).'</pre>';
}


//Kontrolliert das übergebene Passwort.
	function password_korrekt($uname,$pw)
	{	
		global $db;
		$sql="SELECT password FROM user WHERE email='$uname'";
		$result=$db->query($sql);
		$password=$result[0];
		if(password_verify($pw,$password['password']))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}






   //Gibt ein simples Anmeldeformular aus.
    function loginformular_ausgeben($wohin_action)
    {    
		 echo "
		 
		         Bitte loggen Sie sich ein:<br>
		         <br> Login:   </h3><br>";
		         
   		  echo '  <form action="'.$wohin_action.'" method="post">
				Mail: <br>
				<input type="text" name="nutzer_mail"> <br>
				Password: <br>
				<input type="password" name="nutzer_password"> <br><br>
				<input type="submit"value="abschicken">
			   </form>';

	   echo ('<br><br><form action="reg.php">
			<input type="submit"value="Registrieren">
			</form>');
     }

	 
//Trägt alle Daten des übergebenen Arrys in die Datenbank ein.
	function nutzer_eintragen_db($data)
	{
		global $db;
		$hashPW=password_hash($data['nutzer_password1'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO user(
			`id`, 
			`vorname`, 
			`nachname`, 
			`anrede`, 
			`titel`,
			`strasse`,
			`postleitzahl`,
			`stadt`,
			`land`,
			`email`, 
			`password`, 
			`angelegt_am`,
			`geburtsdatum`
			) 
			
			VALUES  (
				NULL, 
				'$data[nutzer_vorname]',
				'$data[nutzer_nachname]', 
				'$data[nutzer_anrede]', 
				'$data[nutzer_titel]', 
				'$data[nutzer_straße]',
				'$data[nutzer_plz]', 
				'$data[nutzer_stadt]',  
				'$data[nutzer_land]', 
				'$data[nutzer_email]',
				'$hashPW', 
				NULL,
				'$data[nutzer_geburtsdatum]' 
				)";

		$eintragen = $db->query($sql);
		if($eintragen == true)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

// Prüft das Passwort auf mehrer Kriterien.
	function passwortStärkePrüfen($pwd)
	{
		$error="";
		if( strlen($pwd) < 8 ) 
		{
			$error .= "Passwort ist zu kruz! <br>";
		}

		if( strlen($pwd) > 20 ) 
		{
			$error .= "Passwort ist zu lang! <br>";
		}


		if( !preg_match("#[0-9]+#", $pwd) ) 
		{
			$error .= "Passwort muss eine Nummer enthalten! <br>";
		}


		if( !preg_match("#[a-z]+#", $pwd) ) 
		{
			$error .= "Passwort muss einen Kleinbuchstaben enthalten! <br>";
		}


		if( !preg_match("#[A-Z]+#", $pwd) ) 
		{
			$error .= "Passwort muss einen Großbuchstaben enthalten! <br>";
		}



		if( !preg_match("#\W+#", $pwd) ) 
		{
			$error .= "Passwort muss ein Sonderzeichen enthalten! <br>";
		}


		if($error)
		{
			echo "Das Passwort ist ungültig: $error";
			return false;
		} 
		else
		{
			return true;
		}
	 }

//Prüft wie oft die übergebene Email schon in der Datenbank steht.
	function userVorhandenPruefen ($mailEingabe)
	{
		global $db;
		$sql="SELECT vorname FROM user Where email='$mailEingabe'";
		
		if($db->query($sql))
		{ 
			$anzahl=count($db->query($sql));
			return $anzahl;
		} 
		else
		{
			return 0;
		}
	 }

//Erstellt das Geburtsdatum zu Übergabe in die Datenbank.
	function geburtstagTimestamp($data) 
	{
		$tag=$data['nutzer_tag'];
		$monat= $data['nutzer_monat'];
		$jahr= $data['nutzer_jahr'];
		$datum= "$jahr-$monat-$tag";
		//$timestamp=(strtotime($datum));
		return $datum;
	}
//Prüft ob alle Pflichtfelder ausgefüllt wurden und ob die Postleitzahl Zahlen enthält.
function felder_kontrolle ($data)
{    
	
	
	$anrede= $data['nutzer_anrede'];
	$vorname= $data['nutzer_vorname'];
	$nachname= $data['nutzer_nachname'];
	$plz=$data['nutzer_plz'];
	$straße= $data['nutzer_straße'];
	$land= $data['nutzer_land'];
	$email= $data['nutzer_email'];
	$password= $data['nutzer_password1'];
	$tag= $data['nutzer_tag'];
	$monat= $data['nutzer_monat'];
	$jahr= $data['nutzer_jahr'];
	
	
	if ($vorname && $nachname && $anrede && $plz && $email && $straße && $land && $email && $password && $tag && $monat && $jahr !="")	// Es wird kontrolliert ob die Felder nicht leer sind
	{
		if(preg_match("#[0-9]+#", $plz))																// Es wird überprüft, ob die Adresse zahlen beinhaltet
		{
			return true;
		}
		else
		{
			echo("Die Postleitzahl ist falsch.<br>");
			return false;
		}
	}
	else 
	{
		return false;
	}
	
}


function geburtstagPrüfen ($data)							// Geburtstag auf Volljährigkeit prüfen							
{
	$tag=$data['nutzer_tag'] ;
	$monat= $data['nutzer_monat'];
	$jahr= $data['nutzer_jahr'];
	$datum= "$monat/$tag/$jahr";															

	$time_vergleich = strtotime (date("d.m.Y H:i"));			// $time vergleich ist die akutuelle Zeit																		//
	$geburtstag = strtotime($datum);							// $geburtstag ist der eingegebene Geburtstag
	$time_vergleichszahl= $time_vergleich- $geburtstag;			// $time_vergleichszahl vergleicht den Geburtstag mit der aktuellen Zeit
	//echo $geburtstag ."\n" ;


	if (($time_vergleichszahl>568080000))						// Es wird in Unix verglichen mit dem Unixwert für 18 Jahren
  	{ 
	 	return True;
	}
}

function rechte ($mail) 										//Gibt die Zahl zurück die die Nutzerrechte bestimmt.
{
	global $db;
	$sql="SELECT admin_level FROM user Where email='$mail'";
	$result=$db->query($sql);
	$zahl=$result[0];
	return $zahl;
}

?>
