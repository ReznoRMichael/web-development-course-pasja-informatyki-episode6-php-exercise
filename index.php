<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Dziennik szkolny</title>
	
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="stylesheet" href="main.css">

</head>

<body>

<form action="index.php" method="post">

	<label>
		Type the class name: <input type="text" name="class">
	</label>
	
	<input type="submit" value="Show marks">

</form>

<?php
	
	if(isset($_POST["class"]))
	{
		if(empty($_POST["class"]))
		{
			echo '<span style="color:yellow">You didn\'t enter a class!</span>';
		}
		else
		{
			//include "dbconnect.php"; // include_once, require_once
			require_once "dbconnect.php"; // once unika redefinicji
			
			$conn = mysqli_connect($host, $user , $pass, $db) or die("Connection error"); // połącz się lub umrzyj
			
			mysqli_set_charset($conn, "utf8");
			
			/*if(!$conn)
			{
				echo "Connection error";
			}
			else
			{
				echo "Connection successful";
			}*/
			
			$class = $_POST["class"];
			
			$q = "SELECT Imie, Nazwisko, Srednia_ocen FROM uczen, klasa WHERE nazwa='$class' AND klasa.id = uczen.id_klasy";
			
			$result = mysqli_query($conn, $q) or die("Database read error");
			
			$ile = mysqli_num_rows($result);
			
			if(!$ile)
			{
				echo '<span style="color:red">This class name doesn\'t exist!</span>';
			}
			else
			{
echo<<<END
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Surname</th>
			<th>Average mark</th>
		</tr>
	</thead>
	<tbody>
END;

				$suma = 0;
				
				while($row = mysqli_fetch_assoc($result))
				{
					//var_dump($row); exit();
					echo "\r\n\t\t\t<tr>";
					foreach($row as $col)
					{
						echo "<td>$col</td>";
						//$suma += $row["Srednia_ocen"];
					}
					echo "</tr>";
					$suma += $row["Srednia_ocen"];
					//echo "\r\n\t\t\t<tr><td>".$row["Imie"]."</td><td>".$row["Nazwisko"]."</td><td>".$row["Srednia_ocen"]."</td></tr>";
				}
echo<<<END
\r\n
	</tbody>
</table>
END;

			echo "<p>Average class marks: ".round($suma/$ile, 2)."</p>";
			var_dump($suma);
			var_dump($ile);
			
			}	
			
			mysqli_close($conn); // zamkniecie polaczenia
		}
		
	}
	
	
?>

</body>
</html>