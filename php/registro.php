<?php
require 'db.php';
require '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];

    $query = $pdo->prepare('INSERT INTO usuarios (username, password, email, nombre) VALUES (?, ?, ?, ?)');
    $query->execute([$username, $password, $email, $nombre]);

    header('Location: ../index.php');
}
?>


<title>Registro de Usuario</title>

<form method="post" action="">
    <label>Usuario:</label>
    <input type="text" name="username" required>
    <label>Contraseña:</label>
    <input type="password" name="password" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Nombre:</label>
    <input type="text" name="nombre" required>
    <button type="submit">Registrar</button>
</form>
<?php require_once '../templates/footer.php'; ?>