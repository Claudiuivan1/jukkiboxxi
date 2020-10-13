<!doctype html>
<html>
    <head>
       	<meta charset="utf-8">
       	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no">
        <title>JUKKUBOXXI - Logout</title>
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

                // Verifico che l'utente che prova ad effettuare il logout sia effettivamente loggato
                $logged = is_logged();
                if($logged)
                    create_cookie("user_data", "", time() - 3600); // Se lo è, elimino il suo cookie

                echo "Logout effettuato.<br>
                Sarai reindirizzato a breve...";

                // Reindirizzo alla home
                header('Refresh: 3; URL=../index.php');
            ?>
        </div>
    </body>
</html>