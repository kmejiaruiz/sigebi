<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

$query = $pdo->query('SELECT * FROM libros');
$libros = $query->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Catálogo de Libros</title>
</head>

<body>
    <h1>Catálogo de Libros</h1>
    <ul>
        <?php foreach ($libros as $libro) : ?>
            <li><?= htmlspecialchars($libro['titulo']) ?> por <?= htmlspecialchars($libro['autor']) ?>
                <?= $libro['disponible'] ? '(Disponible)' : '(No Disponible)' ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>