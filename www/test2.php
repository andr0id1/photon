<?php

require("../inc/includes.php"); 

echo_r($_POST);



global $db;
$sql=
"SELECT klasse.klasse_id, klasse.klasse FROM klasse
JOIN lehrer
ON lehrer.lehrer_id=klasse.lehrer_id
WHERE lehrer.wins='Bold'
";

$result=$db->query($sql);

echo_r($result);



echo '<form name="form1" action="test2.php" method="post" enctype="multipart/form-data">';
echo "<p></p><table border='1'>";

foreach($result AS $key=>$k)
{
	switch($key)
	{
		case 'klasse_id':
		

		case 'klasse':
		echo("<td width='20%'>$k</td>");

	}

}


				foreach ($result AS $id=>$nutzer)
				 { 	
                     $nummer=$nutzer['id'];
                     echo "<tr>"; 
					 echo("<td>$nummer</td>");
					foreach ($nutzer AS $ding=>$spalte)
						{
                            $name="$nummer"."["."$ding"."]";

							echo "<td width='20%'><input type='text' value='$spalte' name='$name' onchange='' size='3' ></td>";
							
						}
		
		
			echo"</tr>";
		
        
				  } // of foreach ($alle_nutzer
					
			echo '</table>';

            ?>


            <?php

			echo "</form>";
?>

test4
   