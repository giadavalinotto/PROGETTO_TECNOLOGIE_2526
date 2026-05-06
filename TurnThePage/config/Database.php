<?php
// ============================================
// CLASSE PER LA CONNESSIONE AL DATABASE
// ============================================

class Database { //una classe è un "modello" o "progetto" per creare oggetti che rappresentano qualcosa, in questo caso una connessione al database
    // ============================================
    // PROPRIETÀ PRIVATE DELLA CLASSE
    // ============================================
    private $host = 'localhost';      // host del database: localhost = il server locale (il computer su cui gira lo script)
    private $dbname = 'biblioteca';   // nome del database
    private $username = 'root';       // username database, di solito "root" per il server locale
    private $password = '';           // password database, di solito vuota per il server locale
    private $db;                      // oggetto PDO (pdo = PHP Data Objects, un'interfaccia per accedere al database)

    // ============================================
    // COSTRUTTORE: CREA LA CONNESSIONE PDO
    // ============================================
    public function __construct() { // __construct() = metodo che viene chiamato automaticamente quando si crea un nuovo oggetto della classe Database
        try {
            //costruisce la stringa di connessione per MySQL
            $this->db = new PDO( //this si riferisce all'istanza corrente della classe Database, db è la proprietà che conterrà l'oggetto PDO e new PDO() crea una nuova connessione PDO
                "mysql:dbname={$this->dbname};host={$this->host};charset=utf8mb4", //stringa di connessione: specifica il tipo di database (mysql), il nome del database, l'host e il charset
                $this->username, // username per la connessione al database
                $this->password // password per la connessione al database
            );

            // ============================================
            // GESTIONE DEGLI ERRORI
            // ============================================
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // this è l'istanza corrente della classe Database
            // db è la proprietà che contiene l'oggetto PDO
            // setAttribute() = metodo per configurare le opzioni dell'oggetto PDO
            // PDO::ATTR_ERRMODE = "modalità di gestione degli errori" (come PDO gestisce gli errori)
            // PDO::ERRMODE_EXCEPTION = "lancia eccezioni" (PDO lancerà un'eccezione ogni volta che si verifica un errore, invece di restituire false o un codice di errore)

            // ============================================
            // MODALITÀ DI RESTITUZIONE DEI RISULTATI
            // ============================================
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // PDO::ATTR_DEFAULT_FETCH_MODE = "modalità di restituzione dei risultati" (come PDO restituisce i dati quando si eseguono query)
            // PDO::FETCH_ASSOC = "restituisce un array associativo" (i risultati saranno restituiti come array associativi, dove le chiavi sono i nomi delle colonne del database)

        } catch (PDOException $e) {
        // se si verifica un errore durante la connessione, viene catturata un'eccezione di tipo PDOException e viene stampato un messaggio di errore
        //catch = "cattura" (cattura l'eccezione e la gestisce)
        // PDOException = tipo di eccezione specifica per errori di database
        // $e = variabile che contiene l'eccezione catturata, getMessage() restituisce il messaggio di errore associato all'eccezione
            
            die("Errore di connessione: " . $e->getMessage());
            // die() = "termina lo script" (stampa il messaggio di errore e termina l'esecuzione dello script)
            // $e->getMessage() = "ottiene il messaggio di errore" (restituisce il messaggio di errore associato all'eccezione catturata)
        }
    }

    // ============================================
    // METODO PUBBLICO PER OTTENERE L'OGGETTO PDO
    // ============================================
    public function getConnection() {
        return $this->db;
        // restituisce l'oggetto PDO che rappresenta la connessione al database, in questo modo altre parti del codice possono utilizzare questa connessione per eseguire query e operazioni sul database
    }
}
?>
