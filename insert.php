<?php

session_start();

if(isset($_POST["class"]))
{
    // set the validation to ok
    $validation_OK=true;
    $class = $_POST["class"];
    
    /* ------------------ check = valid student name ----------------- */
    $studentName = filter_input( INPUT_POST, "studentName", FILTER_SANITIZE_STRING );
    // length of the name
    if ( (strlen($studentName)<3) )
    {
        $validation_OK=false;
        $_SESSION["e_studentName"]="Student's first name must have more than 2 characters.";
    }

    /* ------------------ check = valid student last name ----------------- */
    $studentLastName = filter_input( INPUT_POST, "studentLastName", FILTER_SANITIZE_STRING );
    // length of the last name
    if ( (strlen($studentLastName)<3) )
    {
        $validation_OK=false;
        $_SESSION["e_studentLastName"]="Student's last name must have more than 2 characters.";
    }

    /* ------------------ check = valid student avg mark ----------------- */
    $studentAvgMark = filter_input( INPUT_POST, "studentAvgMark", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
    // avg mark between 1.0 and 6.0
    if ( $studentAvgMark < 1.0 || $studentAvgMark > 6.0 )
    {
        $validation_OK=false;
        $_SESSION["e_studentAvgMark"]="Student's average mark must be between 1.0 and 6.0.";
    }
    
    /* ------------------ check = bot or not ----------------- */
    $secret = require_once 'secret.php';
    $botornot = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $botornot_decoded = json_decode($botornot);

    if ($botornot_decoded->success==false)
    {
        $validation_OK=false;
        $_SESSION["e_bot"]="Please confirm that you're not a bot.";
    }
    
    /* ------------------ remember data into session ----------------- */
    //$_SESSION['s_class'] = $class;
    $_SESSION["s_studentName"] = $studentName;
    $_SESSION["s_studentLastName"] = $studentLastName;
    $_SESSION["s_studentAvgMark"] = $studentAvgMark;
    
    /* ------------------ if everything okay - connect to database ----------------- */
    if ($validation_OK==true)
    {
        //echo "validation ok!";
        $_SESSION["s_validationOK"] = "New student has been added to the database.";
        try
        {
            require_once "database.php";
    
            // prepare the SQL query
            $insertQuery = $db->prepare(
            "INSERT INTO reznor_school_student VALUES ( NULL, :lastname, :firstname, :avgmark, :class )");
        
            // bind values to parameters
            $insertQuery->bindValue( ":lastname", $studentLastName, PDO::PARAM_STR );
            $insertQuery->bindValue( ":firstname", $studentName, PDO::PARAM_STR );
            $insertQuery->bindValue( ":avgmark", $studentAvgMark );
            $insertQuery->bindValue( ":class", $class, PDO::PARAM_INT );
    
            // execution of the query
            $insertQuery->execute();
        }
        catch(Exception $e) // catch any exceptions
        {
            $_SESSION["e_databaseException"] = "Server error. We are sorry for the inconvenience and ask to add a student at a later time.";
            //echo "<div>Developer info: ".$e."</div>";
        }
    }
}

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Insert student</title>
	
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.png">
    <script src="cookiealert/cookiealert_1_2.js"></script><script>CookieAlert.init();</script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

	<div id="container">

        <h1>Student Database</h1>

        <p><a href="./">List students</a> | <a href="insert.php">Insert students</a></p>

		<form action="insert.php" method="post">

            <div class="form-element">
                <label for="class"> Insert into class: </label>
                <select id="class" name="class" required>

                    <option value=""></option>
                    <?php require_once "show.php"; ?>
                
                </select>
            </div>
            
            <div class="form-element">
                <label for="studentName"> Enter the student's name: </label>
                <div><input type="text" id="studentName" name="studentName" required
                value="<?php if (isset($_SESSION["s_studentName"])){echo $_SESSION["s_studentName"]; unset($_SESSION["s_studentName"]);}?>"></div>
            </div>
        
            <div class="form-element">
                <label for="studentLastName"> Enter the student's last name: </label>
                <div><input type="text" id="studentLastName" name="studentLastName" required
                value="<?php if (isset($_SESSION["s_studentLastName"])){echo $_SESSION["s_studentLastName"]; unset($_SESSION["s_studentLastName"]);}?>"></div>
            </div>

            <div class="form-element">
                <label for="studentAvgMark"> Enter the student's average mark (1.0 - 6.0): </label>
                <div><input type="number" id="studentAvgMark" name="studentAvgMark" step="0.01" min="1.0" max="6.0" required
                value="<?php if (isset($_SESSION["s_studentAvgMark"])){echo $_SESSION["s_studentAvgMark"]; unset($_SESSION["s_studentAvgMark"]);}?>"></div>
            </div>

            <div class="g-recaptcha" data-sitekey="6Lc4UKUUAAAAADdrV0fml7TbDEn7DS_Ta359-txn"></div>

			<!-- Submit form -->
			<div class="form-element"><input type="submit" value="Insert student"></div>
		</form>

<?php
if (isset($_SESSION["e_studentName"]))
{
    echo "<div class='error'>".$_SESSION["e_studentName"]."</div>";
    unset($_SESSION["e_studentName"]);
}
if (isset($_SESSION["e_studentLastName"]))
{
    echo "<div class='error'>".$_SESSION["e_studentLastName"]."</div>";
    unset($_SESSION["e_studentLastName"]);
}
if (isset($_SESSION["e_studentAvgMark"]))
{
    echo "<div class='error'>".$_SESSION["e_studentAvgMark"]."</div>";
    unset($_SESSION["e_studentAvgMark"]);
}
if (isset($_SESSION["e_bot"]))
{
    echo '<div class="error">'.$_SESSION["e_bot"].'</div>';
    unset($_SESSION["e_bot"]);
}
if (isset($_SESSION["e_databaseException"]))
{
    echo "<div class='error'>".$_SESSION["e_databaseException"]."</div>";
    unset($_SESSION["e_databaseException"]);
}
if (isset($_SESSION["s_validationOK"]))
{
    echo "<div class='valid'>".$_SESSION["s_validationOK"]."</div>";
    echo "<div>Please check out the <a href='./'>Student List</a> to see the newly added student.</div>";
    unset($_SESSION["s_validationOK"]);
    unset($_SESSION["s_studentName"]);
    unset($_SESSION["s_studentLastName"]);
    unset($_SESSION["s_studentAvgMark"]);
}
?>

	</div> <!-- End container -->

</body>
</html>