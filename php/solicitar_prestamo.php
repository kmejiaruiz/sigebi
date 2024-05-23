<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libro_id = $_POST['libro_id'];
    $usuario_id = $_SESSION['user_id'];
    $fecha_prestamo = date('Y-m-d');

    $query = $pdo->prepare('INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, estado) VALUES (?, ?, ?, "pendiente")');
    $query->execute([$usuario_id, $libro_id, $fecha_prestamo]);

    header('Location: inicio.php');
}

$query = $pdo->query('SELECT * FROM libros WHERE disponible = 1');
$libros = $query->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Solicitar Préstamo</title>
</head>

<body>
    <form method="post" action="">
        <label>Libro:</label>
        <select name="libro_id">
            <?php foreach ($libros as $libro) : ?>
                <option value="<?= $libro['id'] ?>"><?= htmlspecialchars($libro['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Solicitar</button>
    </form>
</body>

</html>