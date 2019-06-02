<?php
	require_once "database.php"; // include_once, require_once (once unika redefinicji)

	$sqlQuery = $db->prepare(
		"SELECT
			*
		FROM
			reznor_school_class"); // przygotowanie zapytania SQL

	$sqlQuery->execute(); // execution of the query
	$numRows = $sqlQuery->rowCount(); // count how many rows there are in the database

	if(!$numRows)
	{
		echo "<span style='color:red'>There are no classes in the database!</span>";
	}
	else
	{
		while( $classDB = $sqlQuery->fetch(PDO::FETCH_ASSOC) )
		{
			//var_dump($user); exit();
			echo "<option value='".$classDB["id"]."'>Class ".$classDB["nazwa"]."</option>";
        }
	}
?>