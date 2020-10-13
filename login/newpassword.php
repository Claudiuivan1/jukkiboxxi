<!doctype html>
<html>
    <head>
       	<meta charset="utf-8">
       	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no">
        <title>JUKKUBOXXI - Cambia password</title>
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
                include '../functiones/global_functions.php';
                include '../functiones/db_functions.php';

                //Estraggo la nuova password
                $password = encrypt_password($_POST["npassword"]);

                // Connessione con il DB
                $dbconn = pg_connect("host=localhost port=5432 dbname=jukkiboxxi user=postgres password=password") or die('Could not connect: '. pg_last_error());
                
                //Eseguo l'update
                $update = db_updater("utente", "password = '" . $password. "'", "id = " . get_id()) or die('Update failed: '. pg_last_error());

                // Stampo un messaggio
                echo "Password cambiata<br>
                Sarai reindirizzato a breve...";

                // Chiudo la connessione con il DB
                pg_close($dbconn);

                // Reindirizzo alla home
                header('Refresh: 3; URL=./profilo.php');
            ?>
        </div>
    </body>
</html>