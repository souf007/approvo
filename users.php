<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Utilisateurs#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Utilisateurs</title>
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
						<h2>Utilisateurs</h2>
					</div>
					<div class="lx-clear-fix"></div>
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
					<div class="lx-page-content">
						<div class="lx-g1">
							<?php
							if(preg_match("#Ajouter Utilisateurs#",$_SESSION['easybm_roles'])){	
							?>
							<div class="lx-add-form">
								<a href="javascript:;" class="lx-new lx-new-user lx-open-popup" data-header="Ajouter un nouveau" data-title="user">+ Nouveau utilisateur</a>
							</div>
							<?php
							}
							?>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Mot clé" data-table="users" /></label>
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<label>
										<a href="users.php" class="lx-refresh-filter"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-users">

								</div>
							</div>
							<?php
							$nb = 50;
							if($parametres['nbrows'] != "" AND $parametres['nbrows'] != "0"){
								$nb = $parametres['nbrows'];
							}				
							?>
							<div class="lx-action-bulk">
								<label><span>Afficher: </span>
									<select name="nbrows">
										<option value="50" <?php if($nb==50){echo "selected";}?>>50</option>
										<option value="100" <?php if($nb==100){echo "selected";}?>>100</option>
										<option value="200" <?php if($nb==200){echo "selected";}?>>200</option>
										<option value="500" <?php if($nb==500){echo "selected";}?>>500</option>
									</select>
								</label><span>lignes par page</span>
							</div>
							<?php
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM users WHERE trash='1'");
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="users" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
									<li><span>Page <ins>1</ins> sur <abbr><?php echo $nbpages;?></abbr></span></li>
									<li><a href="javascript:;" class="previous disabled"><i class="fa fa-angle-left"></i></a></li>
									<li>
										<select id="pgnumber">
											<?php
											for($i=1;$i<=$nbpages;$i++){
												?>
											<option value="<?php echo ($i-1);?>"><?php echo $i;?></option>
												<?php
											}
											?>
										</select>
									</li>
									<li><a href="javascript:;" class="next <?php if($nbpages == 1){echo 'disabled';}?>"><i class="fa fa-angle-right"></i></a></li>
								</ul>
								<div class="lx-clear-fix"></div>
							</div>
							<div class="lx-clear-fix"></div>
						</div>
						<div class="lx-clear-fix"></div>
					</div>
					<div class="lx-clear-fix"></div>
				</div>
				<div class="lx-clear-fix"></div>
			</div>
			<!-- End Popup -->
			<div tabindex="0" class="lx-popup user">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> utilisateur</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="usersform">
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Nom et prénom<sup>*</sup>: </span><input type="text" autocomplete="off" name="fullname" data-isnotempty="" data-message="Saisissez un nom et prénom!!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Téléphone: </span><input type="text" autocomplete="off" name="phone" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Login<sup>*</sup>: </span><input type="text" autocomplete="off" name="email" data-isemail="" data-message="Saisissez un login" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Mot de passe<sup>*</sup>: </span><input type="password" name="password" data-ispassword="" data-message="Saisissez minimum 6 caratères" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<span>Permissions d'accès<sup>*</sup>:</span>
											<span><label class="lx-selectall-all-roles"><input type="checkbox" name="selectall" value="" /> Tout sélectionner<del class="checkmark"></del></label></span>
											<div class="lx-table lx-table-roles">
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Page/Permission</td>
														<td>Consulter</td>
														<td>Ajouter</td>
														<td>Modifier</td>
														<td>Supprimer</td>
														<td>Exporter</td>
														<td>CA, Payé / Remboursé, En cours ...</td>
														<td>Consultation de la journée en cours seulement</td>
													</tr>
													<tr>
														<td style="width:auto;white-space:nowrap;">Tableau de bord</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Tableau de bord" /><del class="checkmark"></del></label></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Tableau de bord" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Trésorerie</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Trésorerie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Trésorerie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Trésorerie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Trésorerie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Trésorerie" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Trésorerie" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Factures</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Factures" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Factures" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Factures" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Factures" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Factures" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Factures" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Devis</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Devis" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Devis" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Devis" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Devis" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Devis" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Devis" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Factures proforma</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Factures proforma" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Factures proforma" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Factures proforma" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Factures proforma" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Factures proforma" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Factures proforma" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Bons de livraison</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Bons de livraison" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Bons de livraison" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Bons de livraison" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Bons de livraison" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Bons de livraison" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Bons de livraison" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Bons de sortie</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Bons de sortie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Bons de sortie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Bons de sortie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Bons de sortie" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Bons de sortie" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Bons de sortie" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Bons de retour</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Bons de retour" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Bons de retour" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Bons de retour" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Bons de retour" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Bons de retour" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Bons de retour" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Factures avoir</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Factures avoir" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Factures avoir" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Factures avoir" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Factures avoir" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Factures avoir" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Factures avoir" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Clients</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Clients" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Clients" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Clients" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Clients" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Clients" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="CA Clients" /><del class="checkmark"></del></label></td>
														<td></td>
													</tr>
													<tr>
														<td>Bons de commande</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Bons de commande" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Bons de commande" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Bons de commande" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Bons de commande" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Bons de commande" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Bons de commande" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Bons de réception</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Bons de réception" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Bons de réception" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Bons de réception" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Bons de réception" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Bons de réception" /><del class="checkmark"></del></label></td>
														<td></td>
														<td><label><input type="checkbox" name="roles" value="Consultation de la journée en cours seulement Bons de réception" /><del class="checkmark"></del></label></td>
													</tr>
													<tr>
														<td>Fournisseurs</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Fournisseurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Fournisseurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Fournisseurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Fournisseurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Fournisseurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="CA Fournisseurs" /><del class="checkmark"></del></label></td>
														<td></td>
													</tr>
													<tr>
														<td>Sociétés</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Sociétés" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Sociétés" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Sociétés" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Sociétés" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Exporter Sociétés" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="CA Sociétés" /><del class="checkmark"></del></label></td>
														<td></td>
													</tr>
													<tr>
														<td>Utilisateurs</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Utilisateurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter Utilisateurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier Utilisateurs" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer Utilisateurs" /><del class="checkmark"></del></label></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td style="width:auto;white-space:nowrap;">TVA</td>
														<td><label><input type="checkbox" name="roles" value="Consulter TVA" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Ajouter TVA" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Modifier TVA" /><del class="checkmark"></del></label></td>
														<td><label><input type="checkbox" name="roles" value="Supprimer TVA" /><del class="checkmark"></del></label></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td>Formation</td>
														<td><label><input type="checkbox" name="roles" value="Consulter Formation" /><del class="checkmark"></del></label></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Notifications</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Consultation des notifications" /> Consultation des notifications<del class="checkmark"></del></label>
															<label><input type="checkbox" name="roles" value="Réglage des notifications" /> Réglage des notifications<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Modification date opération</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Modification date opération" /> Oui<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Transformation / Dupplication documents</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Transformation / Dupplication documents" /> Oui<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Modification statut de paiement</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Modification statut de paiement" /> Oui<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Suppression d'historique de paiement</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Suppression historique de paiement" /> Oui<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Télécharger Backup</td>
													</tr>
													<tr>
														<td>
															<label><input type="checkbox" name="roles" value="Télécharger Backup" /> Télécharger Backup<del class="checkmark"></del></label>
														</td>
													</tr>
												</table>
												<label><input type="hidden" name="rolestext" value="" /></label>
												<table cellpadding="0" cellspacing="0">
													<tr>
														<td>Sociétés à gérer</td>
													</tr>
													<tr>
														<td>
															<?php
															$back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1' ORDER BY rs");
															while($row = $back->fetch()){
																?>
															<label><input type="checkbox" name="companies" value="<?php echo $row['id'];?>" /> <?php echo $row['rs'];?><del class="checkmark"></del></label> &nbsp; 
																<?php 
															}
															?>
														</td>
													</tr>
												</table>
												<label><input type="hidden" name="companiestext" value="" data-isnotempty="" data-message="Veuillez choisir au moins une société" /></label>
											</div>
										</div>	
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="id" value="0" />
											<a href="javascript:;" class="">Enregistrer</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Popup -->
			<div tabindex="0" class="lx-popup export">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Export utilisateurs</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="email" data-title="Email" checked /> Email<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="password" data-title="Mot de passe" checked /> Mot de passe<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="fullname" data-title="Nom et prénom" checked /> Nom et prénom<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="phone" data-title="Téléphone" checked /> Téléphone<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="type" data-title="Type" checked /> Type<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="roles" data-title="Permission" checked /> Permission<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="table" value="users" />
											<a href="javascript:;" class="">Télécharger</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Popup -->	
			<div tabindex="0" class="lx-popup resetpassword">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Réinitialiser mot de passe</h3>
								</div>
								<div class="lx-add-form">
									<div class="lx-delete-box">
										<p>Voulez vous vraiment réinitialiser mot de passe de cet utilisateur?</p>
										<a href="javascript:;" class="lx-reset-pass" data-id="">Oui</a>
										<a href="javascript:;" class="lx-cancel-delete">Non</a>
									</div>
								</div>
							</div>
						</div>
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
										<p>Voulez vous vraiment supprimer cet utilisateur?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deleteuser" data-id="">Oui</a>
										<a href="javascript:;" class="lx-cancel-delete">Non</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- JQuery Script -->
		<script src="js/jquery-1.12.4.min.js"></script>
		<!-- Popup Script -->
		<script src="js/jquery.popup.js"></script>
		<!-- Calendar Script -->
		<script src="js/moment.min.js"></script>
		<script src="js/daterangepicker.js"></script>
		<!-- Main Script -->
		<script src="js/script.js"></script>
		<script>
			$(document).ready(function(){
				loadUsers($(".lx-pagination ul").attr("data-state"));
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>