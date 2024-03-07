<?php
session_start();

// Verifica si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logarse"])) {
    $usuario = trim($_POST["correo_electronico"]);
    $contrasenia = trim($_POST["contrasenia"]);

    // Conexion a BBDD
    try {
        $conexion = new PDO("mysql:host=localhost:3306;dbname=acceso_usuarios", "root", "");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL del correo en la bbdd. 
        $consulta = $conexion->prepare("SELECT * FROM acceso_usuarios.` usuarios` WHERE correo_electronico = :correo_electronico");
        $consulta->bindParam(":correo_electronico", $usuario);
        $consulta->execute();

        $usuarioEncontrado = $consulta->fetch(PDO::FETCH_ASSOC);

        // Verifica si se encontro el correo y si la contraseña coincide con la registrada.
        if ($usuarioEncontrado && password_verify($contrasenia, $usuarioEncontrado['contrasenia'])) {
            $_SESSION['correo_electronico'] = $usuario;
            header("Location: verificar.php");
            exit();
        } else {
            $mensajeError = "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}
// Mensaje con un enlace a la pagina de registro
$mensajeInicio = "No estas registrado <a href='registro.php'> Ir a Registrarse </a>.";


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Feedback-TerezaFranco</title>
</head>

<body>


    <?php
    // Mostrar un mensaje de Inicio o un Mensaje en caso de Error.
    if (isset($mensajeError)) {
        echo "<p>$mensajeError</p>";
    }
    if (isset($mensajeInicio)) {
        echo "<p>$mensajeInicio</p>";
    }
    ?>
    <h2>Iniciar sesión</h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="correo_electronico">Usuario:</label>
        <input type="email" name="correo_electronico" id="correo_electronico" placeholder="Correo electrónico" required autocomplete="username">
        <br>

        <label for="contrasenia">Contraseña:</label>
        <input type="password" name="contrasenia" id="contrasenia" placeholder="Contraseña" required autocomplete="current-password">
        <br>

        <input type="submit" name="logarse" value="Iniciar sesión">
    </form>

</body>

</html>