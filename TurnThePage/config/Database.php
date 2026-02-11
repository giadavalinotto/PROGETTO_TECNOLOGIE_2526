<?php
// ============================================
// CLASSE PER LA CONNESSIONE AL DATABASE
// ============================================

class Database {
    // ============================================
    // PROPRIETÀ PRIVATE DELLA CLASSE
    // ============================================
    private $host = 'localhost';      // host del database
    private $dbname = 'biblioteca';   // nome del database
    private $username = 'root';       // username database
    private $password = '';           // password database
    private $db;                      // oggetto PDO

    // ============================================
    // COSTRUTTORE: CREA LA CONNESSIONE PDO
    // ============================================
    public function __construct() {
        try {
            // Creiamo l'oggetto PDO (connessione al database)
            $this->db = new PDO(
                "mysql:dbname={$this->dbname};host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password

                // BMS usato: mysql (il sistema di gestione database)
                // - Nome del DB: biblioteca (il database che abbiamo creato in phpMyAdmin)
                // - Host: localhost (il DBMS gira sullo stesso computer)
                // - Charset: utf8mb4 (supporta tutti i caratteri Unicode, inclusi emoji)
                // - Username: root (utente amministratore di default in XAMPP)
                // - Password: "" (stringa vuota, in XAMPP root non ha password di default)
            );

            // ============================================
            // MODALITÀ DI GESTIONE DEGLI ERRORI
            // ============================================
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // ↑              ↑                    ↑
            // |              |                    |
            // oggetto PDO    quale attributo      valore da impostare
            //                configurare
            // - PDO::ATTR_ERRMODE = "modalità errori" (come PDO reagisce agli errori SQL)
            // - PDO::ERRMODE_EXCEPTION = "lancia eccezioni" (PDO lancerà un'eccezione in caso di errore SQL)
            // Senza questa impostazione: se fai una query sbagliata, PDO restituisce false
            // Con questa impostazione: se fai una query sbagliata, PDO lancia un'eccezione catturabile con try-catch

            // ============================================
            // MODALITÀ DI RESTITUZIONE DEI RISULTATI
            // ============================================
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // ↑              ↑                               ↑
            // |              |                               |
            // oggetto PDO    quale attributo                 valore da impostare
            //                configurare
            // - PDO::ATTR_DEFAULT_FETCH_MODE = "modalità di fetch predefinita" (come PDO restituisce i risultati delle query)
            // - PDO::FETCH_ASSOC = "array associativo" (PDO restituirà i risultati come array associativi, dove le chiavi sono i nomi delle colonne)
            // Senza questa impostazione: $result = $db->query("SELECT * FROM libri")->fetch();
            // $result potrebbe contenere sia indici numerici che associativi
            // Con PDO::FETCH_ASSOC: $result = ['id' => 1, 'titolo' => 'Il Signore degli Anelli', ...]

        } catch (PDOException $e) {
            // catch = cattura le eccezioni lanciate nel blocco try
            // PDOException = tipo specifico di eccezione per errori PDO/database
            // $e = variabile che contiene l'oggetto eccezione con i dettagli dell'errore

            
            die("Errore di connessione: " . $e->getMessage());
            // die() = termina l'esecuzione dello script e stampa un messaggio
            // htmlspecialchars() = protegge da eventuali caratteri speciali nel messaggio
        }
    }

    // ============================================
    // METODO PUBBLICO PER OTTENERE L'OGGETTO PDO
    // ============================================
    public function getConnection() {
        return $this->db;
    }
}
?>
