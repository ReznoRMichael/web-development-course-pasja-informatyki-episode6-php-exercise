<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>School Journal</title>
	
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
	<link rel="stylesheet" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
	<link rel="icon" href="favicon.png">
	<script src="cookiealert/cookiealert_1_2.js"></script><script>CookieAlert.init();</script>

</head>

<body>

	<div id="container">

	<h1>School Journal</h1>

	<p><a href="./">List students</a> | <a href="insert.php">Insert students</a></p>

		<form action="index.php" method="post">

			<div class="form-element">
				<label for="class"> Select the class name: </label>
				<select id="class" name="class">

					<option value=""></option>
					<?php include_once "show.php"; ?>
				
				</select>
			</div>
			
			<p><input type="submit" value="Show marks"></p>
		</form>

	<?php include "read.php"; ?>

	</div> <!-- End container -->

</body>
</html>