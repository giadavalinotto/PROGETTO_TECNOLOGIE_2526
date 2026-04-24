<?php
session_start();
?>
<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <li><a href="user_loans.php">I miei prestiti</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="loan_history.php">Storico prestiti</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>