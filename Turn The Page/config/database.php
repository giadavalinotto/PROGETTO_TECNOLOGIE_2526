<?php
try {
    $db = new PDO(
        "mysql:dbname=biblioteca;host=localhost;charset=utf8mb4",
        "root",
        ""

        //BMS usato: mysql (il sistema di gestione database)
        // -Nome del DB: biblioteca (il database che abbiamo creato in phpMyAdmin)
        // - Host: localhost (il DBMS gira sullo stesso computer)
        // - Charset: utf8mb4 (supporta tutti i caratteri Unicode, inclusi emoji)
        // - Username: root (utente amministratore di default in XAMPP)
        // - Password: "" (stringa vuota, in XAMPP root non ha password di default)

    );
    //Modalità di gestione degli errori
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // ↑              ↑                    ↑
    // |              |                    |
    // oggetto PDO    quale attributo      valore da impostare
    //                configurare
    // - PDO::ATTR_ERRMODE = "modalità errori" (come PDO reagisce agli errori SQL)
    // - PDO::ERRMODE_EXCEPTION = "lancia eccezioni" (PDO lancerà un'eccezione in caso di errore SQL)
    //Senza questa impostazione: se fai una query sbagliata, PDO restituisce false
    // Con questa impostazione: se fai una query sbagliata, PDO lancia un'eccezione catturabile con try-catch, e puoi vedere il messaggio di errore dettagliato

    //Modalità di restituzione dei risultati
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // ↑              ↑                               ↑
    // |              |                               |
    // oggetto PDO    quale attributo                 valore da impostare
    //                configurare
    // - PDO::ATTR_DEFAULT_FETCH_MODE = "modalità di fetch predefinita" (come PDO restituisce i risultati delle query)
    // - PDO::FETCH_ASSOC = "array associativo" (PDO restituirà i risultati come array associativi, dove le chiavi sono i nomi delle colonne)
    // Senza questa impostazione: se fai una query, PDO restituisce un array con chiavi numeriche (0, 1, 2...) e chiavi associative (nome_colonna)
    // Con questa impostazione: se fai una query, PDO restituisce solo un array associativo (nome_colonna => valore)
    // Esempio:
    // Senza PDO::ATTR_DEFAULT_FETCH_MODE: $result = $db->query("SELECT * FROM libri")->fetch();
    // $result potrebbe essere: [0 => 1, 'id' => 1, 1 => 'Il Signore degli Anelli', 'titolo' => 'Il Signore degli Anelli', ...]
    // Con PDO::ATTR_DEFAULT_FETCH_MODE: $result = $db->query("SELECT * FROM libri")->fetch();
    // $result sarà: ['id' => 1, 'titolo' => 'Il Signore degli Anelli', ...]
    // Questo rende il codice più pulito e leggibile, perché puoi accedere ai dati usando i nomi delle colonne invece di numeri di indice

} catch (PDOException $e) {
//↑         ↑          ↑
//|         |          |
//cattura  tipo di    variabile che contiene
//         eccezione  l'oggetto errore
// - PDOException = classe di eccezione specifica per errori PDO (errori di connessione, query, ecc.)
// - $e = variabile che conterrà l'oggetto eccezione lanciato da PDO in caso di errore

    die("Errore di connessione: " . $e->getMessage());
    //die() = termina l'esecuzione dello script e stampa un messaggio
    // $e->getMessage() = ottiene il messaggio di errore dettagliato
}
?>