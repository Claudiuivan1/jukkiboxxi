<?php

    // Connetto al database
    $dbconn = pg_connect("host=localhost port=5432 dbname=jukkiboxxi user=postgres password=password") or die('Could not connect: '. pg_last_error());

    // Funzione che estrae dati dal DB
    /* Arogmenti:
        $table      (Obbligatorio)  -> Nome della tabella da cui estrarre i dati
        $what       (Facoltativo)   -> Nome dei campi da estrarre. Se non inserito varrà '*'
        $where      (Facoltativo)   -> Condizione 'WHERE'
        $order_by   (Facoltativo)   -> Ordinamento 'ORDER BY'
        $limit      (Facoltativo)   -> Limite di query da estrarre 'LIMIT'
    */
    function db_getter($table, $what="", $where="", $order_by="", $limit="") {
        $query = "SELECT ". ($what ? $what : "*"). " FROM $table". ($where ? " WHERE $where" : ""). ($order_by ? " ORDER BY $order_by" : ""). ($limit ? " LIMIT $limit" : "");
        $result = pg_query($query) or die('Query failed: '. pg_last_error());
        return $result;
    }

    // Funzione che aggiorna i dati dal DB
    /* Arogmenti:
        $table      (Obbligatorio)  -> Nome della tabella da cui estrarre i dati
        $set        (Obbligatorio)  -> Stringa contenente l'attributo da aggiornare e il nuovo valore
        $where      (Obbligatorio)  -> Condizione 'WHERE'
    */
    function db_updater($table, $set, $where) {
        $query = "UPDATE " . $table . " SET " . $set . " WHERE " . $where;
        $result = pg_query($query) or die('Query failed: '. pg_last_error());
        return $result;
    }

    // Funzione che estrae una lista di elementi canzone
    // Gli elementi canzone non sono altro che array che contengono i dati della canzone
    // Consentono di individuarla e stamparla
    /* Arogmenti:
        $method (Obbligatorio) -> Metodo con cui estrarre (Casuale o no)
        $type (Obbligatorio) -> Modalità di raggruppamento delle canzoni (Può essere album, genere o artista)
        $kind (Facoltativo) -> Genere
        $author (Facoltativo) -> Autore
        $album (Facoltativo) -> Album
        $song (Facoltativo) -> Canzone
        $number (Facoltativo) -> NUmero canzone nell'album
    */
    function get_songs($method, $type, $limit, $kind="", $artist="", $album="", $song="", $number="") {
        $artist = str_replace(' ', '_', $artist);
        $album = str_replace(' ', '_', $album);
        $where = "artista != ''".
                 ($kind ? " AND genere='". $kind. "'" : ""). 
                 ($artist ? " AND UPPER(artista) LIKE UPPER('%$artist%')" : ""). 
                 ($album ? " AND UPPER(album) LIKE UPPER('%$album%')" : ""). 
                 ($song ? " AND titolo='". $song. "'" : "").
                 ($number ? " AND num='". $number. "'" : "");
        $order_by = 0;
        if($method || ($artist && !$album)) $order_by = "RANDOM()";
        elseif($album && !$method) $order_by = "num";
        return db_getter("brano", "artista, album, num, titolo", $where, $order_by, $limit);
    }

    // Funzione che estrae tutte le successive canzoni per la coda
    // Queste canzoni saranno inserite nella lista globale
    function retrive_songs() {
        $titolo = "";
        if(array_key_exists('titolo', $_GET)) $titolo = $_GET['titolo'];
        $casuale = 0;
        if(array_key_exists('casuale', $_GET)) $casuale = $_GET['casuale'];
        if($_GET) {            
            if(array_key_exists('album', $_GET) && array_key_exists('artista', $_GET))
                return get_songs($casuale, "album", "", "", $_GET['artista'], $_GET['album'], $titolo);
            elseif(array_key_exists('album', $_GET))
                return get_songs($casuale, "album", "", "", "", $_GET['album'], $titolo);
            elseif(array_key_exists('genere', $_GET))
                return get_songs(1, "genere", "", $_GET['genere'], "", "", $titolo);
            elseif(array_key_exists('artista', $_GET))
                return get_songs(1, "artista", "", "", $_GET['artista'], "", $titolo);
            elseif(array_key_exists('titolo', $_GET))
                return get_songs(1, "titolo", "", "", "", "", $titolo);
        }
        return get_songs(1, "casuale", 100);
    }

    // Funzione che stampa l'array di tracce in un formato comprensibile da js
    // Prende in pasto un array generato dalla funzione retrive_songs()
    function print_js_songs($array) {
        $res = pg_num_rows($array);
        echo "[";
        while($row = pg_fetch_array($array)) {
            echo "['". $row['artista']."', '". $row['album']. "', '". $row['titolo']. "']";
            if($res > 1) echo ",";
            $res--;
        }
        echo "]";
    }

?>