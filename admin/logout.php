<?php
include("../dtbs.php");
include("../functions.php");
session_start();

// Destroy the session variables
session_destroy();
header("Location: ../login.php");
exit();
?>
