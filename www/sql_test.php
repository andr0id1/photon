<?php
require("../inc/includes.php"); 
setlocale(LC_TIME, "de_DE");
date_default_timezone_set('Europe/Berlin');
 



//$date =  date("Y-m",time()); 
//echo $date;
//$dateArray  = explode('-',$date);




$wohin="sql_test.php";

$dateSQL =  date("Y-m-d",time());
global $db;
$sql = 'select monat,vondat,bisdat from monat where bisdat > "'.$dateSQL.'"  order by vondat';

$datumDB=$db->query($sql);

$datumNichtIdentisch=array();

foreach($datumDB AS $key=>$daten)
{
	$datumTeile  = explode('-',$daten['bisdat']);

	$jahrDB=$datumTeile[0];
	$monatDB=$datumTeile[1];


	if($monatDB==12)
	{
		$monatDB2=1;
		$jahrDB2=$jahrDB+1;
	}
	else
	{
		$monatDB2=$monatDB+1;
		$jahrDB2=$jahrDB;
	}


	$datumScriptVON=date("Y-m-d", strtotime("last Monday",mktime(0,0,0,$monatDB,4,$jahrDB)));
	$datumScriptBIS=date("Y-m-d", strtotime("last Friday",mktime(0,0,0,$monatDB2,1,$jahrDB2)));
	$datumScriptMonat=strftime("%B",mktime(0,0,0,$monatDB,1,$jahrDB));

	if(($datumScriptVON !== $daten['vondat']) || ($datumScriptBIS !== $daten['bisdat']) || ($datumScriptMonat !== $daten['monat']))
	{
		array_push($datumDB[$key], $datumScriptMonat);
		array_push($datumDB[$key], $datumScriptVON);
		array_push($datumDB[$key], $datumScriptBIS);
	}

}


$count=(count($datumDB));

$last=$datumDB[$count-1];
$dateArray  = explode('-',$last['bisdat']);

$jahr1=$dateArray[0];
$monat1=$dateArray[1]+1;

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

for($x=1;$x<=12-$count;$x++)
{
	$datumSchleife=array("monat"=>strftime("%B",mktime(0,0,0,$monat1,1,$jahr1)), "vondat"=>date("Y-m-d", strtotime("last Monday",mktime(0,0,0,$monat1,4,$jahr1))), "bisdat"=>date("Y-m-d", strtotime("last Friday",mktime(0,0,0,$monat2,1,$jahr2))));
	array_push($datumNEU, $datumSchleife);
	
	
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
echo"<br>";



echo("<H2>Einträge für die nächsten 12 Monate</H2>");
echo("<table border=0>");
echo("<tr>
			<th scope='col'>Status</th>
        	<th scope='col'>Monat</th>
            <th scope='col'>Von</th>
            <th scope='col'>Bis</th>
        </tr>");
foreach ($datumDB AS $key=>$datenDB)
{
	$tableMonat=$datenDB['monat'];
	$tableVON=$datenDB['vondat'];
	$tableBIS=$datenDB['bisdat'];
	echo("<tr>");
	echo("	<td>Bereits in der DB.</td>
			<td> <input type='text' size='15' value='$tableMonat' name='monat'> </td>
			<td> <input type='text' size='15' value='$tableVON' name='vondat'> </td>
			<td> <input type='text' size='15' value='$tableBIS' name='bisdat'> </td>
			<td> <input type='submit' value='Speichern'> </td>");
	echo("</tr>");

	echo("</form>");

	if(isset($datenDB[0]))
	{
		$korrekturMonat=$datenDB[0];
		$korrekturVON=$datenDB[1];
		$korrekturBIS=$datenDB[2];

		echo("<form action='$wohin' method='post' enctype='multipart/form-data'>");
		echo("<tr>");
		echo("	<td>Möglicherweise nicht korrekt?</td>
				<td>$korrekturMonat</td>
				<td>$korrekturVON</td>
				<td>$korrekturBIS</td>");
		echo("</tr>");	
	}

	echo("<tr>");
	echo("	<td height='30'>  </td>
			<td>  </td>
			<td>  </td>
			<td>  </td>");
	echo("</tr>");
}

$i=1;

foreach ($datumNEU AS $key=>$datenDB)
{
	$tableMonat=$datenDB['monat'];
	$tableVON=$datenDB['vondat'];
	$tableBIS=$datenDB['bisdat'];
	if($i==1)
	{
		$disable="";
		$status="Neuen Eintrag erstellen.";
	}
	else
	{
		$disable="disabled";
		$status="Bitte erst den Vormonat speichern.";
	}

	echo("<form action='$wohin' method='post' enctype='multipart/form-data'>");
	echo("<tr>");
	echo("	<td>$status</td>
			<td> <input type='text' size='15' value='$tableMonat' name='monat' $disable> </td>
			<td> <input type='text' size='15' value='$tableVON' name='vondat' $disable> </td>
			<td> <input type='text' size='15' value='$tableBIS' name='bisdat' $disable> </td>
			<td> <input type='submit' value='Speichern' $disable> </td>");
	echo("</tr>");

	echo("</form>");

	echo("<tr>");
	echo("	<td height='30'>  </td>
			<td>  </td>
			<td>  </td>
			<td>  </td>");
	echo("</tr>");
		
	$i++;
}


echo("</table>");



/*

	echo(strftime("%B",mktime(0,0,0,$monat1,1,$jahr1)));
	echo"<br>";
	echo date("Y-m-d", strtotime("last Monday",mktime(0,0,0,$monat1,4,$jahr1)));
	echo"<br>";
	echo date("Y-m-d", strtotime("last Friday",mktime(0,0,0,$monat2,1,$jahr2)));
	echo"<br>";
	echo"<br>";

echo(sprintf("%'.02d\n", $monat1));


*/







?>