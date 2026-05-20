<?php
session_start();
session_destroy();
header("Location: organization_login.php");
exit();
?>