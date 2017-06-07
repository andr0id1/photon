<?php 

// Starte Session 

@session_start();


// Pfad zum Basis-Verzeichnis der Web-Anwendung

$basedir = dirname(__FILE__)."/..";
$basescript=$_SERVER["REQUEST_URI"];

// Include-Dateien 
require($basedir."/inc/dbconfig.php");
require($basedir."/inc/db.class.php");
require($basedir."/inc/hilfsfunktionen.php");
require($basedir."/inc/funktionen.php");

// Stelle Datenbankverbindung her 
// Verbindungsdaten stehen in dbconfig.php 

$db = new db();
$db->connect($mysqlServer, $mysqlUser, $mysqlPass, $mysqlDB);

?>
