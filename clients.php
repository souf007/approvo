<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Clients#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Clients</title>
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
		<!-- Menu Improvements -->
		<link rel="stylesheet" href="css/menu-improvements.css">
		<!-- KPI Improvements -->
		<link rel="stylesheet" href="css/kpi-improvements.css">
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
						<h2>Clients</h2>
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
							if(preg_match("#Ajouter Clients#",$_SESSION['easybm_roles'])){	
							?>
							<div class="lx-add-form">
								<a href="javascript:;" class="lx-new lx-new-client lx-open-popup" data-header="Ajouter un nouveau" data-title="client">+ Nouveau client</a>
								<a href="javascript:;" class="lx-new lx-open-popup" data-title="importer"><i class="fa fa-upload"></i> Importer</a>
							</div>
							<?php
							}
							?>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Mot clé" data-table="clients" /></label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="company" id="company" placeholder="Choisissez une société" data-ids="" title="Société" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['rs'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['rs'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="paid" id="paid" title="État de paiement" placeholder="Statut de paiement" data-ids="" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<li><label><input type="checkbox" value="100% Payée" data-ids="paid" /> 100% Payée<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="En cours" data-ids="encours" /> En cours<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<label>
										<a href="clients.php" class="lx-refresh-filter"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-caisse-total lx-caisse-total-1"></div>
								<div class="lx-caisse-total lx-caisse-total-2"></div>
								<div class="lx-caisse-total lx-caisse-total-4"></div>
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-clients">

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
								if(preg_match("#Exporter Clients#",$_SESSION['easybm_roles'])){
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
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM clients WHERE trash='1'");
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="clients" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
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
			<div tabindex="0" class="lx-popup client">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> client</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="clientsform">
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label>
												<span>Client<sup>*</sup>: </span>
												<select name="fullname" data-isnotempty="" data-message="Choisissez un client !!" class="todropdown">
													<option value="">Choisissez un client</option>
													<?php
													$back = $bdd->query("SELECT id,code,ice,fullname,phone,address,email FROM clients WHERE fullname<>''".$multicompanies." ORDER BY fullname");
													while($row = $back->fetch()){
														?>
													<option value="<?php echo $row['fullname'];?>"><?php echo $row['fullname']." (".$row['code'].")";?></option>
														<?php 
													}
													?>
												</select>											
											</label>
										</div>
										<div class="lx-textfield lx-g2 lx-g lx-pb-0">
											<label><span>Code client: </span><input type="text" autocomplete="off" name="codecl" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label class="lx-advanced-select"><span>Société<sup>*</sup>:</span>
												<i class="fa fa-caret-down" style="top:22px;"></i>
												<input type="text" autocomplete="off" name="company" placeholder="Choisissez une société" data-isnotempty="" data-message="Choisissez une société" data-ids="" readonly />
												<div>
													<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
													<ul>
														<?php
														$back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
														while($row = $back->fetch()){
															?>
														<li style="margin-bottom:10px;"><label><input type="checkbox" value="<?php echo $row['rs'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['rs'];?><del class="checkmark"></del></label></li>
															<?php
														}
														?>
													</ul>
												</div>
											</label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>ICE: </span><input type="text" autocomplete="off" name="ice" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>IF: </span><input type="text" autocomplete="off" name="iff" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Téléphone: </span><input type="text" autocomplete="off" name="phone" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Email: </span><input type="text" autocomplete="off" name="email" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Adresse: </span><input type="text" autocomplete="off" name="address" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Note: </span><textarea name="note" /></textarea></label>
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
			<div tabindex="0" class="lx-popup importer">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Importation clients</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="importclientsform" enctype="multipart/form-data">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<p><b>Important: </b>Veuillez impoter un fichier excel avec les colonnes suivantes en respectant l'ordre (Nom et prénom (Obligatoire) - ICE (Unique) - Téléphone - Adresse - Email - Note - IDs sociétés séparer par point virgule ";" (Obligatoire)) ou bien <a href="ExampleClients.xlsx" style="padding:0px;font-weight:normal;background:none;color:#fb8500;">télécharger un modèle</a></p>
											<br />
											<p><b>NB: </b>Les clients existants seront automatiquement ignorés</p>
											<br />
											<div class="lx-importer">
												<input type="file" name="xlsfile" id="importclient" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
												<span>Choisissez un fichier (excel)</span>
											</div>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="id" value="0" />
											<a href="javascript:;">Importer</a>
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
									<h3>Export clients</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="code" data-title="Référence" checked /> Référence<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ice" data-title="ICE" checked /> ICE<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="fullname" data-title="Nom et présnom" checked /> Nom et présnom<del class="checkmark"></del></label>
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
											<label><input type="checkbox" name="columns" value="note" data-title="Note"  checked /> Note<del class="checkmark"></del></label>
										</div>
										<?php
										if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])){
										?>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ca" data-title="CA TTC" checked /> CA TTC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="paid" data-title="Payé TTC" checked /> Payé TTC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="encours" data-title="En cours TTC" checked /> En cours TTC<del class="checkmark"></del></label>
										</div>
										<?php
										}
										?>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="table" value="clients" />
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
										<p>Voulez vous vraiment supprimer ce client?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deleteclient" data-id="">Oui</a>
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
				loadClients($(".lx-pagination ul").attr("data-state"));
				toDropDown();
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>
