<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Mon profile</title>
		<meta name="description" content="<?php echo $settings['store'];?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow" />
		<!-- General CSS Settings -->
		<link rel="stylesheet" href="css/general_style.css">
		<!-- Main Style of the template -->
		<link rel="stylesheet" href="css/main_style.php">
		<!-- Landing Page Style -->
		<link rel="stylesheet" href="css/reset_style.css">
		<!-- Awesomefont -->
		<link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.css" crossorigin="anonymous">
		<!-- DateRangePicker -->
		<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
		<!-- Fav Icon -->
		<link rel="shortcut icon" href="favicon.ico">
		<?php include("onesignal.php");?>
	</head>
	<body>

		<!-- Wrapper -->
		<div class="lx-wrapper">
			<!-- Header -->
			<div class="lx-header">
				<?php include('header.php');?>
			</div>
			<!-- Main -->
			<div class="lx-main">
				<div class="lx-main-leftside">
					<?php include('mainmenu.php');?>
				</div>
				<!-- Main Content -->
				<div class="lx-main-content">
					<div class="lx-page-header">
						<h2>Mon Compte</h2>
						<p>Vous pouvez modifier vos informations personnelles ici</p>
					</div>
					<div class="lx-g1">
						<?php
						if($settings['inventaire'] == "1"){
						?>
						<div class="lx-notices-item">
							<i class="fa fa-exclamation-triangle"></i>
							<p>Un inventaire de stock est lancé, vous ne pouvez pas effectuer aucune action sur l'application avant de le terminer</p>
						</div>
						<?php
						}
						?>
					</div>
					<div class="lx-clear-fix"></div>
					<div class="lx-page-content">
						<div class="lx-g2 lx-pb-0">
							<h3>Changer informations personnelles</h3><br />
							<div class="lx-add-form">
								<form autocomplete="off" action="#" method="post" id="accountform" autocomplete="off">
									<?php
									$back = $bdd->query("SELECT * FROM users WHERE id='".$_SESSION['easybm_id']."'");
									$row = $back->fetch();
									?>
									<div class="lx-textfield">
										<input type="file" name="medias" id="medias" accept="image/x-png,image/jpeg" />
										<input type="hidden" name="picture" value="<?php echo $row['picture'];?>" />
										<a href="javascript:;" class="lx-upload-picture">Changer photo de profile</a>
									</div>
									<div class="lx-medias-item">
										<?php
										$picture = "images/avatar.png";
										if($row['picture'] != "avatar.png"){
											$picture = "uploads/".$row['picture'];
										}
										?>
										<div><img src="<?php echo $picture;?>" class="picture" /></div>
										<a href="javascript:;" class="lx-reset-photo lx-open-popup" data-title="deleterecord" data-file="medias" data-img="cette photo de profile" data-input="picture" data-default="avatar.png"><img src="images/close1.svg" /></a>
									</div>
									<div class="lx-textfield">
										<label><span>Login: </span><input type="text" autocomplete="off" name="email" value="<?php echo $row['email'];?>" readonly /></label>
									</div>
									<div class="lx-textfield">
										<label><span>Nom complet: </span><input type="text" autocomplete="off" name="fullname" value="<?php echo $row['fullname'];?>" data-isnotempty="" data-message="Saisissez un nom" /></label>
									</div>
									<div class="lx-textfield">
										<label><span>Téléphone: </span><input type="text" autocomplete="off" name="phone" value="<?php echo $row['phone'];?>" data-isphone="" data-message="Ex: 06xxxxxxxx, 07xxxxxxxx ..." /></label>
									</div>
									<div class="lx-submit">
										<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
										<a href="javascript:;">Enregistrer</a>
									</div>
								</form>
							</div>
						</div>
						<div class="lx-g2 lx-pb-0">
							<h3>Changer mot de passe</h3><br />
							<div class="lx-add-form">
								<form autocomplete="off" action="#" method="post" id="passwordform" autocomplete="off">
									<div class="lx-textfield">
										<label><span>Ancien mot de passe: </span><input type="password" name="oldpassword" /></label>
									</div>
									<div class="lx-textfield">
										<label><span>Nouveau mot de passe: </span><input type="password" name="newpassword1" data-ispassword="" data-message="Saisissez minimum 6 caratères" /></label>
									</div>
									<div class="lx-textfield">
										<label><span>Confirmer le nouveau mot de passe: </span><input type="password" name="newpassword2" data-ispassword="" data-message="Saisissez minimum 6 caratères" /></label>
									</div>
									<div class="lx-submit">
										<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
										<a href="javascript:;">Enregistrer</a>
									</div>
								</form>
							</div>
						</div>
						<div class="lx-clear-fix"></div>
					</div>
				</div>
			</div>
			<!-- End Popup -->	
			<div tabindex="0" class="lx-popup deleterecord">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Confirmation suppression</h3>
								</div>
								<div class="lx-add-form">
									<div class="lx-delete-box">
										<p>Voulez vous vraiment supprimer <ins class="lx-title"></ins>?</p>
										<a href="javascript:;" class="lx-reset-pictures" data-file="" data-img="" data-input="" data-default="">Oui</a>
										<a href="javascript:;" class="lx-cancel-delete">Non</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- JQuery -->
		<script src="js/jquery-1.12.4.min.js"></script>
		<!-- Popup Script -->
		<script src="js/jquery.popup.js"></script>
		<!-- Main Script -->
		<script src="js/script.js"></script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>