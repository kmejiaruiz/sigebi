<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prestamo_id = $_POST['prestamo_id'];

    $query = $pdo->prepare('UPDATE prestamos SET devuelto = 1, fecha_devolucion = ? WHERE id = ?');
    $query->execute([date('Y-m-d'), $prestamo_id]);

    $prestamoQuery = $pdo->prepare('SELECT libro_id FROM prestamos WHERE id = ?');
    $prestamoQuery->execute([$prestamo_id]);
    $prestamo = $prestamoQuery->fetch();

    $updateQuery = $pdo->prepare('UPDATE libros SET disponible = 1 WHERE id = ?');
    $updateQuery->execute([$prestamo['libro_id']]);

    header('Location: inicio.php');
}

$user_id = $_SESSION['user_id'];
$query = $pdo->prepare('SELECT p.id, l.titulo FROM prestamos p JOIN libros l ON p.libro_id = l.id WHERE p.usuario_id = ? AND p.devuelto = 0 AND p.estado = "aprobado"');
$query->execute([$user_id]);
$prestamos = $query->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Devolver Libro</title>
</head>

<body>
    <form method="post" action="">
        <label>Libro a Devolver:</label>
        <select name="prestamo_id">
            <?php foreach ($prestamos as $prestamo) : ?>
                <option value="<?= $prestamo['id'] ?>"><?= htmlspecialchars($prestamo['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Devolver</button>
    </form>
</body>

</html>