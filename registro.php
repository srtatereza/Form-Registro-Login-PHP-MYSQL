<?php
session_start();


// Verifica si se envió el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrarse"])) {
    $usuarioRegistro = trim($_POST["correo_registro"]);
    $contraseniaRegistro = trim($_POST["contrasenia_registro"]);
    $contraseniaConfirmacion = trim($_POST["confirmar_contrasenia"]);

    // Verifica si las contraseñas coinciden
    if ($contraseniaRegistro !== $contraseniaConfirmacion) {
        $mensajeErrorRegistro = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    } else {
        // almacena la contraseña usando la función HASH. 
        $almacenarPassword = password_hash($contraseniaRegistro, PASSWORD_BCRYPT);
       
        // Conexion a BBDD
        try {
            $conexion = new PDO("mysql:host=localhost:3306; dbname=acceso_usuarios", "root", "");
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verifica si el usuario ya existe
            $UsuarioExistente = $conexion->prepare("SELECT * FROM acceso_usuarios.` usuarios` WHERE correo_electronico = :correo_electronico");
            $UsuarioExistente->bindParam(":correo_electronico", $usuarioRegistro);
            $UsuarioExistente->execute();

            if ($UsuarioExistente->rowCount() > 0) {
                $mensajeErrorRegistro = "El correo del usuario ya está en uso </a>.";
            } else {
                // Insertar un nuevo usuario
                $InsertarUsuario = $conexion->prepare("INSERT INTO acceso_usuarios.` usuarios` (correo_electronico, contrasenia) VALUES (:correo_electronico,
                 :contrasenia)");
                $InsertarUsuario->bindParam(":correo_electronico", $usuarioRegistro);
                $InsertarUsuario->bindParam(":contrasenia", $almacenarPassword);
                $InsertarUsuario->execute();

                $mensajeExitoRegistro = "Usuario registrado con éxito.";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}
// Mensaje con un enlace a la pagina de login
$mensajeRegistro = "Estas registrado <a href='login.php'> Inicia sesión </a>.";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Feedback-TerezaFranco</title>
</head>

<body>

    <?php
   // Mostrar un mensaje de Registro  o un Mensaje en caso de Error.
    if (isset($mensajeErrorRegistro)) {
        echo "<p>$mensajeErrorRegistro</p>";
    }
    if (isset($mensajeExitoRegistro)) {
        echo "<p>$mensajeExitoRegistro</p>";
    }
    if (isset($mensajeRegistro)) {
        echo "<p>$mensajeRegistro</p>";
    }
    ?>

    <h2>Registrarse</h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="correo_registro">Nuevo Usuario:</label>
        <input type="email" name="correo_registro" id="correo_registro" placeholder="Correo electrónico" autocomplete="username">
        <br>

        <label for="contrasenia_registro">Nueva Contraseña:</label>
        <input type="password" name="contrasenia_registro" id="contrasenia_registro" placeholder="Contraseña" required autocomplete="new-password">
        <br>

        <label for="confirmar_contrasenia">Repetir Contraseña:</label>
        <input type="password" name="confirmar_contrasenia" id="confirmar_contrasenia" placeholder="Confirmar Contraseña" required autocomplete="new-password">
        <br>        
        <input type="submit" name="registrarse" value="Registrarse">
    </form>

</body>

</html>