<!doctype html>
<html>
    <head>
       	<meta charset="utf-8">
       	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no">
        <title>JUKKUBOXXI - Login</title>
        <link rel="icon" href="../img/favicon.png">

        <link rel="stylesheet" type="text/css" href="../style/reset.css">
        <link rel="stylesheet" type="text/css" href="../style/fonts.css">
        <link rel="stylesheet" type="text/css" href="../style/style.css">
		<link rel="stylesheet" type="text/css" href="../dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php
    
            // Includo le funzioni PHP
            include '../functions/global_functions.php';
            include '../functions/db_functions.php';

            // Preparo i dati
            $nome = $_POST["nome"];
            $cognome = $_POST["cognome"];
            $eta = $_POST["eta"];
            $email = $_POST["email"];
            $user = $_POST["user"];
            $password = $_POST["password"];

            // Connessione con il DB
            $dbconn = pg_connect("host=localhost port=5432 dbname=jukkiboxxi user=postgres password=password") or die('Could not connect: '. pg_last_error());
        
            // Query per l'inserimento del nuovo utente
            $query_insert = "INSERT INTO utente (nome, cognome, eta, email, username, password) VALUES ('". $nome. "','". $cognome. "',". $eta. ",'". $email. "','". $user. "','". encrypt_password($password). "')";
            pg_query($dbconn, $query_insert) or die('Error inserting: '. pg_last_error());

            // Messaggio di avvenuta registrazione
            echo "Registrazione completata.<br>
            Sarai reindirizzato a breve...";

            // Chiudo la connessione con il DB
            pg_close($dbconn);

            // reindirizzo alla home
            header('Refresh: 3; URL=../index.php');
            
?>