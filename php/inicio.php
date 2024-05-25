<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';
require '../templates/header.php';

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['es_admin'];

$query = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$query->execute([$user_id]);
$user = $query->fetch();

$solicitudesPendientes = [];
if ($is_admin) {
    $query = $pdo->query('SELECT COUNT(*) as total FROM prestamos WHERE estado = "pendiente"');
    $result = $query->fetch();
    if ($result['total'] > 0) {
        $solicitudesPendientes = $result['total'];
    }
}

$notificacion_usuario = '';
if (isset($_SESSION['notificacion_usuario'])) {
    $notificacion_usuario = $_SESSION['notificacion_usuario'];
    unset($_SESSION['notificacion_usuario']);
}
?>


<title>Biblioteca</title>
<h1>Bienvenido, <?= htmlspecialchars($user['username']) ?></h1>
<div class="container"><?php if ($is_admin) : ?>
        <nav>
            <ul>
                <li><a href="anexar_libro.php">Anexar Libro</a></li>
                <li><a href="eliminar_libro.php">Eliminar Libro</a></li>
                <li><a href="crear_admin.php">Crear Administrador</a></li>
                <li><a href="ver_solicitudes.php">Ver Solicitudes de Préstamo</a></li>
            </ul>
        </nav>
        <?php if (!empty($solicitudesPendientes)) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Nuevas Solicitudes de Préstamo',
                        text: 'Tienes <?= $solicitudesPendientes ?> solicitudes de préstamo pendientes. ¿Quieres verlas?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, ver solicitudes',
                        cancelButtonText: 'No, después'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'ver_solicitudes.php';
                        }
                    });
                });
            </script>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    if (!empty($notificacion_usuario)) : ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Estado de Solicitud',
                    text: '<?= htmlspecialchars($notificacion_usuario) ?>',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php endif; ?>


    <nav>
        <ul>
            <li><a href="catalogo.php">Catálogo de Libros</a></li>
            <li><a href="solicitar_prestamo.php">Solicitar Préstamo</a></li>
            <li><a href="devolver_libro.php">Devolver Libro</a></li>
            <li><a href="editar_perfil.php">Editar Perfil</a></li>
        </ul>
    </nav>

</div>
<a href="logout.php">Cerrar Sesión</a>

<?php require '../templates/footer.php';
