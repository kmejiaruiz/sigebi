<?php
require_once '../templates/header.php';
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['es_admin']) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libro_id = $_POST['libro_id'];

    try {
        // Código que podría lanzar una excepción
        $query = $pdo->prepare('DELETE FROM libros WHERE id = ?');
        $query->execute([$libro_id]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Error de clave foránea
            echo "No se puede eliminar el libro porque hay préstamos relacionados.";
        } else {
            // Otro tipo de error
            echo "Error: " . $e->getMessage();
        }
    }
    // $query = $pdo->prepare('DELETE FROM libros WHERE id = ?');
    // $query->execute([$libro_id]);

    header('Location: inicio.php');
}

$query = $pdo->query('SELECT * FROM libros');
$libros = $query->fetchAll();




?>


<title>Eliminar Libro</title>
<div class="container">
    <form method="post" action="">
        <label>Libro:</label>
        <select name="libro_id">
            <?php foreach ($libros as $libro) : ?>
                <option value="<?= $libro['id'] ?>"><?= htmlspecialchars($libro['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Eliminar</button>
    </form>
</div>

<?php require_once '../templates/footer.php'; ?>