<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['correo_electronico'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Feedback-TerezaFranco</title>
</head>

<body>

    <h1>SESION INICIADA CORRECTAMENTE - ( Estas dentro del sistema).</h1>

    <!-- Mensaje al usuario, mostrando su correo de login -->

    <h2>Tu correo es: <?php echo $_SESSION['correo_electronico']; ?></h2>

    <!-- Enlace para cerrar la sesión-->
    <a href="cerrar.php">Cerrar sesión</a>

</body>
</html>