<!doctype html>
<html>
    <head>
       	<meta charset="utf-8">
       	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no">
        <title>JUKKIBOXXI</title>
		<link rel="icon" href="./img/favicon.png">

        <link rel="stylesheet" type="text/css" href="./style/reset.css">
        <link rel="stylesheet" type="text/css" href="./style/fonts.css">
        <link rel="stylesheet" type="text/css" href="./style/style.css">
		<link rel="stylesheet" type="text/css" href="./dist/css/bootstrap.min.css">

		<script src="./functions/globals.js"></script>
		<script src="./functions/controls.js"></script>
		<script src="./functions/animations.js"></script>
		<script src="./functions/tracks.js"></script>
		<script src="./functions/buttons.js"></script>
    </head>
    <body>
		<?php 
			include './functions/global_functions.php';
			include './functions/db_functions.php';
		?>
		<?php $logged = is_logged(); ?>

		<!-- Pulsante login/logout -->
		<a class="rounded-left bg-danger text-light border-0 p-2 btn-login" <?php if($logged) echo 'href="./login/logout.php"'; else echo 'data-toggle="modal" data-target="#loginModal"'; ?>>
			<?php echo ($logged ? "Logout" : "Login"); ?>
		</a>

		<!-- Pulsante registrazione/profilo -->
		<a class="rounded-left bg-warning text-light border-0 p-2 btn-registration" style="cursor: pointer;" href="<?php if($logged) echo './login/profilo.php'; else echo './login/registrazione.html'; ?>">
			<?php echo ($logged ? "Profilo di <b>". get_username(). "</b>" : "Registrazione"); ?>
		</a>

		<!-- Titolo -->
		<h1 class="text-light" style="font-family: 'VintageStraps'; font-size: 65px; position: fixed; top: 20px; left: 30px;">
			<p class="text-danger" style="display: inline;">Jukki</p>
			<p class="text-primary" style="display: inline;">Boxxi</p></h1>

		<!-- Corpo della pagina -->
		<div class="container-fluid bg-dark pt-5" style="height: 100vh !important; overflow: hidden;">
			<div class="row h-100">
				<div class="col-1"></div>
				<!-- Immagine del jukebox -->
				<div class="col-5 h-100 text-center" style="overflow: visible;">
					<img src="./img/jukebox_vect.svg" class="h-100" style="margin: 0 auto !important;"> 

					<?php
						if($logged) {
							echo '<div class="text-light bg-danger rounded border-1" style="position: absolute; top: 31.9%; left: 29%; width: 42.1%; height: 10.1%; text-align: center;">
								<div class="pt-2 pb-2 font-weight-bold" style="font-size: .8em">Ascolta il tuo genere preferito</div>
								<a href="?casuale=1&genere='. get_generepref(). '" class="btn btn-warning text-dark" style="width: 120px;">
									<img src="./img/icons/music_note.svg">
								</a>
							</div>';
						}
					?>

					<div class="text-light bg-warning border-1 p-1 pb-2" style="position: absolute; top: 50.3%; left: 38.6%; width: 22.8%; text-align: center;">
						<form method="GET" action="">
							<input name="artista" class="form-control form-control-sm w-100 mb-2" type="text" placeholder="Cerca artista" autocomplete="off">
							<input name="album" class="form-control form-control-sm w-100 mb-2" type="text" placeholder="Cerca album" autocomplete="off">
							<input class="btn btn-sm btn-primary w-100" type="submit" value="Cerca">
						</form>
					</div>
				</div>
				<!-- Info brani, coda di riproduzione e riproduttore musicale -->
				<div class="col-5 h-100 py-5 text-center">
					<div class="container-fluid w-75 rounded py-5" style="background-color: rgba(166, 183, 197, 0.6);">
						<!-- Audio player for JukkiBoxxi -->
						<audio src="" id="player" onended="return nextTrack();"></audio>
				
						<div class="container w-75">
							<div class="row">
								<!-- Cover, titolo e autore del brano in riproduzione -->
								<div class="col pt-2 text-center">
									<div class="rounded bg-light w-75" id="cover-box">
										<img class="cover-box" style="left: -120px;" src="" id="prev-cover" hidden>
										<img class="cover-box" style="left: calc(50% - 80px);" src="" id="cover">
										<img class="cover-box" style="left: calc(100% - 40px);" src="" id="next-cover">
									</div>
									<div class="mt-4 px-3 alert bg-danger text-light border" id="main_song"></div>
								</div>
							</div>

							<div class="row">
								<!-- Coda di riproduzione -->
								<div class="col pb-3 px-3 text-center">
									<p class="text-light p-2 font-weight-bold">Coda di riproduzione</p>
									<ul class="list-group" id="cdr">
										<li class="list-group-item"></li>
										<li class="list-group-item"></li>
										<li class="list-group-item"></li>
										<li class="list-group-item"></li>
									</ul>
								</div>
							</div>

							<div class="row">
								<!-- Controlli per il riproduttore musicale -->
								<div class="col py-2 px-1">
									<!-- Casuale -->
									<a href="<?php set_casuale(); ?>" style="display: inline-block; text-align: center;" class="lateral-button<?php if(array_key_exists('casuale', $_GET) && $_GET['casuale']) echo ' bg-success' ?>" id="shuffle-button">
										<img src="./img/icons/shuffle.svg" style="width: 20px; height: 20px; margin-top: 10px">
									</a>
								</div>
								<div class="col py-2 px-1">
									<!-- Precedente -->
									<div class="audio-button" id="prev-button" style="background-image: url('./img/icons/previous.svg'); display: none;" onclick="return prevTrack();"></div>
								</div>
								<div class="col py-2 px-1">
									<!-- Play/pausa -->
									<div class="audio-button" style="background-image: url('./img/icons/play.svg'); " id="play-pause" onclick="return playPause();"></div>
								</div>
								<div class="col py-2 px-1">
									<!-- Successiva -->
									<div class="audio-button" id="next-button" style="background-image: url('./img/icons/next.svg');" onclick="return nextTrack();"></div>
								</div>
								<div class="col py-2 px-1">
									<!-- Loop -->
									<div class="lateral-button" id="loop-button" style="background-image: url('./img/icons/loop.svg');" onclick="return loop();"></div>
								</div>
							</div>
						
							<!-- Controllo volume -->
							<div class="row">
								<div class="col-2"></div>
								<div class="col-1 p-2">
									<img id="volume-icon" src="./img/icons/volume_up.svg">
								</div>
								<div class="col-6 py-2">
									<input class="custom-range" type="range" min="0" max="1" value="0.8" step="0.01" id="volume" oninput="return changeVolume(this.value);" onchange="return changeVolume(this.value);">
								</div>
								<div class="col-3"></div>
							</div>
						</div>
					</div>

					<!--Inizializzo la coda di riproduzione -->
					<script>
						GLOBAL_SONGS = <?php print_js_songs(retrive_songs()); ?>;
						songs = initCdr();
						initLS(songs);
					</script>
				</div>
				<div class="col-1"></div>
			</div>
		</div>

		<!-- Login popup -->
		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      				<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalLabel">Login</h5>
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          					<span aria-hidden="true">&times;</span>
        				</button>
      				</div>
      				<div class="modal-body">
						<div id="loginForm"></div>
      				</div>
    			</div>
  			</div>
		</div> 

		<script src="./dist/js/jquery-3.3.1.slim.min.js"></script>

		<!-- Jquery script for login modal -->
        <script>
            $(function(){
                $("#loginForm").load("./login/index.html");
            });
		</script>
		
        <script src="./dist/js/bootstrap.min.js"></script>
    </body>
</html>
