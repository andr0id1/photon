<?php
/**
 * Created by PhpStorm.
 * User: andreclausen
 * Date: 23.03.17
 * Time: 19:15
 */


require("../inc/includes.php");

/*
setlocale(LC_TIME, "de_DE");
date_default_timezone_set('Europe/Berlin');

$vonJahr=2017;
$vonMonat=03;
$vonTag=30;

$date=date("t",mktime(0,0,0,$vonMonat,1,$vonJahr));
echo $date;

if(date("t",mktime(0,0,0,$vonMonat,1,$vonJahr))<=$vonTag)
{
    echo "ja";
}
else
{
    echo " nein";
}



$jahr=2019;
$tage = 60 * 60 * 24;
$ostersonntag = easter_date($jahr);
$feiertage[] = date("dm", $ostersonntag + 40 * $tage); // Pfingstferien

echo_r($feiertage);
*/

/*

$sql = 'SELECT Von,Bis FROM Ferien WHERE Jahr=2017';
$alleFerien=$db->query($sql);

foreach($alleFerien AS $key=>$Ferien)
{
    echo_r($Ferien);
    $vonFerien=explode('-',$Ferien['Von']);
    $vonTag=$vonFerien[2];
    $vonMonat=$vonFerien[1];
    $vonJahr=$vonFerien[0];
    echo_r($vonTag);
    echo_r($vonMonat);

    $bisFerien=explode('-',$Ferien['Bis']);
    if($vonMonat==12)
    {
        $bisMonat=12;
        $bisTag=31;
    }
    else
    {
        $bisTag=$bisFerien[2];
        $bisMonat=$bisFerien[1];
    }

    $bisJahr=$bisFerien[0];
    echo_r($bisTag);
    echo_r($bisMonat);



    while (($vonTag<=$bisTag)or($vonMonat<$bisMonat))
    {
        $vonTagCorr=sprintf("%'.02d",$vonTag);
        $vonMonatCorr=sprintf("%'.02d",$vonMonat);
        $feiertage = "$vonTagCorr"."$vonMonatCorr";
        echo "$feiertage<br>";

        if((date("t",mktime(0,0,0,$vonMonat,1,$vonJahr))<=$vonTag)and($vonMonat<$bisMonat))
        {
            $vonMonat++;
            $vonTag=01;
        }
        else
        {
            $vonTag++;
        }
    }

}
*/

if(wocheFrei(28,07,2017))
{
    echo "in dieser woche ist ein freier tag";
}
else
{
    echo "die woche hat keinen freien tag";
}





?>