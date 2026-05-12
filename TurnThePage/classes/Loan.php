<?php
class Loan {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getLoansByUser($user_id) {
        $sql = "SELECT l.id, b.title, l.loan_date, l.return_date, l.status 
                FROM loans l 
                JOIN books b ON l.book_id = b.id 
                WHERE l.user_id = ? 
                ORDER BY l.loan_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}