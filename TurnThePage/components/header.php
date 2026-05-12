<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <nav class="site-nav">
        <ul class="nav-list">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <li><a href="user_loans.php">I miei prestiti</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="loan_history.php">Storico prestiti</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>