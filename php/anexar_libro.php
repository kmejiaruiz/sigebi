<?php
require_once '../templates/header.php';
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['es_admin']) {
    header('Location: ../index.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano_publicacion = $_POST['ano_publicacion'];

    $query = $pdo->prepare('INSERT INTO libros (titulo, autor, ano_publicacion) VALUES (?, ?, ?)');
    $query->execute([$titulo, $autor, $ano_publicacion]);

    header('Location: inicio.php');
}
?>


<title>Anexar Libro</title>

<div class="container">
    <form method="post" action="">
        <label>Título:</label>
        <input type="text" name="titulo" required>
        <label>Autor:</label>
        <input type="text" name="autor" required>
        <label>Año de Publicación:</label>
        <input type="number" name="ano_publicacion" required>
        <button type="submit">Anexar</button>
    </form>
</div>

<?php require_once "../templates/footer.php";
