<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['es_admin']) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];

    $query = $pdo->prepare('INSERT INTO usuarios (username, password, email, nombre, es_admin) VALUES (?, ?, ?, ?, "admin")');
    $query->execute([$username, $password, $email, $nombre]);

    header('Location: inicio.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Administrador</title>
</head>

<body>
    <form method="post" action="">
        <label>Usuario:</label>
        <input type="text" name="username" required>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <button type="submit">Crear</button>
    </form>
</body>

</html>