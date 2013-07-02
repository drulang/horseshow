<?php

session_start();

session_destroy();

$serverLocation = "http://".$_SERVER['SERVER_NAME']."/horseshow/";

header("Location: ".$serverLocation."index.php");
?>
