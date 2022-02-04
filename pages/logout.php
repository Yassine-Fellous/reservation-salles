<?php
session_start();
session_destroy();
header('Location:/reservation-salles/index.php');
exit;
?>