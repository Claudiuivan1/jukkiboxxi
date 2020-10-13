<?php

    // Codifica la password per evitare di rendere i dati nel DB troppo vulnerabili
    function encrypt_password($password) { 
        return md5($password);
    }

    // Funzione che verifica se un utente è loggato
    // La verifica è effettuata verificando semplicemente che il cookie relativo sia settato
    function is_logged() {
        if(isset($_COOKIE['user_data']))
            return true;
        return false;
    }

    // Funzione che crea un cookie serializzando un array 
    // Generalmente utilizzato per la funzione di login
    // Consente di serializzare i dati dell'utente e renderli fruibili in qualsiasi momento
    function create_cookie($name, $value, $duration = "0") {
        setcookie($name, serialize($value), $duration, "/");
    }

    // Getters per i dati dell'utente

    //-------------------------------

    //ID
    function get_id() {
        return unserialize($_COOKIE['user_data'])[0];
    }

    // Username
    function get_username() {
        return unserialize($_COOKIE['user_data'])[1];
    }

    // Nome
    function get_name() {
        return unserialize($_COOKIE['user_data'])[2];
    }
    
    // Cognome
    function get_surname() {
        return unserialize($_COOKIE['user_data'])[3];
    }

    // Genere preferito
    function get_generepref() {
        return unserialize($_COOKIE['user_data'])[4];
    }

    // Email
    function get_email() {
        return unserialize($_COOKIE['user_data'])[5];
    }

    //-------------------------------


    // Funzione che gestisce il pulsante "casuale"
    function set_casuale() {
        if(array_key_exists('casuale', $_GET)) {
            if($_GET['casuale'])
                echo "?casuale=0";
            else
                echo "?casuale=1";
        } else {
            echo "?casuale=1";
        }
        foreach($_GET as $key => $value)
            if($key != "casuale")
                echo "&$key=$value";
    }

?>