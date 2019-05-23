<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>School Journal</title>
	
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="stylesheet" href="main.css">

</head>

<body>

	<div id="container">

		<form action="index.php" method="post">
			<label>
				<p>Type the class name:</p>
				<p><input type="text" name="class"></p>
			</label>
			<p><input type="submit" value="Show marks"></p>
			<p>Available classes: 1a, 1b, 2a, 2b</p>
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
			require_once "dbconnect-reznor.php"; // once unika redefinicji
			
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
			
			$q = "SELECT nazwa, Imie, Nazwisko, Srednia_ocen FROM reznor_school_student, reznor_school_class WHERE nazwa='$class' AND reznor_school_class.id = reznor_school_student.id_klasy";
			
			$result = mysqli_query($conn, $q) or die("Database read error");
			
			$ile = mysqli_num_rows($result);
			
			if(!$ile)
			{
				echo '<span style="color:red">This class name doesn\'t exist!</span>';
			}
			else
			{
echo<<<END
<table id="result-table">
	<thead>
		<tr>
			<th>Class</th>
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

			echo "<p>Average class mark of all students: <b>".round($suma/$ile, 2)."</b></p>";
			echo "<p>Sum of average marks of all students: <b>".$suma."</b></p>";
			echo "<p>Number of all students in class <b>".$class."</b> : <b>".$ile."</b></p>";
			//var_dump($suma);
			//var_dump($ile);
			
			}	
			
			mysqli_close($conn); // zamkniecie polaczenia
		}
		
	}
	
	
?>
	</div> <!-- End container -->

</body>
</html>