<?php
require("config.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // the form has fields for name, email, and message
    $q = trim($_GET["q"]);
    $units = trim($_GET["units"]);
}

if (empty($q)){
	header("Location:../?error=q");
} else {
	$q = str_replace (" ", "+", urlencode($q));
	header("Location:../?q=".$q."&units=".$units);
}