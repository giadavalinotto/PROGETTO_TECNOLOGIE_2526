<?php

include 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'biblioteca');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once 'classes/Loan.php';

$loans = new Loan($conn);
$result = $loans->getLoansByUser($user_id);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Storico Prestiti</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Storico dei Tuoi Prestiti</h1>
    <table>
        <tr>
            <th>Titolo Libro</th>
            <th>Data Prestito</th>
            <th>Data Restituzione</th>
            <th>Stato</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['loan_date']); ?></td>
            <td><?php echo htmlspecialchars($row['return_date'] ?? 'Non restituito'); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="user_dashboard.php">Torna alla Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>