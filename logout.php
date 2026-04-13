<?php
session_start();
session_destroy();
header("Location: /lexora_mlk/index.php");
exit;
?>