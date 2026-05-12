<?php
// Connessione al database
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}

// Prendi tutti gli utenti
$stmt = $pdo->query("SELECT id, password FROM users");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $plainPassword = $row['password'];

    // Hash SEMPRE (niente controlli, sappiamo che sono in chiaro)
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    $update->execute([
        ':password' => $hashedPassword,
        ':id' => $id
    ]);

    echo "Utente ID $id aggiornato<br>";
}

echo "Migrazione completata.";
?>