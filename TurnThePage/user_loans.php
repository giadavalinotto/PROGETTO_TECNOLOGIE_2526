<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storico Prestiti - Turn The Page</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="container">
        <section class="page-panel">
            <div class="page-header">
                <div>
                    <span class="eyebrow">Storico prestiti</span>
                    <h1>Storico dei tuoi prestiti</h1>
                    <p class="page-description">Controlla lo stato dei libri presi in prestito e resta aggiornato su restituzioni e scadenze.</p>
                </div>
                <a href="user_dashboard.php" class="button button-secondary">Torna alla Dashboard</a>
            </div>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-card">
                    <table class="loans-table">
                        <thead>
                            <tr>
                                <th>Titolo Libro</th>
                                <th>Data Prestito</th>
                                <th>Data Restituzione</th>
                                <th>Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['loan_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['return_date'] ?? 'Non restituito'); ?></td>
                                    <td><span class="status-pill <?php echo strtolower(str_replace(' ', '-', htmlspecialchars($row['status']))); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Non hai prestiti in corso al momento.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php require_once __DIR__ . '/components/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>