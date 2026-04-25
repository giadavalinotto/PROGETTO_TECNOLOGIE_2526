<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { // TODO: usare password hashing in produzione e verificare con password_verify()
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php');
            exit;
        } else {
            header('Location: user_dashboard.php');
            exit;
        }
    } else {
        $error = 'Credenziali errate.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Turn The Page</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card">
            <div class="auth-header">
                <span class="eyebrow">Accesso</span>
                <h1 class="auth-title">Bentornato</h1>
            </div>
    <?php if ($error): ?>
                <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

            <form class="auth-form" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <div class="auth-actions">
                    <button type="submit" class="button">Accedi</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>