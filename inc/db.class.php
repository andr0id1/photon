<?php
class db
{
	// Verbindung zur Datenbank
	var $con = null;
	
	function db()
	{
		// ... Konstruktor - im Moment leer
	}

	// Stellt Verbindung zur Datenbank her
	function connect($server, $user="", $password="", $database="")
	{
		// Serververbindung
		if (($this->con = @mysql_connect($server, $user, $password))===false) 
		{
			$this->handle_error("Fehler beim Verbindungsversuch zu ".$server);
			$this->con = null;
			return false;
		}
		
		// Datenbankverbindung
		if (@mysql_select_db($database, $this->con)===false)
		{
			$this->handle_error(mysql_errno($this->con)." : ".mysql_error($this->con));
			return false;
		}	
	}
				
	// F�hrt einen SQL-Befehl aus
	function query($sql)
	{
		
		// Gibt es �berhaupt schon eine Verbindung zur Datenbank?
		if ($this->con===null)
		{
			$this->handle_error("Keine Verbindung zur Datenbank. Bitte zuerst connect() ausf�hren");	
			return false;
		}
		
		// Ergebnis-Array
		$rows = array();

		// F�hre den SQL-Befehl aus
		$rs = @mysql_query($sql, $this->con);
		
		// Sind Fehler aufgetreten?
		if ($rs===false) 
		{
			$this->handle_error("Error: ".mysql_errno($this->con)." : ".mysql_error($this->con)." - SQL: ".htmlentities($sql));
			return false;
		}
		
		// Wurde etwas zur�ckgegeben? 
		// Bei INSERTS/UPDATES ist rs===true, sonst enth�lt es den Recordset
		if ($rs===true)
		{
			return true;	
		}
		else 
		{	// Sammle Ergebniseingtr�ge (Datenbankzeilen) in Array
			while ($_rows = @mysql_fetch_array($rs, MYSQL_ASSOC))
			{
				// Fange leere Eintr�ge ab.
				if (count($_rows)==0) continue;

				$rows[] = $_rows;
			}
		}

		return $rows;
	}

	// Schlie�t die Verbindung zur Datenbank wieder
	function close()
	{
		if (@mysql_close($this->con)===false)
		{
			$this->handle_error(mysql_errno($this->con)." : ".mysql_error($this->con));
			return false;	
		} 	
	}
	
	// Behandelt aufgetretene Fehler
	function handle_error($fehler)
	{
		// Nur dann einen Fehler ausgeben, wenn auf lokaler Entwicklungsmaschine
		if ($_SERVER['HTTP_HOST']=='localhost')
		{
			echo "<br />Es ist ein Datenbank-Fehler aufgetreten: ". $fehler;
		}
	}

}
?>
