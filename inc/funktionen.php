<?php	




function abweichungen($monat_id, $lehrer, $wohin)
{
    $message="";
    $sqlerror=0;
    if(is_numeric($lehrer))
    {
        $lehrer_id=$lehrer;
    }
    else
    {
        global $db;
        $sql='SELECT lehrer.lehrer_id FROM lehrer WHERE lehrer.wins="'.$lehrer.'"';
        $result=$db->query($sql);
        $lehrer_id=$result[0]['lehrer_id'];
    }

	if (isset($_POST['save'.$lehrer_id.'']))
	{
		if($_POST['saveMonat']==$monat_id)
		{
		$formaction = "save";
		$alles=$_POST;
		}
		else
		{
			$formaction="";
		}
	
	}
	else
	{
		$formaction="";
	}


	switch ($formaction)  // Je nach gespeichertem Befehl aus Formular eine Aktion ausführen
	{   
		case 'save':
		global $db;
		$datetime = date("Y-m-d H:i:s");
		$alleklassen=$alles['klassen'];


		if(isset($alles['insert'])) 		//Klassen die einen Insert haben sollen, diese werden eingetragen und von der Liste aller zu bearbeitenen Klassen genommen.
		{
			foreach($alles['insert'] AS $key=>$insertklasse)
			{
				$alleklassen=array_diff($alleklassen, array($insertklasse));
				$neuinsert=$alles[$insertklasse];
				$name=$neuinsert[0];
				$v=$neuinsert[1];
				$z=$neuinsert[2];
				$e=$neuinsert[3];
				$a=$neuinsert[4];

				if(($v !=="") || ($z !=="") || ($e !=="") || ($a !==""))
				{
                    $error=0;
                    if($v=="")
                    {
                        $v="NULL";
                    }
                    elseif (! is_numeric($v))
                    {
                        $error++;
                    }

                    if($z=="")
                    {
                        $z="NULL";
                    }
                    elseif (! is_numeric($z))
                    {
                        $error++;
                    }

                    if($e=="")
                    {
                        $e="NULL";
                    }
                    elseif (! is_numeric($e))
                    {
                        $error++;
                    }

                    if($a=="")
                    {
                        $a="NULL";
                    }
                    elseif (! is_numeric($a))
                    {
                        $error++;
                    }

                    if (empty($error))
                    {
                        $sql = 'insert into abweich (klasse_id,monat_id,v_h,z_h,e_h,a_h,eing_lehrer_id,eing_dat) values ((select klasse_id from klasse where klasse = "'.$name.'"),"'.$monat_id.'",'.$v.','.$z.','.$e.','.$a.',"'.$lehrer_id.'","'. $datetime.'")';
                        $result=$db->query($sql);
                        if($result==false)
                        {
                            $sqlerror++;
                            echo("Es konnte kein neuer Eintrag erstellt werden.");
                            break;
                        }
                    }
                    else
                    {
                        echo ("Tragen Sie bitte nur Zahlen ein.");
                    }
				}				
			}			
		}
	
		foreach($alleklassen AS $key=>$updateklasse)		//Alle klassen die in alleklassen vorhanden sind bekommen ein Update in der Datenbank.
		{
			global $db;
			$neuupdate=$alles[$updateklasse];
			$name=$neuupdate[0];
			$v=$neuupdate[1];
			$z=$neuupdate[2];
			$e=$neuupdate[3];
			$a=$neuupdate[4];

            if(($v =="") && ($z =="") && ($e =="") && ($a ==""))
            {
                $sql = 'DELETE FROM abweich WHERE abweich.klasse_id=(SELECT klasse.klasse_id FROM klasse WHERE klasse ="'.$name.'") AND abweich.monat_id = '.$monat_id.' ';
                $result=$db->query($sql);
                if($result==false)
                {
                    $sqlerror++;
                    echo("Der Eintrag konnte nicht gelöscht werden.");
                    break;
                }
            }
            else
            {
                $error=0;
                if($v=="")
                {
                    $v="NULL";
                }
                elseif (! is_numeric($v))
                {
                    $error++;
                }

                if($z=="")
                {
                    $z="NULL";
                }
                elseif (! is_numeric($z))
                {
                    $error++;
                }

                if($e=="")
                {
                    $e="NULL";
                }
                elseif (! is_numeric($e))
                {
                    $error++;
                }

                if($a=="")
                {
                    $a="NULL";
                }
                elseif (! is_numeric($a))
                {
                    $error++;
                }

                if (empty($error))
                {
                    $sql = 'update abweich set v_h = '.$v.', z_h = '.$z.', e_h = '.$e.', a_h = '.$a .',eing_lehrer_id = "'.$lehrer_id.'", eing_dat = "'.$datetime.'" where monat_id = "'.$monat_id.'" and klasse_id = (select klasse_id from klasse where klasse = "'.$name.'")';
                    $result=$db->query($sql);
                    if($result==false)
                    {
                        echo("Der Eintrag konnte nicht aktualisiert werden.");
                        $sqlerror++;
                        break;
                    }
                }
                else
                {
                    echo ("Tragen Sie bitte nur Zahlen ein");
                }

            }
            if($sqlerror==0)
            {
                $message="<h4 style='color: green;'>Die Werte wurden erfolgreich gesichert.</h4>";
            }

		}
		break;
	}




	global $db;
	$sql="SELECT klasse.klasse FROM klasse WHERE klasse.lehrer_id='$lehrer_id'";
	$result=$db->query($sql);

	if($result==false)
    {
        echo "<h3>Es wurde keine Klasse zugewiesen</h3>";
    }
    else
    {


        echo "<form name='form1' action='$wohin' method='post'  enctype='multipart/form-data'>

	
	   
        <table class='minimalist-b'  summary='Abweichstunden'>
            <tr>
                <th class='border-right'>Klassen</th>
                <th>V</th>
                <th>Z</th>
                <th>E</th>
                <th>A</th>
            </tr>
        ";

        $zählwertKlasse = 0;
        foreach ($result AS $key => $klassenName) {
            $klasse = $klassenName['klasse'];
            $arrayName1 = "$zählwertKlasse" . "[" . "0" . "]";
            $arrayName2 = "klassen" . "[" . "$zählwertKlasse" . "]";
            echo("<input type = 'hidden' name='$arrayName1' value='$klasse'>");
            echo("<input type = 'hidden' name='$arrayName2' value='$zählwertKlasse'>");

            global $db;
            $sql = "SELECT abweich.v_h, abweich.z_h, abweich.e_h, abweich.a_h FROM klasse
            JOIN abweich
            on abweich.klasse_id=klasse.klasse_id
            WHERE klasse.klasse='$klasse'
            and abweich.monat_id='$monat_id'
            ";
            $abweichungen = $db->query($sql);


            if (!isset($abweichungen[0])) {
                $arrayName3 = "insert" . "[" . "$zählwertKlasse" . "]";
                echo("<input type = 'hidden' name='$arrayName3' value='$zählwertKlasse'>");
                $abweichungen = array(array("v_h" => "", "z_h" => "", "e_h" => "", "a_h" => ""));
            }

            echo("<tr>");
            echo("<td class='border-right'>$klasse</td>");
            $i = 1;
            foreach ($abweichungen[0] AS $NR => $abweichungenWerte) {
                $arrayName4 = "$lehrer_id"."_"."$zählwertKlasse" . "_" . "$i";
                $arrayName5 = "$zählwertKlasse" . "[" . "$i" . "]";
                echo("<td valign='middle'' align='center'><input type='text'  value='$abweichungenWerte' name='$arrayName5' id='$arrayName4' onchange='Function$arrayName4()' maxlength='2' size='2'></td>
			<script>
			function Function$arrayName4() {
			document.getElementById('$arrayName4').className = 'updated';
			}
			</script>");
                $i++;
            }

            echo("</tr>");
            $zählwertKlasse++;
        }


        echo("</table>
	<style>
	.updated 
	{
		color: #04B404;
		font-weight:900
	}
	</style>");

        echo "<input type = 'hidden' name='saveMonat' value= '$monat_id' />";


        echo'
            <input type="submit" value="Speichern" class="btn btn-primary btn-sm sharp"><br>
            <input type="hidden" name="save'.$lehrer_id.'" value=1>

        </form>
        <br>'.$message.'<br>';

    }
}




function lehrerKlassenZuweisen($lehrer, $wohin)
{
    $message="";

	if (isset($_POST['formaction']))
	{
		$alles=$_POST;

		if(isset($alles['klassenIst']))
		{
		    if(isset($alles['klassenSoll']))
		    {
                $austragenKlassen=array_diff($alles['klassenIst'], $alles['klassenSoll']);
            }
            else
            {
                $austragenKlassen=$alles['klassenIst'];
            }

            if(empty($austragenKlassen)==false)
            {
                foreach($austragenKlassen AS $key=>$austragen)
                {
                    global $db;
                    $sql="UPDATE `klasse` SET `lehrer_id` = '0' WHERE `klasse`.`klasse` = '$austragen'";
                    $ausgetragen=$db->query($sql);
                    if($ausgetragen=true)
                    {
                        klasseOnOff($austragen);
                        lehrerOnOff($lehrer);
                        $message .= "Die Klasse $austragen wurde erfolgreich ausgetragen.<br><br>";
                    }
                    else
                    {
                        $message .= "Die Klasse $austragen konnte nicht ausgetragen werden.<br><br>";
                    }
                }
            }
        }

		if(isset ($alles['klassenDazu']))
		{
			$umtragenKlasse=$alles['klassenDazu'];
			foreach($umtragenKlasse AS $key=>$umtragen)
			{
				global $db;
				$sql="UPDATE `klasse` SET `lehrer_id` = '$lehrer' WHERE `klasse`.`klasse` = '$umtragen'";
				$umgetragen=$db->query($sql);
				if($umgetragen=true)
				{
				    klasseOnOff($umtragen);
				    lehrerOnOff($lehrer);
                    $message .= "Die Klasse $umtragen wurde erfolgreich umgetragen.<br><br>";
				}
				else
				{
                    $message .= "Die Klasse $umtragen konnte nicht umgetragen werden.<br><br>";
				}
			}
		}	
	}			

	global $db;
	$sql="SELECT klasse.klasse FROM klasse WHERE klasse.aktiv= 1 AND klasse.lehrer_id='$lehrer' ORDER BY `klasse` ASC";
	$zugewieseneKlassen=$db->query($sql);
	$sql="SELECT klasse.klasse FROM klasse WHERE klasse.lehrer_id =0 ORDER BY `klasse` ASC";
	$unzugewieseneKlassen=$db->query($sql);
	$sql="SELECT klasse.klasse FROM klasse WHERE klasse.aktiv= 1 and klasse.lehrer_id NOT IN (0) ORDER BY `klasse` ASC";
	$alleKlassen=$db->query($sql);
    $sql="SELECT lehrer.wins FROM lehrer WHERE lehrer.lehrer_id=$lehrer ";
    $lehrerWins=$db->query($sql);

	echo "
    <div class='col-lg-2'>
        <h4>Gewählter Lehrer:<br></h4><h2>".$lehrerWins[0]['wins']."</h2>
    </div>
    <div class='col-lg-3 border-left'>
        <form  action='$wohin?data%5Bid%5D=$lehrer' method='post' enctype='multipart/form-data'>
        <table class='minimalist-b' style='width: 20rem'>
            <tr>
                <th><h4>Klassen</h4></th>
                <th></th>
            </tr>";
            $zählwertDurchläufe=0;
            foreach($zugewieseneKlassen AS $key=>$klassenName)
            {
                $value=$klassenName['klasse'];
                $arrayName6="klassenSoll"."["."$zählwertDurchläufe"."]";
                $arrayName7="klassenIst"."["."$zählwertDurchläufe"."]";

                echo "
                <tr>
                    <td valign='middle' align='center'>$value</td>
                    <td> <input type='checkbox' name='$arrayName6' value='$value' checked='checked'></td>
                </tr>
                <input type = 'hidden' name='$arrayName7' value='$value'>";
                $zählwertDurchläufe++;
            }

        echo "
        </table>
        
        <input class='btn btn-primary sharp top-buffer-groß'  type=\"submit\" value=\"Speichern\"><br>
        <input type = \"hidden\" name=\"formaction\" value=\"save\" />
        
        <h4 class='top-buffer'>$message</h4>
    </div>
    <div class='col-lg-3'>
    
        <table class='minimalist-b' style='width: 25rem'>
            <tr>
                <th><h4>Ohne Zuweisung</h4></th>
                <th></th>
            </tr>";

        $zählwertDurchläufe2=0;
        foreach($unzugewieseneKlassen AS $key=>$klassenName)
        {
            $arrayName8="klassenDazu"."["."$zählwertDurchläufe2"."]";
            $value=$klassenName['klasse'];
            echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td><input type='checkbox' name='$arrayName8' value='$value'></td>
            </tr>";
            $zählwertDurchläufe2++;
        }

        echo "
        
        <tr style='border-top: 3px solid #545454'>
            <th><h4>Bereits zugewiesen</h4></th>
            <th></th>
        </tr>";

        foreach($alleKlassen AS $key=>$klassenName)
        {
            $arrayName9="klassenDazu"."["."$zählwertDurchläufe2"."]";
            $value=$klassenName['klasse'];

            echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td> <input type='checkbox' name='$arrayName9' value='$value'></td>
            </tr>";
            $zählwertDurchläufe2++;
        }

        ?>

        </table>


        </form>

    </div>

	<?php
}



function monat($wohin)
{
	$date =  date("Y-m-d",time());
	$dateArray2 = explode('-',$date);
  
  	if ($dateArray2[1] > 1)
	{
		$dateArray2[1] = $dateArray2[1] - 1;
	}     // muss aktiv werden, wenn Oktober eingetragen  
  	else 
  	{
     	$dateArray2[1] = '12';
	 	$dateArray2[0] = $dateArray2[0] - 1;
	}


  	$date2 = $dateArray2[0].'-'.$dateArray2[1].'-15';

	global $db;
	$sql = 'select * from monat where "'.$date.'" between vondat and bisdat order by vondat';
	$result=$db->query($sql);
	$aktuellerMonat=$result[0];

	$sql = 'select * from monat where "'.$date2.'" between vondat and bisdat order by vondat';
	$result=$db->query($sql);
	$letzterMonat=$result[0];

	if(isset($_POST['monat_id']))
	{
        $_SESSION['monat_id']=$_POST['monat_id'];
    }

    if(isset($_SESSION['monat_id']))
    {
		$monatNummer=$_SESSION['monat_id'];
		switch($monatNummer)
		{
			case $aktuellerMonat['monat_id']:
			$checked1= "checked='checked'";
			$checked2= "";
			break;

			case $letzterMonat['monat_id']:
			$checked1= "";
			$checked2= "checked='checked'";
			break;
		}
	}
	else
	{
		$monatNummer=$aktuellerMonat['monat_id'];
		$checked1= "checked='checked'";
		$checked2= "";
	}

	$vonDatumAktuell = date("d.m ", strToTime($aktuellerMonat['vondat']));
    $bisDatumAktuell = date("d.m ", strToTime($aktuellerMonat['bisdat']));
	$vonDatumAlt = date("d.m ", strToTime($letzterMonat['vondat']));
    $bisDatumAlt = date("d.m ", strToTime($letzterMonat['bisdat']));

	echo "<form name='form2'  action='$wohin' method='post' enctype='multipart/form-data'>";

	echo "<table border=0 class='text-center'>";
	echo "<tr>
		<th></th>
		<th>Monat</th>
		<th>von - bis</th>
		</tr>";

	echo"<tr>
	<td><input type='radio' ",$checked2," onchange='document.form2.submit()' name='monat_id' value='",$letzterMonat['monat_id'],"'> </td>
	<td WIDTH='70'> ", $letzterMonat['monat'] ,"</td>
	<td>",$vonDatumAlt ," - ",$bisDatumAlt ,"</td>
	</tr>";

	echo"<tr>
	<td><input type='radio' ",$checked1," onchange='document.form2.submit()' name='monat_id' value='",$aktuellerMonat['monat_id'],"'> </td>
	<td WIDTH='70' style='font-weight: bold;';'> ", $aktuellerMonat['monat'] ,"</td>
	<td style='font-weight: bold'>",$vonDatumAktuell ," - ",$bisDatumAktuell ,"</td>
	</tr>";

	echo"</table><br><br><br>";

	echo"
	<noscript>
	<input class='btn btn-primary btn-sm sharp' type='submit' value='Übernehmen'><br>
	</noscript>
	</form>";

	return $monatNummer;
}

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function lehrerAnzeigen($lehrer_id, $wins)      //Die Lehrer ID oder den Lehrer Wins angeben und den anderen Wert auf 0 setzten.
{
    if(empty($wins))
    {
        global $db;
        $sql = 'SELECT wins FROM lehrer WHERE lehrer.lehrer_id = '.$lehrer_id.'';
        $result=$db->query($sql);
        $wins=$result[0]['wins'];
        echo "<table border='1'><tr><td>Gewählter Lehrer: <br><h3>$wins</h3></td></tr></td></table>";
    }
    elseif (empty($lehrer_id))
    {
        echo "<table border='1'><tr><td>Gewählter Lehrer: <br><h3>$wins</h3></td></tr></td></table>";
    }
}

function lehrerOffline()
{
    global $db;
    $sql = 'SELECT lehrer_id FROM klasse WHERE klasse.aktiv= 1 and klasse.lehrer_id NOT IN (0) ORDER BY klasse.lehrer_id ASC';
    $result=$db->query($sql);
    array_walk_recursive($result, function($value) use (&$klassenIDs)
    {
        $klassenIDs[] = $value;
    });

    $sql = 'SELECT lehrer_id FROM lehrer WHERE lehrer.aktiv=1 AND lehrer.level=0 ORDER BY lehrer_id ASC';
    $result=$db->query($sql);
    array_walk_recursive($result, function($value) use (&$lehrerIDs)
    {
        $lehrerIDs[] = $value;
    });

    $ohneKlasseID=array_diff($lehrerIDs,$klassenIDs);

    foreach($ohneKlasseID AS $key=>$ID)
    {
        $sql = 'UPDATE lehrer SET aktiv = 0 WHERE lehrer.lehrer_id = '.$ID.'';
        $result=$db->query($sql);
        if($result==false)
        {
            echo "Die Lehrer ID $ID konnte nicht inaktiv geschaltet werden";
        }
    }


}


function freierTag($tag, $monat, $jahr) {

    // Parameter in richtiges Format bringen
    if(strlen($tag) == 1) {
        $tag = "0$tag";
    }
    if(strlen($monat) == 1) {
        $monat = "0$monat";
    }


    // Wochentag berechnen
    $datum = getdate(mktime(0, 0, 0, $monat, $tag, $jahr));
    $wochentag = $datum['wday'];



    // Prüfen, ob Wochenende
    if($wochentag == 0 || $wochentag == 6) {
        return true;
    }


    //Ferien ermitteln
    $YMD="$jahr"."-"."$monat"."-"."$tag";
    global $db;
    $sql = "SELECT ferien.Ferien FROM ferien WHERE '".$YMD."' BETWEEN ferien.Von AND ferien.Bis";
    $result=$db->query($sql);

    if($result==true)
    {
        return true;
    }
    else
    {
        return false;
    }



    // Feste Feiertage werden nach dem Schema ddmm eingetragen
    $feiertage[] = "0101"; // Neujahrstag
    $feiertage[] = "0105"; // Tag der Arbeit
    $feiertage[] = "0310"; // Tag der Deutschen Einheit
    $feiertage[] = "2512"; // Erster Weihnachtstag
    $feiertage[] = "2612"; // Zweiter Weihnachtstag

    // Bewegliche Feiertage berechnen
    $tage = 60 * 60 * 24;
    $ostersonntag = easter_date($jahr);
    $feiertage[] = date("dm", $ostersonntag - 2 * $tage);  // Karfreitag
    $feiertage[] = date("dm", $ostersonntag + 1 * $tage);  // Ostermontag
    $feiertage[] = date("dm", $ostersonntag + 39 * $tage); // Himmelfahrt
    $feiertage[] = date("dm", $ostersonntag + 40 * $tage); // Pfingstferien
    $feiertage[] = date("dm", $ostersonntag + 50 * $tage); // Pfingstmontag

    // Prüfen, ob Feiertag
    $code = $tag.$monat;
    return in_array($code, $feiertage);
}


function wocheFrei($tag, $monat, $jahr)
{
    for($x=1;$x<=5;$x++)
    {
        if (freierTag($tag,$monat,$jahr))
        {
            return true;
        }
        else
        {
            $tag--;
        }
    }

}

function klasseLehrerZuweisen($klasse, $wohin)
{
    $message="";

    if (isset($_POST['formaction']))
    {
        $alles=$_POST;

        if(isset($alles['lehrerIst']))
        {
            if(isset($alles['lehrerSoll']))
            {
                $austragenLehrer=array_diff($alles['lehrerIst'], $alles['lehrerSoll']);
            }
            else
            {
                $austragenLehrer=$alles['lehrerIst'];
            }

            if(empty($austragenLehrer)==false)
            {
                foreach($austragenLehrer AS $key=>$austragen)
                {
                    global $db;
                    $sql="UPDATE klasse SET klasse.lehrer_id = '0' WHERE klasse.klasse_id='$klasse'";
                    $ausgetragen=$db->query($sql);
                    if($ausgetragen=true)
                    {
                        $message .= "Der Lehrer wurde erfolgreich ausgetragen.<br>";
                    }
                    else
                    {
                        $message .= "Der Lehrer konnte nicht ausgetragen werden.<br>";
                    }
                }
            }
        }

        if(isset ($alles['lehrerDazu']))
        {
            $umtragenLehrer=$alles['lehrerDazu'];
            foreach($umtragenLehrer AS $key=>$umtragen)
            {
                global $db;
                $sql="UPDATE klasse SET lehrer_id = (SELECT lehrer.lehrer_id FROM lehrer WHERE lehrer.wins='$umtragen') WHERE klasse.klasse_id = '$klasse'";
                $umgetragen=$db->query($sql);
                if($umgetragen=true)
                {
                    $message .= "Der Lehrer wurde erfolgreich umgetragen.<br>";
                }
                else
                {
                    $message .= "Der Lehrer konnte nicht umgetragen werden.<br>";
                }
            }
        }
    }

    global $db;
    $sql="SELECT lehrer.wins FROM lehrer INNER JOIN klasse ON klasse.lehrer_id=lehrer.lehrer_id WHERE klasse.klasse_id='".$klasse."'";
    $zugewieseneLehrer=$db->query($sql);
    $sql="SELECT lehrer.wins FROM lehrer LEFT JOIN klasse ON (klasse.lehrer_id = lehrer.lehrer_id) WHERE klasse.lehrer_id IS NULL ORDER BY `wins`  ASC";
    $unzugewieseneLehrer=$db->query($sql);
    $sql="SELECT DISTINCT lehrer.wins FROM lehrer INNER JOIN klasse ON klasse.lehrer_id=lehrer.lehrer_id ORDER BY `wins`  ASC";
    $alleLehrer=$db->query($sql);

    if(count($zugewieseneLehrer))
    {
        $deactivate="disabled";
    }
    else
    {
        $deactivate="";
    }

    echo "
    <div class='col-lg-3'>
        <form  action='$wohin?data%5Bid%5D=$klasse' method='post' enctype='multipart/form-data'>
        <table class='minimalist-b' style='width: 20rem'>
            <tr>
                <th><h4>Lehrer</h4></th>
                <th></th>
            </tr>";
    $zählwertDurchläufe=0;
    foreach($zugewieseneLehrer AS $key=>$lehrerName)
    {
        $value=$lehrerName['wins'];
        $arrayName6="lehrerSoll"."["."$zählwertDurchläufe"."]";
        $arrayName7="lehrerIst"."["."$zählwertDurchläufe"."]";

        echo "
                <tr>
                    <td valign='middle' align='center'>$value</td>
                    <td> <input type='checkbox' name='$arrayName6' value='$value' checked='checked' ></td>
                </tr>
                <input type = 'hidden' name='$arrayName7' value='$value'>";         //todo-bo type auf checkboxen umstellen, muss in tabelle funktionieren
        $zählwertDurchläufe++;
    }

    echo "
        </table>
        
        <input class='btn btn-primary sharp top-buffer-groß'  type=\"submit\" value=\"Speichern\"><br>
        <input type = \"hidden\" name=\"formaction\" value=\"save\" />
        <h4 class='top-buffer'>$message</h4>
    </div>
    <div class='col-lg-4'>
    
        <table class='minimalist-b' style='width: 25rem'>
            <tr>
                <th><h4>Lehrer ohne Klasse</h4></th>
                <th></th>
            </tr>";

    $zählwertDurchläufe2=0;
    foreach($unzugewieseneLehrer AS $key=>$lehrerName)
    {
        $arrayName8="lehrerDazu"."["."$zählwertDurchläufe2"."]";
        $value=$lehrerName['wins'];
        echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td><input type='checkbox' name='$arrayName8' value='$value' $deactivate></td>
            </tr>";
        $zählwertDurchläufe2++;
    }

    echo "
        
        <tr style='border-top: 3px solid #545454'>
            <th><h4>Bereits zugewiesen</h4></th>
            <th></th>
        </tr>";

    foreach($alleLehrer AS $key=>$lehrerName)
    {
        $arrayName9="lehrerDazu"."["."$zählwertDurchläufe2"."]";
        $value=$lehrerName['wins'];

        echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td> <input type='checkbox' name='$arrayName9' value='$value' $deactivate></td>
            </tr>";
        $zählwertDurchläufe2++;
    }

    ?>

    </table>


    </form>

    </div>

    <?php
}


function Lehrer_vorhanden($data)
{
    global $db;
    $wins = $data['wins'];
    $sql='SELECT wins FROM lehrer WHERE wins= "'.$wins.'" ';

    ($db_ergebnis=$db->query($sql));
    if (isset ($db_ergebnis[0] ))
    {
        return false;
    }
    else
    {
        return true;
    }
}


function lehrer_eintragen_db($data)
{
    global $db; //Wir verwenden die Standard-DB

    $wins= $data['wins'];
    $l_level= $data['l_level'];
    $l_aktiv= $data['l_aktiv'];
    $l_email=$data['email'];

    $sql = 'insert into lehrer (lehrer_id,wins,email,level,lpw,aktiv) values (NULL,"'.$wins.'","'.$l_email.'","'.$l_level.'",NULL,"'.$l_aktiv.'")';


    if($db->query($sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}


function lehrer_updaten_db($data)
{
    global $db; //Wir verwenden die Standard-DB

    $lehrer_id=$data['id'];

    $wins= $data['wins'];
    $l_level= $data['l_level'];
    $l_aktiv= $data['l_aktiv'];
    $l_email=$data['email'];




    $sql= "UPDATE `lehrer` SET `wins` = '$wins', `email` = '$l_email', `level` = '$l_level', `lpw` =NULL, `aktiv` = '$l_aktiv' WHERE `lehrer`.`lehrer_id` = '$lehrer_id'";

    if($db->query($sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function Klasse_vorhanden($data)
{
    global $db;
    $klasse=$data['klasse'];

    $sql="SELECT klasse FROM klasse WHERE klasse='$klasse' ";

    ($db_ergebnis=$db->query($sql));
    if (isset ($db_ergebnis[0] ))
    {
        return false;
    }
    else
    {
        return true;
    }


}

function Klasse_vorhanden_mit_id($data) // aktuell nicht in Benutzung
{
    global $db;
    $klasse=$data['klasse'];
    $klasse_id=$data['id'];


    $sql= "SELECT klasse FROM klasse WHERE (klasse='$klasse' AND klasse_id=$klasse_id)";

    ($db_ergebnis=$db->query($sql));
    if (isset ($db_ergebnis[0] ))
    {
        return false;
    }
    else
    {
        return true;
    }

}

function klasse_updaten_db($data)
{
    global $db; //Wir verwenden die Standard-DB

    $klasse_id=$data['id'];
    $klasse= $data['klasse'];
    $sollstunden= $data['sollstunden'];
    $k_aktiv= $data['k_aktiv'];

    $sql= "UPDATE `klasse` SET `klasse` = '$klasse',  `sollstunden` = '$sollstunden', `aktiv` = '$k_aktiv' WHERE `klasse`.`klasse_id` = '$klasse_id'";

    if($db->query($sql))

    {
        return true;
    }
    else
    {
        return false;
    }
}

function klasse_eintragen_db($data)
{
    global $db; //Wir verwenden die Standard-DB

    $klasse=$data['klasse'];
    $k_aktiv=$data['k_aktiv'];
    $sollstd=$data['sollstunden'];

    $sql = 'insert into klasse (klasse_id,klasse,aktiv,sollstunden) values (NULL,"'.$klasse.'","'.$k_aktiv.'","'.$sollstd.'")';

    if($db->query($sql))
    {
        return true;
    }
    else
    {
        return false ;
    }
}

function password_kontrolle ($data)

{
    global $db; //Wir verwenden die Standard-DB

    $l_pw = $data['l_pw'];
    $l_level =$data['l_level'];

    if ($l_pw  == "" && $l_level == "1")
    {
        echo "Admin muss ein Password haben!";
    }
    else
    {
        if($l_pw != "" && $l_level == "0")
        {
            echo "Lehrer darf kein Password haben!";
        }
        else
        {
            return true;
        }
    }
}

function email_vergleich ($data)
{
    global $db;
    $email=$data['email'];

    $sql="SELECT * FROM lehrer WHERE email='$email' ";

    ($db_ergebnis=$db->query($sql));
    if (isset ($db_ergebnis[0] ))
    {
        return false;
    }
    else
    {
        return true;
    }
}

function verwaltung_lehrkraefte($data)
{
    global $db;
    $sql = "SELECT wins,aktiv FROM `lehrer`";

    $db_verwaltung_lehrkräfte=$db->query($sql);
    return ($db_verwaltung_lehrkräfte);

}

function echo_r($x)
{
    echo '<pre style="color:red">'.print_r($x,true).'</pre>';
}

function klasseLehrerZuweisen2($klasse,$wohin)
{
    $message="";

    if (isset($_POST['save']))
    {
        $alles=$_POST;

        if(isset ($alles['lehrer']))
        {
            $umtragen=$alles['lehrer'];

            global $db;
            $sql="UPDATE klasse SET lehrer_id = (SELECT lehrer.lehrer_id FROM lehrer WHERE lehrer.wins='$umtragen') WHERE klasse.klasse_id = '$klasse'";
            $umgetragen=$db->query($sql);
            if($umgetragen=true)
            {
                lehrerOnOff($umtragen);
                klasseOnOff($klasse);
                $message .= "Der Lehrer wurde erfolgreich umgetragen.<br>";
            }
            else
            {
                $message .= "Der Lehrer konnte nicht umgetragen werden.<br>";
            }
        }
    }

    if(isset($_POST['loeschen']))
    {
        $alles=$_POST;

        if(isset ($alles['lehrer']))
        {
            $austragen=$alles['lehrer'];

            global $db;
            $sql="UPDATE klasse SET lehrer_id = 0 WHERE klasse.klasse_id = '$klasse'";
            $ausgetragen=$db->query($sql);
            if($ausgetragen=true)
            {
                lehrerOnOff($austragen);
                klasseOnOff($klasse);
                $message .= "Der Lehrer wurde erfolgreich ausgetragen.<br>";
            }
            else
            {
                $message .= "Der Lehrer konnte nicht ausgetragen werden.<br>";
            }
        }
    }

    global $db;
    $sql="SELECT lehrer.wins FROM lehrer INNER JOIN klasse ON klasse.lehrer_id=lehrer.lehrer_id WHERE klasse.klasse_id='".$klasse."'";
    $zugewieseneLehrer=$db->query($sql);
    $sql="SELECT lehrer.wins FROM lehrer LEFT JOIN klasse ON (klasse.lehrer_id = lehrer.lehrer_id) WHERE klasse.lehrer_id IS NULL ORDER BY `wins`  ASC";
    $unzugewieseneLehrer=$db->query($sql);
    $sql="SELECT DISTINCT lehrer.wins FROM lehrer INNER JOIN klasse ON klasse.lehrer_id=lehrer.lehrer_id ORDER BY `wins`  ASC";
    $alleLehrer=$db->query($sql);
    $sql="SELECT klasse.klasse FROM klasse WHERE klasse.klasse_id=$klasse ";
    $klasseName=$db->query($sql);


    echo "
    <div class='col-lg-2'>
        <h4>Gewählte Klasse:<br></h4><h2>".$klasseName[0]['klasse']."</h2>
    </div>
    <div class='col-lg-3 border-left'>
        <div class='row'>
            <div class='col-lg-12'>
                <form  action='$wohin?data%5Bid%5D=$klasse' method='post' enctype='multipart/form-data'>
                <input class='btn btn-primary sharp top-buffer-groß'  type='submit' value='Speichern' name='save'><br>
            </div>
        </div>
        <div class='row'>
            <div class='col-lg-12'>
                <input class='btn btn-primary sharp top-buffer'  type='submit' formaction='$wohin?data%5Bid%5D=$klasse' formmethod='post' value='Zuordnung aufheben' name='loeschen'>
            </div>
        </div>
        <div class='row'>
            <div class='col-lg-12'>
                <h4 class='top-buffer'>$message</h4>
            </div>
        </div>        
    </div>
    <div class='col-lg-4'>
        
        <table class='minimalist-b' style='width: 25rem'>
            <tr>
                <th><h4>Lehrer</h4></th>
                <th></th>
            </tr>";
    $zählwertDurchläufe=0;
    foreach($zugewieseneLehrer AS $key=>$lehrerName)
    {
        $value=$lehrerName['wins'];

        echo "
                <tr>
                    <td valign='middle' align='center'>$value</td>
                    <td> <input type='radio' name='lehrer' value='$value' checked='checked' ></td>
                </tr>";
        $zählwertDurchläufe++;
    }

    echo "
        
            <tr style='border-top: 3px solid #545454'>
                <th><h4>Lehrer ohne Klasse</h4></th>
                <th></th>
            </tr>";

    $zählwertDurchläufe2=0;
    foreach($unzugewieseneLehrer AS $key=>$lehrerName)
    {
        $value=$lehrerName['wins'];
        echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td><input type='radio' name='lehrer' value='$value'></td>
            </tr>";
        $zählwertDurchläufe2++;
    }

    echo "
        
        <tr style='border-top: 3px solid #545454'>
            <th><h4>Bereits zugewiesen</h4></th>
            <th></th>
        </tr>";

    foreach($alleLehrer AS $key=>$lehrerName)
    {
        $value=$lehrerName['wins'];

        echo "
            <tr>
                <td valign='middle' align='center'>$value</td>
                <td> <input type='radio' name='lehrer' value='$value'></td>
            </tr>";
        $zählwertDurchläufe2++;
    }

    ?>

    </table>


    </form>

    </div>

    <?php
}

function searchForWins($wins, $array)
{
    foreach ($array as $key => $val)
    {
        if ($val['wins'] === $wins)
        {
            return true;
        }
    }
    return false;
}

function esfl_abfrage($data)
{
    global $db;
    $email=$data['email'];
    $suchmuster='/@esfl.de/';
	
    if (preg_match($suchmuster,$email))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function lehrerOnOff($lehrer)
{
    if(is_numeric($lehrer))
    {
        $lehrer_id=$lehrer;
    }
    else
    {
        global $db;
        $sql='SELECT lehrer.lehrer_id FROM lehrer WHERE lehrer.wins="'.$lehrer.'"';
        $result=$db->query($sql);
        $lehrer_id=$result[0]['lehrer_id'];
    }



    global $db;
    $sql='SELECT COUNT(klasse.klasse_id) as anzahl FROM klasse WHERE klasse.lehrer_id="'.$lehrer_id.'"';
    $result=$db->query($sql);
    $anzahl=$result[0]['anzahl'];
    $sql='SELECT lehrer.aktiv FROM lehrer WHERE lehrer.lehrer_id="'.$lehrer_id.'"';
    $result=$db->query($sql);
    $status=$result[0]['aktiv'];
    if($anzahl>0)
    {
        if($status==0)
        {
            $sql='UPDATE lehrer SET lehrer.aktiv=1 WHERE lehrer.lehrer_id="'.$lehrer_id.'"';
            $result=$db->query($sql);
            if($result==false)
            {
                return false;
            }
        }
        return true;
    }
    else
    {
        if($status==1)
        {
            $sql='UPDATE lehrer SET lehrer.aktiv=0 WHERE lehrer.lehrer_id="'.$lehrer_id.'"';
            $result=$db->query($sql);
            if($result==false)
            {
                return false;
            }
        }
        return true;
    }
}

function klasseOnOff($klasse)
{
    if(is_numeric($klasse))
    {
        $klasse_id=$klasse;
    }
    else
    {
        global $db;
        $sql='SELECT klasse.klasse_id FROM klasse WHERE klasse.klasse="'.$klasse.'"';
        $result=$db->query($sql);
        $klasse_id=$result[0]['klasse_id'];
    }



    global $db;
    $sql='SELECT klasse.aktiv, klasse.lehrer_id FROM klasse WHERE klasse.klasse_id="'.$klasse_id.'"';
    $result=$db->query($sql);
    $status=$result[0]['aktiv'];
    $lehrerID=$result[0]['lehrer_id'];
    if($lehrerID==0)
    {
        if($status==1)
        {
            $sql='UPDATE klasse SET klasse.aktiv=0 WHERE klasse.klasse_id="'.$klasse_id.'"';
            $result=$db->query($sql);
            if($result==false)
            {
                return false;
            }
        }
        return true;
    }
    else
    {
        if($status==0)
        {
            $sql='UPDATE klasse SET klasse.aktiv=1 WHERE klasse.klasse_id="'.$klasse_id.'"';
            $result=$db->query($sql);
            if($result==false)
            {
                return false;
            }
        }
        return true;
    }
}


function datumrechner($anzahl, $startmonat, $startjahr)
{
    setlocale(LC_TIME, "de_DE");
    date_default_timezone_set('Europe/Berlin');

    /*
    $dateSQL =  date("Y-m-d",time());
    $dateArray  = explode('-',$dateSQL);
    $jahr1=$dateArray[0];
    $monat1=$dateArray[1]+1;
*/
    $monat1=$startmonat;
    $jahr1=$startjahr;

    $datumNEU=array();

    if($monat1==12)
    {
        $monat2=1;
        $jahr2=$jahr1+1;
    }
    else
    {
        $monat2=$monat1+1;
        $jahr2=$jahr1;
    }

    for($x=1;$x<=$anzahl;$x++)
    {
        $monatName=strftime("%B",mktime(0,0,0,$monat1,1,$jahr1));
        $dateArray  = explode('-',date("Y-m-d", strtotime("last Monday",mktime(0,0,0,$monat1,4,$jahr1))));
        $vonJahr=$dateArray[0];
        $vonMonat=$dateArray[1];
        $vonTag=$dateArray[2];
        $skip=false;

        while (freierTag($vonTag,$vonMonat,$vonJahr)==true)
        {
            if(date("t",mktime(0,0,0,$vonMonat,1,$vonJahr))<=$vonTag)
            {
                if($monat1==$vonMonat)
                {
                    $skip=true;
                }

                if($vonMonat==12)
                {
                    $vonMonat=01;
                    $vonJahr++;
                }
                else
                {
                    $vonMonat++;
                }

                $vonTag=1;
            }
            else
            {
                $vonTag++;
            }
        }

        $vonTagCorr=sprintf("%'.02d",$vonTag);
        $vonMonatCorr=sprintf("%'.02d",$vonMonat);
        $vondat="$vonJahr"."-"."$vonMonatCorr"."-"."$vonTagCorr";

        $bisdat=date("Y-m-d", strtotime("last Friday",mktime(0,0,0,$monat2,1,$jahr2)));

        if($skip==false)
        {
            $dateArray=explode('-',$bisdat);
            $bisJahr=$dateArray[0];
            $bisMonat=$dateArray[1];
            $bisTag=$dateArray[2];

            while(wocheFrei($bisTag,$bisMonat,$bisJahr))
            {
                $bisdat=date("Y-m-d", strtotime("last Friday",mktime(0,0,0,$bisMonat,$bisTag,$bisJahr)));
                $dateArray=explode('-',$bisdat);
                $bisJahr=$dateArray[0];
                $bisMonat=$dateArray[1];
                $bisTag=$dateArray[2];
            }
        }

        if($vondat>=$bisdat)
        {
            $skip=true;
        }

        $datumSchleife=array(   "monat"=>$monatName,
            "vondat"=>$vondat,
            "bisdat"=>$bisdat);

        if($skip==false)
        {
            array_push($datumNEU, $datumSchleife);
        }

        if($monat1==12)
        {
            $monat1=0;
            $jahr1++;
        }

        if($monat2==12)
        {
            $monat2=0;
            $jahr2++;
        }

        $monat1++;
        $monat2++;
    }

    return $datumNEU;
}

?>
