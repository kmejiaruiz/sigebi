<?php
require_once '../templates/header.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $query = $pdo->prepare('UPDATE usuarios SET username = ?, email = ?, nombre = ?, password = ? WHERE id = ?');
        $query->execute([$username, $email, $nombre, $password, $user_id]);
    } else {
        $query = $pdo->prepare('UPDATE usuarios SET username = ?, email = ?, nombre = ? WHERE id = ?');
        $query->execute([$username, $email, $nombre, $user_id]);
    }

    header('Location: index.php');
}

$query = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch();
?>
<title>Editar Perfil</title>
<div class="container">
    <form method="post" action="">
        <label>Usuario:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
        <label>Contraseña (dejar en blanco para mantener la actual):</label>
        <input type="password" name="password">
        <button type="submit">Guardar Cambios</button>
    </form>
</div>

<?php require_once '../templates/footer.php'; ?>