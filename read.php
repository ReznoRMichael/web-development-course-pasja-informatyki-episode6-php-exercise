<?php
if(isset($_POST["class"]))
{
    if(empty($_POST["class"]))
    {
        echo "<p>Please enter a class.</p>";
    }
    else
    {
        $class = $_POST["class"];

        require_once "database.php"; // include_once, require_once (once unika redefinicji)

        $sqlQuery = $db->prepare(
        "SELECT
            C.nazwa, S.Imie, S.Nazwisko, S.Srednia_ocen
        FROM
            reznor_school_student AS S, reznor_school_class AS C
        WHERE
            C.id = :class
        AND
            C.id = S.id_klasy"); // przygotowanie zapytania SQL

        $sqlQuery->bindValue(':class', $_POST["class"], PDO::PARAM_STR); // przypisanie wartosci do etykiety :class
        $sqlQuery->execute(); // execution of the query

        $numRows = $sqlQuery->rowCount(); // count how many rows there are in the database
        
        if(!$numRows)
        {
            echo "<p style='color:red'>This class name doesn't exist!</p>";
        }
        else
        {
            $sqlQueryT = $db->prepare(
            "SELECT
                T.imie, T.nazwisko, C.nazwa
            FROM
                reznor_school_teacher AS T, reznor_school_class AS C, reznor_school_student AS S
            WHERE
                C.id = :class
            AND
                C.id = S.id_klasy
            AND
                C.id = T.id_klasy"); // przygotowanie zapytania SQL

            $sqlQueryT->bindValue(':class', $class, PDO::PARAM_STR); // przypisanie wartosci do etykiety :class
            $sqlQueryT->execute(); // execution of the query
            $teacher = $sqlQueryT->fetch(PDO::FETCH_ASSOC);

            echo "<p>Class <b>".$teacher["nazwa"]."</b> Teacher: <b>".$teacher["imie"]." ".$teacher["nazwisko"]."</b></p>";
echo<<<END
<table id="result-table">
	<thead>
		<tr>
			<th>No.</th>
			<th>Class</th>
			<th>Name</th>
			<th>Surname</th>
			<th>Average mark</th>
		</tr>
	</thead>
	<tbody>
END;
				$avgMark = 0;
				$No = 1;

				// fetch the data and save as an associative array (with names) - the default fetch mode is both
				while( $user = $sqlQuery->fetch(PDO::FETCH_ASSOC) )
				{
					//var_dump($user); exit();
					echo "\r\n\t\t\t<tr>";
					echo "<td>".$No++."</td>";

					foreach($user as $col)
					{
						echo "<td>".$col."</td>";
						//$avgMark += $row["Srednia_ocen"];
					}
					echo "</tr>";
					$avgMark += $user["Srednia_ocen"];
				}
echo<<<END
\r\n
	</tbody>
</table>
END;

        echo "<p>Average class mark of all students: <b>".round($avgMark/$numRows, 2)."</b></p>";
        echo "<p>Sum of average marks of all students: <b>".$avgMark."</b></p>";
        echo "<p>Number of all students in class <b>".$teacher["nazwa"]."</b> : <b>".$numRows."</b></p>";
        //var_dump($avgMark);
        //var_dump($numRows);
        
        }	// end else !$numRows
    }	// end else empty($_POST["class"])
}	//end if isset($_POST["class"])
	
?>