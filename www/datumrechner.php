<?php
/**
 * Created by PhpStorm.
 * User: andreclausen
 * Date: 23.03.17
 * Time: 17:24
 */

require("../inc/includes.php");
setlocale(LC_TIME, "de_DE");
date_default_timezone_set('Europe/Berlin');







$dateSQL =  date("Y-m-d",time());

$dateArray  = explode('-',$dateSQL);

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

for($x=1;$x<=12;$x++)
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



echo_r($datumNEU);


?>