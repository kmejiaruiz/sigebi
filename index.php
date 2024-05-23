<?php
session_start();
require 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare('SELECT * FROM usuarios WHERE username = ?');
    $query->execute([$username]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['es_admin'] = ($user['es_admin'] == 'admin');
        header('Location: php/inicio.php');
    } else {
        $error = 'Credenciales incorrectas';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inicio de Sesión</title>
</head>

<body>
    <form method="post" action="">
        <label>Usuario:</label>
        <input type="text" name="username" required>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <button type="submit">Ingresar</button>
    </form>
    <a href="php/registro.php">Agregar</a>
    <?php if (isset($error)) : ?>
        <p><?= $error ?></p>
    <?php endif; ?>
</body>

</html>