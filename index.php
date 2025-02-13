<?php
include_once 'includes/db.php';
include_once 'includes/config.php';
include_once 'controllers/BookController.php';

$bookController = new BookController($pdo);
$books = $bookController->listBooks();

include 'views/header.php';
?>

<main>
    <h1>Բոլոր գրքերը</h1>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?> - <?php echo htmlspecialchars($book['author'], ENT_QUOTES); ?></li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include 'views/footer.php'; ?>