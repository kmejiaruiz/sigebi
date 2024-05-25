<?php
require_once '../templates/header.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libro_id = $_POST['libro_id'];
    $usuario_id = $_SESSION['user_id'];
    $fecha_prestamo = date('Y-m-d');

    $query = $pdo->prepare('INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) VALUES (?, ?, ?, "pendiente")');
    $query->execute([$libro_id, $usuario_id, $fecha_prestamo]);

    $solicitud_exito = true;
}

$query = $pdo->query('SELECT * FROM libros WHERE disponible = 1');
$libros = $query->fetchAll();
?>


<title>Solicitar Préstamo</title>
<div class="container">
    <form method="post" action="">
        <label>Libro:</label>
        <select name="libro_id">
            <?php foreach ($libros as $libro) : ?>
                <option value="<?= $libro['id'] ?>"><?= htmlspecialchars($libro['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Solicitar</button>
        <a href="inicio.php"><button type="button">Regresar</button></a>

        <?php if (isset($solicitud_exito) && $solicitud_exito) : ?>
            <script>
                Toastify({
                    text: "Préstamo solicitado correctamente",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4CAF50",
                    stopOnFocus: true,
                }).showToast();
            </script>
        <?php endif; ?>
    </form>
</div>

<?php require_once '../templates/footer.php';
