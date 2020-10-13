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
        <div class="alert alert-success px-3 pt-5 text-center alert-message">
            <?php

                // Includo le funzioni PHP
                include '../functions/global_functions.php';
                include '../functions/db_functions.php';

                // Preparo i dati
                $user = $_POST["user"];
                $password = encrypt_password($_POST["password"]);

                // Connessione con il DB
                $dbconn = pg_connect("host=localhost port=5432 dbname=jukkiboxxi user=postgres password=password") or die('Could not connect: '. pg_last_error());

                // Verifico che l'utente che tenta di loggarsi esista nel DB
                $select = db_getter("utente", "id, username, nome, cognome, generepref, email", "password='$password' AND username='$user'", "", 1);
                $row = pg_fetch_row($select);

                if($row != NULL) {
                    // Se trovo una corrispondenza creo un cookie con i suoi dati, che testimonierà che l'utente è loggato
                    if(isset($_POST['rememberMeCheck']))
                        create_cookie("user_data", $row, time()+31556926);
                    else
                        create_cookie("user_data", $row);
                    echo "Login effettuato.<br>
                    Sarai reindirizzato a breve...";
                } else {
                    // Se no, restituisco un messaggio d'errore
                    echo "I dati inseriti sono errati.<br>
                    Sarai reindirizzato a breve...";
                }

                // Chiudo la connessione con il DB
                pg_close($dbconn);

                // Reindirizzo alla home
                header('Refresh: 3; URL=../index.php');
            ?>
        </div>
    </body>
</html>