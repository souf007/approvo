<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Sociétés#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Sociétés</title>
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
						<h2>Sociétés</h2>
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
						<div class="lx-g1">
							<?php
							if(preg_match("#Ajouter Sociétés#",$_SESSION['easybm_roles'])){	
							?>
							<div class="lx-add-form">
								<a href="javascript:;" class="lx-new lx-new-company lx-open-popup" data-header="Ajouter un nouveau" data-title="company">+ Nouvelle société</a>
							</div>
							<?php
							}
							?>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Mot clé" data-table="companies" /></label>
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<label>
										<a href="companies.php" class="lx-refresh-filter"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-companies">

								</div>
							</div>
							<?php
							$nb = 50;
							if($parametres['nbrows'] != "" AND $parametres['nbrows'] != "0"){
								$nb = $parametres['nbrows'];
							}				
							?>
							<div class="lx-action-bulk">
								<?php
								if(preg_match("#Exporter Sociétés#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-new lx-open-popup" data-title="export"><i class="fa fa-download"></i> Exporter</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
								}
								?>
								<label><span>Afficher: </span>
									<select name="nbrows"><option value="50" <?php if($nb==50){echo "selected";}?>>50</option>
										<option value="100" <?php if($nb==100){echo "selected";}?>>100</option>
										<option value="200" <?php if($nb==200){echo "selected";}?>>200</option>
										<option value="500" <?php if($nb==500){echo "selected";}?>>500</option>
									</select>
								</label><span>lignes par page</span>
							</div>
							<?php
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM companies WHERE trash='1'".$companiesid);
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="companies" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
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
			<div tabindex="0" class="lx-popup company">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> société</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="companiesform">
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Raison social<sup>*</sup>: </span><input type="text" autocomplete="off" name="rs" data-isnotempty="" data-message="Saisissez une raison social !!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Téléphone: </span><input type="text" autocomplete="off" name="phone" data-isphone="" data-message="Saisissez un téléphone !!" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Adresse: </span><input type="text" autocomplete="off" name="address" data-isnotempty="" data-message="Saisissez une adresse !!" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>E-mail: </span><input type="text" autocomplete="off" name="email" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Site web: </span><input type="text" autocomplete="off" name="website" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>Capitale: </span><input type="text" autocomplete="off" name="capital" /></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>RC: </span><input type="text" autocomplete="off" name="rc" data-isnotempty="" data-message="Saisissez un RC !!" /></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>Patente: </span><input type="text" autocomplete="off" name="patente" data-isnotempty="" data-message="Saisissez une patente !!" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>IF: </span><input type="text" autocomplete="off" name="iff" data-isnotempty="" data-message="Saisissez un IF !!" /></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>CNSS: </span><input type="text" autocomplete="off" name="cnss" data-isnotempty="" data-message="Saisissez un CNSS !!" /></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>ICE: </span><input type="text" autocomplete="off" name="ice" data-isnotempty="" data-message="Saisissez un ICE !!" /></label>
										</div>			
										<div class="lx-clear-fix"></div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des factures<sup>*</sup>: </span><input type="text" autocomplete="off" name="facture" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des devis<sup>*</sup>: </span><input type="text" autocomplete="off" name="devis" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>		
										<div class="lx-clear-fix"></div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des factures avoir<sup>*</sup>: </span><input type="text" autocomplete="off" name="avoir" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des bons de retour<sup>*</sup>: </span><input type="text" autocomplete="off" name="br" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>	
										<div class="lx-clear-fix"></div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des factures proforma<sup>*</sup>: </span><input type="text" autocomplete="off" name="factureproforma" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des bons de livraison<sup>*</sup>: </span><input type="text" autocomplete="off" name="bl" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>	
										<div class="lx-clear-fix"></div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des bons de sortie<sup>*</sup>: </span><input type="text" autocomplete="off" name="bs" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Début de numérotation des bons de commande<sup>*</sup>: </span><input type="text" autocomplete="off" name="blcommand" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span style="white-space:nowrap;">Début de numérotation des bons de récéption commande<sup>*</sup>: </span><input type="text" autocomplete="off" name="brcommand" value="1" data-isnumber="" data-notzero="" data-message="Saisissez un numéro !!" /></label>
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
			<div tabindex="0" class="lx-popup bankaccount">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> compte banquaire</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="bankaccountsform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>N° compte / RIB<sup>*</sup>: </span><input type="text" autocomplete="off" name="rib" data-isnotempty="" data-message="Saisissez un N° compte / RIB !!" /></label>
										</div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>La banque<sup>*</sup>: </span><input type="text" autocomplete="off" name="bank" data-isphone="" data-message="Saisissez une banque !!" /></label>
										</div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Agence: </span><input type="text" autocomplete="off" name="agency" /></label>
										</div>			
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="company" value="0" />
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
			<div tabindex="0" class="lx-popup costumizeextrainfo">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Informations supplimentaires sur le document</h3>
								</div>
								<div class="lx-add-form">
									<div class="lx-tabs lx-tabs-docs" style="margin:0px;padding:15px;">
										<a href="javascript:;" class="active" data-document="facture" data-company="">Facture</a>
										<a href="javascript:;" data-document="devis" data-company="">Devis</a>
										<a href="javascript:;" data-document="avoir" data-company="">Factures avoir</a>
										<a href="javascript:;" data-document="br" data-company="">Bons de retour</a>
										<a href="javascript:;" data-document="factureproforma" data-company="">facture proforma</a>
										<a href="javascript:;" data-document="bl" data-company="">Bons de livraison</a>
										<a href="javascript:;" data-document="bs" data-company="">Bons de sortie</a>
										<a href="javascript:;" data-document="bc" data-company="">Bon de commande</a>
										<a href="javascript:;" data-document="bre" data-company="">Bon de récéption</a>
									</div>
									<form autocomplete="off" action="#" method="post" id="costumizeextrainfoform">
										<div class="lx-docs-form">

										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="company" value="0" />
											<input type="hidden" name="document" value="facture" />
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
									<h3>Export sociétés</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="rs" data-title="Raison social" checked /> Raison social<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="phone" data-title="Téléphone" checked /> Téléphone<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="address" data-title="Adresse" checked /> Adresse<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="email" data-title="Email" checked /> Email<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="website" data-title="Website" checked /> Website<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="capital" data-title="Capitale" checked /> Capitale<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="rc" data-title="RC" checked /> RC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="patente" data-title="Patente" checked /> Patente<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="iff" data-title="IF" checked /> IF<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="cnss" data-title="CNSS" checked /> CNSS<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ice" data-title="ICE" checked /> ICE<del class="checkmark"></del></label>
										</div>
										<?php
										if(preg_match("#CA et marge ... Sociétés#",$_SESSION['easybm_roles'])){
										?>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="nbcmds" data-title="Nb. de commandes livrées" checked /> Nb. de commandes livrées<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ca" data-title="Chiffre d'affaires TTC" checked /> CA TTC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="marge" data-title="Marge HT" checked /> Marge HT<del class="checkmark"></del></label>
										</div>
										<?php
										}
										?>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="table" value="companies" />
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
										<p>Voulez vous vraiment supprimer cette société?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deletecompany" data-id="">Oui</a>
										<a href="javascript:;" class="lx-cancel-delete">Non</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Popup -->	
			<div tabindex="0" class="lx-popup deleterecord1">
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
										<p>Voulez vous vraiment supprimer ce compte banquaire?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deletebankaccount" data-id="">Oui</a>
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
				loadCompanies($(".lx-pagination ul").attr("data-state"));
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>