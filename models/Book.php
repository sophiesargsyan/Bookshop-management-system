<?php

class Book {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllBooks() {
        $stmt = $this->pdo->query("SELECT * FROM books");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBook($title, $author, $genre, $published_date, $isbn) {
        $stmt = $this->pdo->prepare("INSERT INTO books (title, author, genre, published_date, isbn) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $genre, $published_date, $isbn]);
    }
}
?>