<?php

// logout.php or similar
session_start();
session_destroy();
header("Location: ../login.php");
exit();
?>