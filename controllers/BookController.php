<?php

include_once '../models/Book.php';

class BookController {
    private $book;

    public function __construct($pdo) {
        $this->book = new Book($pdo);
    }

    public function listBooks() {
        return $this->book->getAllBooks();
    }

    public function addBook($title, $author, $genre, $published_date, $isbn) {
        return $this->book->addBook($title, $author, $genre, $published_date, $isbn);
    }
}
?>