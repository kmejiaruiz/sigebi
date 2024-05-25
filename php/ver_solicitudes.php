<?php
require '../templates/header.php';
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
        $prestamoQuery = $pdo->prepare('SELECT p.libro_id, u.username AS usuario_nombre FROM prestamos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?');
        $prestamoQuery->execute([$prestamo_id]);
        $prestamo = $prestamoQuery->fetch();

        $updateQuery = $pdo->prepare('UPDATE libros SET disponible = 0 WHERE id = ?');
        $updateQuery->execute([$prestamo['libro_id']]);

        // Guardar notificación para el usuario
        $_SESSION['notificacion_usuario'] = "Un administrador ha aprobado tu solicitud";

        // Mostrar alerta para el administrador
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Solicitud Aprobada',
                    text: 'Has aceptado la solicitud del usuario {$prestamo['usuario_nombre']}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'ver_solicitudes.php';
                });
            });
        </script>";
    } elseif ($accion == 'rechazar') {
        $query = $pdo->prepare('UPDATE prestamos SET estado = "rechazado" WHERE id = ?');
        $query->execute([$prestamo_id]);

        // Obtener el nombre del usuario para la notificación
        $usuarioQuery = $pdo->prepare('SELECT u.username FROM prestamos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?');
        $usuarioQuery->execute([$prestamo_id]);
        $usuario = $usuarioQuery->fetch();

        // Guardar notificación para el usuario
        $_SESSION['notificacion_usuario'] = "Lo sentimos, tu solicitud de préstamo ha sido rechazada";

        // Mostrar alerta para el administrador
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Solicitud Rechazada',
                    text: 'La solicitud de {$usuario['username']} ha sido rechazada',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'ver_solicitudes.php';
                });
            });
        </script>";
    }
    exit;
}

$query = $pdo->query('SELECT p.*, u.username, l.titulo FROM prestamos p JOIN usuarios u ON p.usuario_id = u.id JOIN libros l ON p.libro_id = l.id WHERE p.estado = "pendiente"');
$solicitudes = $query->fetchAll();
?>

<title>Solicitudes de Préstamo</title>
<div class="container">
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
</div>

<?php require '../templates/footer.php';
