<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';
require '../templates/header.php';

$query = $pdo->query('SELECT * FROM libros');
$libros = $query->fetchAll();
?>


<title>Catálogo de Libros</title>

<div class="container">
    <h1>Catálogo de Libros</h1>
    <ul>
        <?php foreach ($libros as $libro) : ?>
            <li><?= htmlspecialchars($libro['titulo']) ?> por <?= htmlspecialchars($libro['autor']) ?> escrito en <?= htmlspecialchars(($libro['ano_publicacion'])) ?>
                <?= $libro['disponible'] ? '(Disponible)' : '(No Disponible)' ?></li> <span><a href="solicitar_prestamo.php?id=<?= $libro['id'] ?>">Solicitar Prestamo</a></span>

        <?php endforeach; ?>
    </ul>
</div>

<?php require_once '../templates/footer.php';
