<?php
session_start();

// Destruir la sesión y redirigir al index
session_unset();
session_destroy();
header("Location: index.php");
exit();
