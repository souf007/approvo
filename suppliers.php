<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Fournisseurs#",$_SESSION['easybm_roles']) OR $settings['onlyproduct'] == "0"){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Fournisseurs</title>
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
						<h2>Fournisseurs</h2>
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
							if(preg_match("#Ajouter Fournisseurs#",$_SESSION['easybm_roles'])){	
							?>
							<div class="lx-add-form">
								<a href="javascript:;" class="lx-new lx-new-supplier lx-open-popup" data-header="Ajouter un nouveau" data-title="supplier">+ Nouveau fournisseur</a>
								<a href="javascript:;" class="lx-new lx-open-popup" data-title="importer"><i class="fa fa-upload"></i> Importer</a>
							</div>
							<?php
							}
							?>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Mot clé" data-table="suppliers" /></label>
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
												<li><label><input type="checkbox" value="100% payé" data-ids="paid" /> 100% payé<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="En cours" data-ids="encours" /> En cours<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<label>
										<a href="suppliers.php" class="lx-refresh-filter"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-caisse-total lx-caisse-total-1"></div>
								<div class="lx-caisse-total lx-caisse-total-2"></div>
								<div class="lx-caisse-total lx-caisse-total-4"></div>
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-suppliers">

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
								if(preg_match("#Exporter Fournisseurs#",$_SESSION['easybm_roles'])){
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
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM suppliers WHERE trash='1'");
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="suppliers" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
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
			<div tabindex="0" class="lx-popup supplier">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> fournisseur</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="suppliersform">
										<div class="lx-textfield lx-g2 lx-g lx-pb-0">
											<label><span>Fournisseur<sup>*</sup>: </span><input type="text" autocomplete="off" name="title" data-isnotempty="" data-message="Saisissez un fournisseur!!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-g lx-pb-0">
											<label><span>Code fournisseur: </span><input type="text" autocomplete="off" name="codefo" /></label>
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
											<label><span>Responsable<sup>*</sup>: </span><input type="text" autocomplete="off" name="respname" data-isnotempty="" data-message="Saisissez un responsable!!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Téléphone: </span><input type="text" autocomplete="off" name="respphone" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Email: </span><input type="text" autocomplete="off" name="respemail" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Fax: </span><input type="text" autocomplete="off" name="respfax" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>ICE: </span><input type="text" autocomplete="off" name="ice" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
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
									<h3>Importation fournisseurs</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="importsuppliersform" enctype="multipart/form-data">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<p><b>Important: </b>Veuillez impoter un fichier excel avec les colonnes suivantes en respectant l'ordre (Fournisseur (Obligatoire / Unique) - Responsable (Obligatoire) - Téléphone - Adresse - Email - Fax - Note - IDs sociétés séparer par point virgule ";" (Obligatoire)) ou bien <a href="ExampleSuppliers.xlsx" style="padding:0px;font-weight:normal;background:none;color:#fb8500;">télécharger un modèle</a></p>
											<br />
											<p><b>NB: </b>Les fournisseurs existants seront automatiquement ignorés</p>
											<br />
											<div class="lx-importer">
												<input type="file" name="xlsfile" id="importsupplier" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
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
									<h3>Export Fournisseurs</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="code" data-title="Référence" checked /> Référence<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="title" data-title="Fournisseur" checked /> Fournisseur<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="respname" data-title="Responsable" checked /> Responsable<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="address" data-title="Adresse" checked /> Adresse<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ice" data-title="ICE" checked /> ICE<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="respphone" data-title="Téléphone" checked /> Téléphone<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="respemail" data-title="Email" checked /> Email<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="respfax" data-title="Fax" checked /> Fax<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="note" data-title="Note" checked /> Note<del class="checkmark"></del></label>
										</div>
										<?php
										if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
										?>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="ca" data-title="Chiffre d'affaires TTC" checked /> Chiffre d'affaires TTC<del class="checkmark"></del></label>
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
											<input type="hidden" name="table" value="suppliers" />
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
										<p>Voulez vous vraiment supprimer ce fournisseur?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deletesupplier" data-id="">Oui</a>
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
				loadSuppliers($(".lx-pagination ul").attr("data-state"));
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>
