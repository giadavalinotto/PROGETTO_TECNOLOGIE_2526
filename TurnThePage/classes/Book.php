<?php

class Book {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // metodo per prendere tutti i libri
    public function getAllBooks() {
        try {
            $query = "SELECT * FROM books ORDER BY title";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e; // oppure gestisci qui
        }
    }
}