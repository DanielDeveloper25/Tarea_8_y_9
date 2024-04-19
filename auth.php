<?php
require('libreria/principal.php');

// Iniciar la sesión
session_start();

// Procesar inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    $con = connection();

    // Preparar la consulta SQL
    $stmt = $con->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($clave, $row['clave_hash'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit();
        } else {
            $error_msg = urlencode("Usuario o contraseña incorrectos.");
            header("Location: login.php?error=$error_msg");
            exit();
        }
    } else {
        $error_msg = urlencode("Usuario o contraseña incorrectos.");
        header("Location: login.php?error=$error_msg");
        exit();
    }
    $stmt->close();
}

// Agregar usuario por defecto si no existe
$con = connection();
$sql = "SELECT * FROM usuarios WHERE nombre_usuario = 'adamix'";
$query = mysqli_query($con, $sql);
if (mysqli_num_rows($query) == 0) {
    $clave_hash = password_hash('pasemesolosilomeresco70', PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO usuarios (nombre_usuario, clave_hash) VALUES ('adamix', '$clave_hash')";
    mysqli_query($con, $sql_insert);
}
?>