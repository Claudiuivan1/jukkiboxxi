<!doctype html>
<html>
    <head>
       	<meta charset="utf-8">
       	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, shrink-to-fit=no">
        <title>JUKKIBOXXI - Profilo</title>
		<link rel="icon" href="../img/favicon.png">

        <link rel="stylesheet" type="text/css" href="../style/reset.css">
        <link rel="stylesheet" type="text/css" href="../style/fonts.css">
        <link rel="stylesheet" type="text/css" href="../style/style.css">
		<link rel="stylesheet" type="text/css" href="../dist/css/bootstrap.min.css">

		<script src="../functions/globals.js"></script>
		<script src="../functions/profilo.js"></script>
		<script src="../functions/controls.js"></script>
		<script src="../functions/tracks.js"></script>
    </head>
    <body onload="return initTopFive();">
		<?php 
			include '../functions/global_functions.php';
			include '../functions/db_functions.php';
		?>
		<?php $logged = is_logged(); ?>
		<!-- La pagina è raggiungibile solo loggandosi ergo non effettuo controlli -->
		<!-- Etichetta di benvenuto -->
		<a class="rounded-left bg-danger text-light border-0 p-2 btn-login" disabled>
			<?php echo "Benvenuto <b>" . get_username() . "</b>!"; ?>
		</a>

		<!-- Pulsante di reindirizzamento alla home -->
		<a class="rounded-left bg-warning text-light border-0 p-2 btn-registration" style="cursor: pointer;" href="../index.php">
			<?php echo "Vai a <b>JukkiBoxxi</b>"?>
		</a>

		<!-- Titolo -->
		<h1 class="text-light" style="font-family: 'VintageStraps'; font-size: 65px; position: fixed; top: 20px; left: 30px;">
			<p class="text-danger" style="display: inline;">Personal</p>
			<p class="text-primary" style="display: inline;">Boxxi</p></h1>

		<!-- Corpo della pagina -->
		<div class="container-fluid bg-dark p-5" style="height: 100vh !important; overflow: hidden; background: -webkit-linear-gradient(top, #252a2e 0%,#51585e 100%);">
			<br><br><br>
			<div class="container-fluid rounded">
				<div class="row">
					<div class="col px-5 pb-5 pt-2">
						<p class="text-warning w-75" style="margin: 0 auto; font-family: 'VintageStraps'; font-size: 30px;">Ultimi ascolti</p>
						<div class="container-fluid rounded mt-5 p-5 w-75" style="margin: 0 auto; background-color: rgba(166, 183, 197, 0.6);">
							<div class="list-group" id="list-tab" role="tablist">
								<a class="list-group-item list-group-item-action" id="top1_list" data-toggle="list" role="tab" aria-controls="top1">Top1</a>
								<a class="list-group-item list-group-item-action" id="top2_list" data-toggle="list" role="tab" aria-controls="top2">Top2</a>
								<a class="list-group-item list-group-item-action" id="top3_list" data-toggle="list" role="tab" aria-controls="top3">Top3</a>
								<a class="list-group-item list-group-item-action" id="top4_list" data-toggle="list" role="tab" aria-controls="top4">Top4</a>
								<a class="list-group-item list-group-item-action" id="top5_list" data-toggle="list" role="tab" aria-controls="top5">Top5</a>
							</div>
							<div class="col pt-3 text-center">
								<img style="width: 200px; height: 200px;" src="" id="top_cover">
								<div class="mt-3 px-3 alert bg-danger text-light border" id="curr_top_song"></div>
							</div>
						</div>
					</div>
					<div class="col px-5 pb-5 pt-2">
						<p class="text-warning mb-2" style="font-family: 'VintageStraps'; font-size: 30px;">Le tue info personali</p>
						<div class="row pt-5">
							<div class="col">
								<div class="label pt-3 text-info" style="font-size: 20px">
									Username
								</div>
								<div class="label pt-3 text-info" style="font-size: 20px">
									E-Mail
								</div>
								<div class="label pt-3 text-info" style="font-size: 20px">
									Genere preferito
								</div>
								<div class="pt-3 text-info" style="font-size: 20px">
									Password
								</div>
							</div>
							<div class="col">
								<div class="label pt-3 text-light" style="font-size: 20px">
									<?php echo get_username(); ?>
								</div>
								<div class="label pt-3 text-light" style="font-size: 20px">
									<?php echo get_email(); ?>
								</div>
								<div class="form-group pt-3 m-0">
									<form class="form-inline" method="POST" action="./newgenre.php" onsubmit="return changeGenre();">
										<select class="form-control form-control-sm mr-3" name="newgenre" id="exampleFormControlSelect1">
											<?php
												$fav_user_genre = get_generepref();
												if ($fav_user_genre == "")
													echo '<option value="" selected>Scegli...</option>';
												$result = db_getter("genere") or die("Query failed: " . pg_last_error());
												while ($row = pg_fetch_row($result)) {
													$genre = $row[0];
													if ($genre == $fav_user_genre)
														echo '<option value="" selected>' . ucfirst($genre) . '</option>';
													else
														echo '<option value="' . $genre . '">'  . ucfirst($genre) . '</option>';
												}
											?>
										</select>
										<button class="btn btn-outline-info btn-sm">Cambia</button>
									</form>
								</div>
								<div class="label pt-3 text-light m-0" style="font-size: 20px">
									<a class="btn btn-outline-info btn-sm" data-toggle="modal" href="#newPasswordModal">Cambia password</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
			
		<!-- Modal per il cambio password -->
		<div class="modal fade" id="newPasswordModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Cambia password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="newPasswordForm" method="POST" action="./newpassword.php" onsubmit="return validateNewPassword();">
							<div class="row">
								<div class="col-5">
									<p class="label text-dark">Nuova password</p>
								</div>
								<div class="col">
									<input class="form-control form-control-sm w-100" name="npassword" id="npassword" size="32" type="password" maxlength="32" required>
								</div>
							</div>
							<div class="row">
								<div class="col-5">
									<p class="label text-dark">Conferma password</p>
								</div>
								<div class="col">
									<input class="form-control form-control-sm w-100" name="cnpassword" id="cnpassword" size="32" type="password" maxlength="32" required>
								</div>
							</div>
						</div>
						<div class="modal-footer">
        					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="return reset();">Annulla</button>
							<button type="submit" class="btn btn-primary">Cambia</button>
        				</div>
						</form>
					</div>
				</div>
			</div>
		</div>
        <script src="../dist/js/bootstrap.min.js"></script>
    </body>
</html>
