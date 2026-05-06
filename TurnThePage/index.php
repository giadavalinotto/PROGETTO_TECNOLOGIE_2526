<?php

require_once __DIR__ . '/config/Database.php'; // include del file che contiene la classe Database

// Istanzio la classe database e ottengo l'oggetto PDO
$database = new Database();
$db = $database->getConnection();

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
        <div class="books-grid"> <!-- Contenitore a griglia per le card dei libri -->
            <?php

            // ============================================
            // BLOCCO TRY-CATCH PER GESTIONE ERRORI
            // ============================================
            
            try {
                // Il blocco try contiene il codice che potrebbe generare errori
                // Se si verifica un errore, l'esecuzione salta al blocco catch
                
                // ============================================
                // QUERY SQL: Recupera tutti i libri
                // ============================================
                
                // Creiamo la stringa SQL che verrà eseguita sul database
                $query = "SELECT * FROM books ORDER BY title";
                // SELECT * = seleziona tutte le colonne della tabella
                // FROM books = dalla tabella chiamata 'books'
                // ORDER BY title = ordina i risultati alfabeticamente per titolo (A-Z)
                
                // ============================================
                // ESECUZIONE QUERY
                // ============================================
                
                // $db->query() = metodo dell'oggetto PDO che esegue una query SQL
                // Parametro: la stringa SQL da eseguire
                // Ritorna: un oggetto PDOStatement che contiene i risultati
                $stmt = $db->query($query);
                // $stmt = "statement" (istruzione), contiene i risultati della query
                
                // ============================================
                // RECUPERO RISULTATI
                // ============================================
                
                // fetchAll() = metodo che recupera TUTTI i risultati della query
                // Ritorna: array di array associativi
                // Ogni elemento dell'array è un libro (riga della tabella)
                // Ogni libro è un array associativo: ['id' => 1, 'title' => '1984', ...]
                $books = $stmt->fetchAll();
                // Grazie a PDO::FETCH_ASSOC impostato in database.php,
                // otteniamo array con chiavi = nomi delle colonne
                
                // Esempio di $books:
                // [
                //     0 => ['id' => 1, 'title' => '1984', 'author' => 'Orwell', ...],
                //     1 => ['id' => 2, 'title' => 'Harry Potter', 'author' => 'Rowling', ...],
                //     ...
                // ]
                
                // ============================================
                // CICLO FOREACH: Itera su tutti i libri
                // ============================================
                
                // foreach = struttura di controllo che itera su un array
                // $books as $book = per ogni elemento di $books, assegnalo a $book
                // Ad ogni iterazione, $book conterrà i dati di UN libro
                foreach ($books as $book) {
                    // $book è un array associativo che rappresenta un singolo libro
                    // Es: ['id' => 1, 'title' => '1984', 'author' => 'George Orwell', ...]
                    
                    // ============================================
                    // INIZIO OUTPUT HTML PER QUESTO LIBRO
                    // ============================================
                    ?>
                    
                    <div class="book-card"> <!-- Contenitore per la card di un libro, con classe CSS per gli stili -->
                        
                        <!-- ============================================ -->
                        <!-- IMMAGINE DI COPERTINA -->
                        <!-- ============================================ -->
                        
                        <img class="book-cover"
                             src="<?php 
                                 // Costruiamo l'attributo src (percorso immagine)
                                 
                                 // htmlspecialchars() = funzione che converte caratteri speciali in entità HTML
                                 // Previene attacchi XSS (Cross-Site Scripting)
                                 // Es: se $book['cover_image'] contiene <script>, viene convertito in &lt;script&gt;
                                 
                                 // $book['cover_image'] ?? 'uploads/covers/default.jpg'
                                 // ?? = operatore null coalescing (coalescenza null)
                                 // Se $book['cover_image'] è null o non esiste, usa 'uploads/covers/default.jpg'
                                 // Altrimenti usa il valore di $book['cover_image']
                                 
                                 echo htmlspecialchars($book['cover_image'] ?? 'uploads/covers/default.jpg'); 
                                 ?>" 
                             alt="Copertina di <?php 
                                 // Attributo alt = testo alternativo se l'immagine non si carica
                                 // Importante per accessibilità (screen reader) e SEO
                                 echo htmlspecialchars($book['title']); 
                                 ?>">
                        
                        <!-- ============================================ -->
                        <!-- TITOLO DEL LIBRO -->
                        <!-- ============================================ -->
                        
                        <div class="book-title">
                            <?php 
                            // echo = stampa il contenuto
                            // htmlspecialchars() = converte caratteri speciali per sicurezza
                            // $book['title'] = accede alla chiave 'title' dell'array $book
                            echo htmlspecialchars($book['title']); 
                            ?>
                        </div>

                        <!-- ============================================ -->
                        <!-- AUTORE DEL LIBRO -->
                        <!-- ============================================ -->
                        
                        <div class="book-author">
                            di <?php 
                            // "di " = testo fisso che precede il nome dell'autore
                            // $book['author'] = nome dell'autore dal database
                            echo htmlspecialchars($book['author']); 
                            ?>
                        </div>
                        
                        <!-- ============================================ -->
                        <!-- INFORMAZIONI AGGIUNTIVE -->
                        <!-- ============================================ -->
                        
                        <div class="book-info">
                            <!-- 
                                Contenitore per informazioni extra (editore, anno, ISBN)
                            -->
                            
                            <?php 
                            // ============================================
                            // EDITORE (se presente)
                            // ============================================
                            
                            // if (!empty($book['publisher']))
                            // !empty() = verifica che la variabile NON sia vuota
                            // Ritorna true se la variabile contiene un valore (non null, non stringa vuota, non 0)
                            // : = sintassi alternativa per if (usata spesso in HTML)
                            if (!empty($book['publisher'])): 
                            ?>
                                Editore: <?php echo htmlspecialchars($book['publisher']); ?><br>
                                <!-- <br> = tag HTML per andare a capo -->
                            <?php 
                            // endif = chiusura dell'if (sintassi alternativa)
                            endif; 
                            ?>
                            
                            <?php 
                            // ============================================
                            // ANNO DI PUBBLICAZIONE (se presente)
                            // ============================================
                            
                            // Stesso controllo per l'anno
                            if (!empty($book['year'])): 
                            ?>
                                Anno: <?php echo htmlspecialchars($book['year']); ?><br>
                            <?php 
                            endif; 
                            ?>
                            
                            <?php 
                            // ============================================
                            // ISBN (se presente)
                            // ============================================
                            
                            // Stesso controllo per l'ISBN
                            if (!empty($book['isbn'])): 
                            ?>
                                ISBN: <?php echo htmlspecialchars($book['isbn']); ?>
                            <?php 
                            endif; 
                            ?>
                        </div>
                        
                        <!-- ============================================ -->
                        <!-- DISPONIBILITÀ COPIE -->
                        <!-- ============================================ -->
                        
                        <div class="copies <?php 
                            // Determiniamo la classe CSS in base alla disponibilità
                            
                            // $book['copies_available'] > 0 ? 'available' : 'unavailable'
                            // ? : = operatore ternario (if-else compatto)
                            // Sintassi: condizione ? valore_se_vero : valore_se_falso
                            // Se copies_available > 0, echo 'available', altrimenti 'unavailable'
                            
                            echo $book['copies_available'] > 0 ? 'available' : 'unavailable'; 
                            ?>">
                            <!-- 
                                Risultato esempio:
                                <div class="copies available"> oppure <div class="copies unavailable">
                            -->
                            
                            <?php 
                            // ============================================
                            // TESTO DISPONIBILITÀ
                            // ============================================
                            
                            // if = struttura condizionale
                            // Esegue il codice dentro le graffe {} solo se la condizione è vera
                            if ($book['copies_available'] > 0) {
                                // Se ci sono copie disponibili
                                
                                // Costruiamo il testo "Disponibili: X/Y"
                                // . = operatore di concatenazione (unisce stringhe)
                                echo "Disponibili: " . $book['copies_available'] . "/" . $book['copies_total'];                                
                            } else {
                                // else = altrimenti (se la condizione è falsa)
                                // Se NON ci sono copie disponibili
                                
                                echo "Non disponibile";
                            }
                            ?>
                        </div>
                        <!-- 
                            Fine della card del libro
                        -->
                    </div>
                    
                    <?php
                    // Fine del corpo del foreach
                    // Il ciclo torna all'inizio e prende il libro successivo
                }
                // Fine del foreach
                // Tutti i libri sono stati processati
                
                // ============================================
                // CONTROLLO SE NON CI SONO LIBRI
                // ============================================
                
                // count($books) = funzione che conta gli elementi di un array
                // Ritorna il numero di libri trovati
                if (count($books) === 0) {
                    // === = operatore di confronto stretto (controlla tipo E valore)
                    // Se non ci sono libri nel database
                    
                    // Mostriamo un messaggio informativo
                    echo '<p style="text-align: center; grid-column: 1/-1; font-size: 1.2em; color: #999;">';
                    echo 'Nessun libro presente nel catalogo.';
                    echo '</p>';
                    // grid-column: 1/-1 = occupa tutte le colonne della griglia
                }
                
            // ============================================
            // GESTIONE ERRORI DATABASE
            // ============================================
            
            } catch (PDOException $e) {
                // catch = cattura le eccezioni lanciate nel blocco try
                // PDOException = tipo specifico di eccezione per errori PDO/database
                // $e = variabile che contiene l'oggetto eccezione con i dettagli dell'errore
                
                // Se si verifica un errore nel database, mostriamo un messaggio
                
                // Apriamo un div con stili inline per il messaggio di errore
                echo '<div style="grid-column: 1/-1; background: #f8d7da; padding: 20px; border-radius: 8px; color: #721c24;">';
                
                // Titolo dell'errore
                echo '<strong>Errore nel caricamento dei libri:</strong><br>';
                
                // $e->getMessage() = metodo che ritorna il messaggio di errore dettagliato
                // htmlspecialchars() = protegge da XSS anche nei messaggi di errore
                echo htmlspecialchars($e->getMessage());
                
                // Chiudiamo il div
                echo '</div>';
            }
            // Fine del try-catch
            ?>
            
        </div>
        <!-- Fine della griglia dei libri -->
    </div>
    <!-- Fine del container principale -->
</body>
</html>