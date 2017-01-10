<?php
 

function tabelleUser()
{


    if(isset($_POST['limit']))                              //Das Limit für die Ausgegebenen Nuzter festlegen.
    {
        $limit=$_POST['limit'];
    }
    else
    {
        $limit=5;
    }


    global $db;
    $sql="SELECT * FROM user limit $limit";                 //Nutzerdaten aus der Datenbank lesen.  
    $result=$db->query($sql);
    $anzahl=count($result);


                                                            //Html Tabelle öffnen und Kopfzeile benennen
    echo("
    <table border='1'>
    <thead>
    <tr>
    <th>ID</th>
    <th>Vorname</th>
    <th>Nachname</th>
    <th>Anrede</th>
    <th>Titel</th>
    <th>Straße</th>
    <th>Postleitzahl</th>
    <th>Stadt</th>
    <th>Land</th>
    <th>Email</th>
    <th>Passworthash</th>
    <th>Eingetragen am</th>
    <th>Geburtsdatum</th>
    </tr>
    </thead>
    ");


    for($l=0; $l < $anzahl; $l++)                             //Tabelle mit den Daten aus der DB füllen.
    {
        echo("<tr>");
        foreach($result[$l] as $key => $nutzer)
        {
            echo("<td><FONT SIZE='1'>$nutzer</td>");
        }
        echo("</tr>");
    }

       
    echo("</table>");                                           //Html Tabelle schließen.
    
    

                                                                //Manuelle anza
    echo("
    <form action='admin.php' method='post'>
    Limit er angezeigten Nutzer:												
    <select name='limit' size='1'>
    <option></option>
    <option>1</option>
    <option>2</option>	
    <option>5</option>
    <option>10</option>								
    <option>20</option>
    <option>50</option>
    <option>100</option>
    <option>200</option>
    </select>
    <input type='submit'value='Aktualisieren'>
	</form>
    ");
}

?>