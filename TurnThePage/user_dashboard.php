<?php

session_start();
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Book.php';
require_once __DIR__ . '/components/header.php';

$database = new Database();
$db = $database->getConnection(); // <-- QUESTO NON DEVE essere null

$bookModel = new Book($db);
$books = $bookModel->getAllBooks();

// Gestione della prenotazione PULSANTE
    $message = '';
    $messageType = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reserve') {
        
        $book_id = (int) $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
        
        $loan_date = date('Y-m-d');           // Oggi
        $due_date  = date('Y-m-d', strtotime('+30 days')); // Scadenza: 30 giorni da oggi
    
        try {
            // Ricontrolliamo la disponibilità lato server
            $check = $db->prepare("SELECT copies_available FROM books WHERE id = ?");
            $check->execute([$book_id]);
            $book_check = $check->fetch();
    
            if ($book_check && $book_check['copies_available'] > 0) {
    
                // Inseriamo il prestito in loans
                $insert = $db->prepare("
                    INSERT INTO loans (user_id, book_id, loan_date, due_date, return_date, status)
                    VALUES (?, ?, ?, ?, NULL, 'in prestito')
                ");
                $insert->execute([$user_id, $book_id, $loan_date, $due_date]);
    
                // Scaliamo le copie disponibili
                $update = $db->prepare("
                    UPDATE books SET copies_available = copies_available - 1 WHERE id = ?
                ");
                $update->execute([$book_id]);
    
                $message = "✅ Prenotazione effettuata! Scadenza: " . date('d/m/Y', strtotime($due_date));
                $messageType = "success";
    
            } else {
                $message = "⚠️ Spiacente, il libro non è più disponibile.";
                $messageType = "error";
            }
    
        } catch (PDOException $e) {
            $message = "❌ Errore: " . htmlspecialchars($e->getMessage());
            $messageType = "error";
        }
    }
?>


<!DOCTYPE html> <!-- Dichiariamo che questo è un documento HTML5 -->
<html lang="it"> <!-- Impostiamo la lingua del documento a italiano -->
<head>
    <meta charset="UTF-8"> <!-- Impostiamo la codifica dei caratteri a UTF-8 per supportare tutti i caratteri speciali -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Rende la pagina responsive su dispositivi mobili -->
    <title>Catalogo - Turn The Page</title> <!-- Titolo della pagina che appare nella scheda del browser -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>"> <!-- Collegamento al file CSS per gli stili, con cache busting -->
    <!-- Aggiunge un parametro con il timestamp attuale: serve a forzare il browser a ricaricare il CSS ogni volta, evitando problemi di cache durante lo sviluppo-->
</head>

<body>
    <div class="container">
        <h1>Catalogo - Turn The Page</h1>
        <div class="books-grid">
<!-- Aggiunta PULSANTE --> 
<?php if (!empty($message)): ?>
    <div class="alert <?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
        <!-- -->

            <?php
            try {
                $query = "SELECT * FROM books ORDER BY title";
                $stmt = $db->query($query);
                $books = $stmt->fetchAll();
                foreach ($books as $book) {
                    ?>
                    <div class="book-card">

                        <img class="book-cover"
                             src="<?php 
                                 echo htmlspecialchars($book['cover_image'] ?? 'uploads/covers/default.jpg'); 
                                 ?>" 
                             alt="Copertina di <?php 
                                 // Attributo alt = testo alternativo se l'immagine non si carica
                                 // Importante per accessibilità (screen reader) e SEO
                                 echo htmlspecialchars($book['title']); 
                                 ?>">

                        <div class="book-title">
                            <?php 
                            echo htmlspecialchars($book['title']); 
                            ?>
                        </div>
                        <div class="book-author">
                            di <?php 
                            echo htmlspecialchars($book['author']); 
                            ?>
                        </div>

                        <div class="book-info">
                            <?php 
                            if (!empty($book['publisher'])): 
                            ?>
                                Editore: <?php echo htmlspecialchars($book['publisher']); ?><br>
                            <?php 
                            endif; 
                            ?>
                            
                            <?php 
                            if (!empty($book['year'])): 
                            ?>
                                Anno: <?php echo htmlspecialchars($book['year']); ?><br>
                            <?php 
                            endif; 
                            ?>
                            
                            <?php 
                            if (!empty($book['isbn'])): 
                            ?>
                                ISBN: <?php echo htmlspecialchars($book['isbn']); ?>
                            <?php 
                            endif; 
                            ?>
                        </div>
                        
                        <div class="copies <?php 
                            echo $book['copies_available'] > 0 ? 'available' : 'unavailable'; 
                            ?>">
                            
                            <?php 
                            if ($book['copies_available'] > 0) {
                                echo "Disponibili: " . $book['copies_available'] . "/" . $book['copies_total'];                                
                            } else {
                                echo "Non disponibile";
                            }
                            ?>
                        </div>
                        <!-- AGGIUNTE PULSANTE -->
                         <!-- Pulsante prenotazione -->

                         <div class="book-actions">
                            <?php if ($book['copies_available'] > 0): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="reserve">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn-reserve">📖 Prenota</button>
                                </form>
                            <?php else: ?>
                                <button class="btn-reserve disabled" disabled>Non disponibile</button>
                            <?php endif; ?>
                        </div> <!-- chiude .book-actions -->
                    </div> <!-- chiude .book-card -->

                    <?php
                }

            } catch (PDOException $e) {
                echo '<div style="grid-column: 1/-1; background: #f8d7da; padding: 20px; border-radius: 8px; color: #721c24;">';
                echo '<strong>Errore nel caricamento dei libri:</strong><br>';
                echo htmlspecialchars($e->getMessage());
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
<?php
require_once __DIR__ . '/components/footer.php';
?>
</html>