<?php
include_once 'includes/db.php';
include_once 'includes/config.php';
include_once 'controllers/BookController.php';

$bookController = new BookController($pdo);
$books = $bookController->listBooks();

include 'views/header.php';
?>

<h1>Բոլոր գրքերը</h1>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo $book['title']; ?> - <?php echo $book['author']; ?></li>
    <?php endforeach; ?>
</ul>

<?php include 'views/footer.php'; ?>