<?// Das HTML für den oberen Teil der Webseite steht in head.php
require("head.php") ?>

<?php  // Im Mittelteil steht der Code welcher die Webseite steuert
require("../inc/includes.php"); // Alle nützlichen Hilfsfunktionen, datenbankanbindung und session-Eröffnung

$datum = date("d.m.Y H:i");
echo "Heute ist der $datum<br><br>";

?>


        <?php

        if(isset($_POST["data"]))       //Wurde Post gefüllt?
            {
                $data=$_POST['data'];
                $data['nutzer_geburtsdatum']=geburtstagTimestamp($data);

                if(felder_kontrolle ($data))        //Sind alle Felder ausgefüllt.
                {
                    $password1=$data['nutzer_password1'];
                    $password2=$data['nutzer_password2'];
                    $email=$data['nutzer_email'];
                    if(isset($data['agbs']))        //Ist der AGB Haken gesetzt worden.
                    {
                        if($password1==$password2)      //Sind Passwort 1 und 2 identisch
                        {
                            if (passwortStärkePrüfen($password1))       //Ist das Passwort stark genug
                            {
                                if(userVorhandenPruefen($email)==0)     // Existiert die verwendetet Mail schon?
                                {
                                    if(geburtstagPrüfen ($data))        // Ist die Person alt genug
                                    {
                                        if(nutzer_eintragen_db($data))      //Daten werden in die Datenbank geschrieben
                                        {
                                            header("Location: index.php");      //Zurück zum Index
                                        }
                                        else
                                        {
                                            die("Die Registrierung ist fehlgeschlagen");
                                        }

                                    }
                                    else
                                    {
                                        echo("Sie sind noch unter 18 Jahre alt");
                                    }

                                }
                                else
                                {
                                    echo("Email wurde schon registriert");
                                }

                            }
                            else
                            {
                                echo("Das eingegebende Passwort nicht sicher genug.<br>
                                    Es muss mindestens ein Großbuchstabe, ein Kleinbuchstabe, eine Zahl und ein Sonderzeichen enthalten sein.");
                            }

                        }
                        else
                        {
                            echo("Die Passwörter stimmen nicht überein");
                        }

                    }
                    else
                    {
                        echo("Die AGB's wurden nicht akzeptiert");
                    }

                }
                else
                {
                    echo("Nicht alle Pflichtfelder wurden richtig ausgefüllt.");
                }

            }
            else
            {
                echo("Bitte füllen sie die Felder aus");
            }

?>


<form action="reg.php" method="post"><br>
<h1>Registrierung</h1><br>

Anrede:												<!-- Anrede im Klappentext -->
<select name="data[nutzer_anrede]" size="1">		<!-- Select leitet eine Auswahlliste ein -->
<option>Herr</option>								<!-- Option sthet der Eintrag für die Liste -->
<option>Frau</option>
</select>

Titel:

<?php
$sql = "SELECT * FROM `nutzer_titel` \n"			// Verbindung zum Ordner von der Datenbank
    . "ORDER BY `nutzer_titel`.`Titel` ASC";

$nutzer_titel=$db->query($sql); 					// Verbindung zur Datenbank

echo "<select name= 'data [nutzer_titel]'>";  		// Einleitung der Auswahlliste und die Variable füllen mit Daten

for($l=0; $l < 10; $l++)							// Schleife, wie oft sie durchlaufen soll
{
    foreach( $nutzer_titel[$l] as $key => $titel) { echo '<option value="' . $titel . '">' . $titel . '</option>'; } // Schleife der Daten in der Datenbak mit Zeile $Key und Spalte $titel
}

echo "</select><br><br>";	

?>

<!-- Es folgen die Eingabefelder der benötigten Angaben -->
Vorname:<br>
<input type="text" name="data[nutzer_vorname]"><br><br>

Nachname:<br>
<input type="text" name="data[nutzer_nachname]"><br><br>

E-Mail:<br>
<input type="text" name="data[nutzer_email]"><br><br>

Passwort:<br>
<input type="password" name="data[nutzer_password1]"><br><br>

Password erneut eingeben:<br>
<input type="password" name="data[nutzer_password2]"><br><br>

Postleitzahl:<br>
<input type ="text" name="data[nutzer_plz]">
<br><br>

Stadt:<br>
<input type ="text" name="data[nutzer_stadt]">
<br><br>

Straße, Nr:<br>
<input type ="text" name= "data[nutzer_straße]">
<br><br>


Land:<br>

<select name='data[nutzer_land]'>

<?php
$sql = "SELECT de FROM `countries`";				// Verbindung zum Ordner von der Datenbank
$countries=$db->query($sql); 						// Verbindung mit der Datenbank

for($l=0; $l < 250; $l++)							// 
{
    foreach($countries[$l] as $key => $land) 		// Schleife der Daten in der Datenbak mit Zeile $Key und Spalte $land
    {
        echo'<option value='.$land.'>'.$land.'</option>';
    }
}

?>

</select><br><br>



Geburtstag: (mindestens 18 Jahre alt) <br>															<!-- Anrede im Klappentext -->		
<select name='data[nutzer_tag]'>										<!--Einleitung der Auswahlliste und die Variable füllen mit Daten -->

<?php
for($geburtstag=1; $geburtstag < 32; $geburtstag++)						// Schleife. Es wird von 1 bis 31 gezählt und in $geburtstag geschrieben.
{
    echo'<option value='.$geburtstag.'>'.$geburtstag.'</option>';		// Die Zahlen von $geburtstag werden in die Auswahlliste geschrieben
}
?>

</select>

<select name='data[nutzer_monat]'>				<!--Einleitung der Auswahlliste und die Variable füllen mit Daten -->
<?php
for($m=1; $m < 13; $m++)						// Schleife. Es wird von 1 bis 12 gezählt und in $m geschrieben.
{
echo '<option value='.$m.'>'.$m.'</option>';	// Die Zahlen von $m werden in die Auswahlliste geschrieben
}
?>

</select>

<select name='data[nutzer_jahr]'>					<!--Einleitung der Auswahlliste und die Variable füllen mit Daten -->

<?php
for($j=2016; $j > 1920; $j--)						 // Schleife. Es wird von 1 bis 12 gezählt und in $m geschrieben.
{
    echo '<option value='.$j.'>'.$j.'</option>';	// Die Zahlen von $j werden in die Auswahlliste geschrieben
}
?>
</select>
<br><br>


<input type="checkbox" name="data[agbs]" value=true/><a href="agb.html">AGB's</a><br><br>

<input type="submit"value="abschicken"><br><br>

</form>





        <?require("foot.php") //Und am Ende kommt der Fussteil der HTML-Seite ?>
