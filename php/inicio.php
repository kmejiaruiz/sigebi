<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['es_admin'];

$query = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Biblioteca</title>
</head>

<body>
    <h1>Bienvenido, <?= htmlspecialchars($user['username']) ?></h1>
    <?php if ($is_admin) : ?>
        <nav>
            <ul>
                <li><a href="anexar_libro.php">Anexar Libro</a></li>
                <li><a href="eliminar_libro.php">Eliminar Libro</a></li>
                <li><a href="crear_admin.php">Crear Administrador</a></li>
                <li><a href="ver_solicitudes.php">Ver Solicitudes de Préstamo</a></li>
            </ul>
        </nav>
    <?php endif; ?>
    <nav>
        <ul>
            <li><a href="catalogo.php">Catálogo de Libros</a></li>
            <li><a href="solicitar_prestamo.php">Solicitar Préstamo</a></li>
            <li><a href="devolver_libro.php">Devolver Libro</a></li>
            <li><a href="editar_perfil.php">Editar Perfil</a></li>
        </ul>
    </nav>
    <a href="logout.php">Cerrar Sesión</a>
</body>

</html>