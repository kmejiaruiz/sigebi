<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['es_admin']) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prestamo_id = $_POST['prestamo_id'];
    $accion = $_POST['accion'];

    if ($accion == 'aprobar') {
        $query = $pdo->prepare('UPDATE prestamos SET estado = "aprobado" WHERE id = ?');
        $query->execute([$prestamo_id]);

        $prestamoQuery = $pdo->prepare('SELECT libro_id FROM prestamos WHERE id = ?');
        $prestamoQuery->execute([$prestamo_id]);
        $prestamo = $prestamoQuery->fetch();

        $updateQuery = $pdo->prepare('UPDATE libros SET disponible = 0 WHERE id = ?');
        $updateQuery->execute([$prestamo['libro_id']]);
    } elseif ($accion == 'rechazar') {
        $query = $pdo->prepare('UPDATE prestamos SET estado = "rechazado" WHERE id = ?');
        $query->execute([$prestamo_id]);
    }

    header('Location: ver_solicitudes.php');
}

$query = $pdo->query('SELECT p.*, u.username, l.titulo FROM prestamos p JOIN usuarios u ON p.usuario_id = u.id JOIN libros l ON p.libro_id = l.id WHERE p.estado = "pendiente"');
$solicitudes = $query->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Solicitudes de Préstamo</title>
</head>

<body>
    <h1>Solicitudes de Préstamo</h1>
    <table>
        <tr>
            <th>Usuario</th>
            <th>Libro</th>
            <th>Fecha de Préstamo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($solicitudes as $solicitud) : ?>
            <tr>
                <td><?= htmlspecialchars($solicitud['username']) ?></td>
                <td><?= htmlspecialchars($solicitud['titulo']) ?></td>
                <td><?= htmlspecialchars($solicitud['fecha_prestamo']) ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="prestamo_id" value="<?= $solicitud['id'] ?>">
                        <button type="submit" name="accion" value="aprobar">Aprobar</button>
                        <button type="submit" name="accion" value="rechazar">Rechazar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>