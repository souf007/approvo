<?php
session_start();
include("config.php");

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Historique des paiements#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Historique des paiements</title>
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
		<link rel="stylesheet" href="css/ion.rangeSlider.min.css"/>
		<!-- Fav Icon -->
		<link rel="shortcut icon" href="favicon.ico">
		<?php include("onesignal.php");?>
		<script src="js/tinymce/tinymce.min.js"></script>
		<script>
			tinymce.init({
			  selector: 'textarea',
			  height: 200,
			  menubar: false,
			  plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table paste code help wordcount'
			  ],
			  toolbar: 'undo redo | ' +
			  'bold italic underline | alignleft aligncenter ' +
			  'alignright alignjustify | bullist numlist outdent indent | ' +
			  'removeformat',
			  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
			});
		</script>
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
						<h2>Autres dépences et recettes</h2>
					</div>
					<div class="lx-clear-fix"></div>
					<div class="lx-page-content">
						<div class="lx-g1">
							<?php
							if(preg_match("#Ajouter Autres dépences et recettes#",$_SESSION['easybm_roles'])){	
							?>
							<div class="lx-add-form">
								<a href="javascript:;" class="lx-new lx-new-expense lx-open-popup" data-header="Ajouter une nouvelle" data-title="expense">+ Nouvelle dépence / recette</a>
							</div>
							<?php
							}
							?>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Mot clé" data-table="expense" /></label>
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
										<input type="text" autocomplete="off" name="type" id="type" value="<?php echo isset($_GET['type'])?$_GET['type']:"";?>" placeholder="Choisissez un nature" data-ids="<?php echo isset($_GET['type'])?$_GET['type']:"";?>" title="Nature" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<li><label><input type="checkbox" value="Entrée" data-ids="Entrée" <?php echo isset($_GET['type'])?($_GET['type']=="Entrée"?"checked":""):"";?> /> Entrée<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Sortie" data-ids="Sortie" <?php echo isset($_GET['type'])?($_GET['type']=="Sortie"?"checked":""):"";?> /> Sortie<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="modepayment" id="modepayment" title="Mode de paiement" value="<?php echo isset($_GET['modepayment'])?$_GET['modepayment']:"";?>" placeholder="Choisissez un mode de paiement" data-ids="<?php echo isset($_GET['modepayment'])?$_GET['modepayment']:"";?>" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<li><label><input type="checkbox" value="Espèce" data-ids="Espèce" <?php echo isset($_GET['modepayment'])?(in_array("Espèce", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> Espèce<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Chèque" data-ids="Chèque" <?php echo isset($_GET['modepayment'])?(in_array("Chèque", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> Chèque<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Effet" data-ids="Effet" <?php echo isset($_GET['modepayment'])?(in_array("Effet", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> Effet<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Virement" data-ids="Virement" <?php echo isset($_GET['modepayment'])?(in_array("Virement", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> Virement<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="TPE" data-ids="TPE" <?php echo isset($_GET['modepayment'])?(in_array("TPE", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> TPE<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="En attente de paiement" data-ids="En attente de paiement" <?php echo isset($_GET['modepayment'])?(in_array("En attente de paiement", explode(",",$_GET['modepayment']))?"checked":""):"";?> /> En attente de paiement<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="status" id="status" title="Statut" value="<?php echo isset($_GET['status'])?$_GET['status']:"";?>" placeholder="Choisissez un statut" data-ids="<?php echo isset($_GET['statusid'])?$_GET['statusid']:"";?>" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<li><label><input type="checkbox" value="Réglements reçus non encaissés" data-ids="a1a" <?php echo isset($_GET['statusid'])?(in_array("a1a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements reçus non encaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements émis non décaissés" data-ids="a2a" <?php echo isset($_GET['statusid'])?(in_array("a2a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements émis non décaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements reçus non échus" data-ids="a9a" <?php echo isset($_GET['statusid'])?(in_array("a9a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements reçus non échus <del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements émis non échus" data-ids="a13a" <?php echo isset($_GET['statusid'])?(in_array("a13a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements émis non échus <del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements sans date d'écheance" data-ids="a10a" <?php echo isset($_GET['statusid'])?(in_array("a10a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements sans date d'écheance<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements reçus échus non encaissés" data-ids="a4a" <?php echo isset($_GET['statusid'])?(in_array("a4a", explode(",",$_GET['statusid']))?"checked":""):"";?>/> Réglements reçus échus non encaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements émis échus non décaissés" data-ids="a5a" <?php echo isset($_GET['statusid'])?(in_array("a5a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements émis échus non décaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Chèques / effets (reçus) échus non remis" data-ids="a3a" <?php echo isset($_GET['statusid'])?(in_array("a3a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Chèques / effets (reçus) échus non remis<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Chèques / effets (reçus) non remis" data-ids="a14a" <?php echo isset($_GET['statusid'])?(in_array("a14a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Chèques / effets (reçus) non remis<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Chèques / effets (reçus) remis" data-ids="a6a" <?php echo isset($_GET['statusid'])?(in_array("a6a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Chèques / effets (reçus) remis<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements reçus encaissés" data-ids="a7a" <?php echo isset($_GET['statusid'])?(in_array("a7a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements reçus encaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Réglements émis décaissés" data-ids="a12a" <?php echo isset($_GET['statusid'])?(in_array("a12a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Réglements émis décaissés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Chèques / effets impayés" data-ids="a8a" <?php echo isset($_GET['statusid'])?(in_array("a8a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Chèques / effets impayés<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Exclure les impayés" data-ids="a11a" <?php echo isset($_GET['statusid'])?(in_array("a11a", explode(",",$_GET['statusid']))?"checked":""):"";?> /> Exclure les impayés<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select" style="<?php echo preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])?"":"display:none;";?>">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="client" id="client" placeholder="Choisissez un client" data-ids="" title="Client" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT id,code,fullname FROM clients WHERE fullname<>'' ORDER BY fullname");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['fullname'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['fullname']." (".$row['code'].")";?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select" style="<?php echo preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])?"":"display:none;";?>">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="supplier" id="supplier" placeholder="Choisissez un fournisseur" data-ids="" title="Fournisseur" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT id,title FROM suppliers WHERE trash='1' ORDER BY title");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['title'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['title'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="nature" id="nature" placeholder="Choisissez une catégorie" data-ids="" title="Catégorie" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT DISTINCT nature FROM payments ORDER BY nature");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['nature'];?>" data-ids="<?php echo $row['nature'];?>" /> <?php echo $row['nature'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select" style="<?php echo !preg_match("#(Oui,Non)#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="invoiced" id="invoiced" placeholder="Facturé / Non facturé" title="Facturé / Non facturé" value="<?php echo preg_match("#(Oui,Non)#",$_SESSION['easybm_roles'])?"":(preg_match("#(Oui)#",$_SESSION['easybm_roles'])?"Oui":(preg_match("#(Non)#",$_SESSION['easybm_roles'])?"Non":""));?>" data-ids="" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<li><label><input type="checkbox" value="Facuré" data-ids="Oui" <?php echo (preg_match("#(Oui)#",$_SESSION['easybm_roles']) AND !preg_match("#(Non)#",$_SESSION['easybm_roles']))?"checked":"";?>/> Facturé<del class="checkmark"></del></label></li>
												<li><label><input type="checkbox" value="Non facturé" data-ids="Non" <?php echo (!preg_match("#(Oui)#",$_SESSION['easybm_roles']) AND preg_match("#(Non)#",$_SESSION['easybm_roles']))?"checked":"";?>/> Non facturé<del class="checkmark"></del></label></li>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="imputation" id="imputation" placeholder="Choisissez une imputation comptable" data-ids="" title="Imputation comptable" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT DISTINCT imputation FROM payments WHERE imputation<>'' ORDER BY imputation");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['imputation'];?>" data-ids="<?php echo $row['imputation'];?>" /> <?php echo $row['imputation'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="rib" id="rib" placeholder="Choisissez un compte banquaire" data-ids="" title="Compte banquaire" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT * FROM bankaccounts WHERE 1=1".$multicompanies);
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['bank']." | ".$row['rib'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['bank']." | ".$row['rib'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<?php
									$styleday = "";
									$rangedateplaceholder = "Date création";
									$rangedate = "";
									$startdate = "";
									$enddate = "";
									if(preg_match("#Consultation de la journée en cours seulement Historique des paiements#",$_SESSION['easybm_roles'])){
										$styleday = "display:none;";
										$rangedate = gmdate("d/m/Y")." - ".gmdate("d/m/Y");
										$rangedateplaceholder = "Date création";
										$startdate = gmdate("d/m/Y");
										$enddate = gmdate("d/m/Y");	
									}
									elseif(isset($_GET['datestart']) AND isset($_GET['dateend'])){
										$rangedate = $_GET['datestart']." - ".$_GET['dateend'];
										$startdate = $_GET['datestart'];
										$enddate = $_GET['dateend'];											
									}

									$rangedatedue = "";
									$startdatedue = "";
									$enddatedue = "";									
									if(isset($_GET['dateduestart']) AND isset($_GET['datedueend'])){
										$rangedatedue = $_GET['dateduestart']." - ".$_GET['datedueend'];
										$startdatedue = $_GET['dateduestart'];
										$enddatedue = $_GET['datedueend'];											
									}
									?>
									<label style="<?php echo $styleday;?>"><input type="text" autocomplete="off" name="dateadd" id="dateadd" title="Date création" value="<?php echo $rangedate;?>" placeholder="<?php echo $rangedateplaceholder;?>" readonly style="background:white;cursor:pointer;" /></label>
									<input type="hidden" name="datestart" id="datestart" value="<?php echo $startdate;?>" />
									<input type="hidden" name="dateend" id="dateend" value="<?php echo $enddate;?>" />
									<label><input type="text" autocomplete="off" name="datedue" id="datedue" title="Date d'écheance" data-table="jexpense" value="<?php echo $rangedatedue;?>" placeholder="Date d'écheance" readonly style="background:white;cursor:pointer;" /></label>
									<input type="hidden" name="dateduestart" id="dateduestart" value="<?php echo $startdatedue;?>" />
									<input type="hidden" name="datedueend" id="datedueend" value="<?php echo $enddatedue;?>" />
									<label><input type="text" autocomplete="off" name="dateremis" id="dateremis" data-table="jexpense" value="" title="Date de remise" placeholder="Date de remise" readonly style="background:white;cursor:pointer;" /></label>
									<input type="hidden" name="dateremisstart" id="dateremisstart" value="" />
									<input type="hidden" name="dateremisend" id="dateremisend" value="" />
									<label><input type="text" autocomplete="off" name="datepaid" id="datepaid" data-table="jexpense" value="" title="Date d'enexpensement / déexpensement (Rapprochement)" placeholder="Date d'enexpensement / déexpensement (Rapprochement)" readonly style="background:white;cursor:pointer;" /></label>
									<input type="hidden" name="datepaidstart" id="datepaidstart" value="" />
									<input type="hidden" name="datepaidend" id="datepaidend" value="" />
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<br />
									<fieldset style="display:inline-block;padding:0px 10px;">
										<legend>Prix TTC</legend>
										<label style="margin-right:0px;margin-bottom:5px;" class="lx-price-range">
											<input type="text" class="js-range-slider" name="my_range" value="" />
											<input type="hidden" id="pricemin" />
											<input type="hidden" id="pricemax" />
											<a href="javascript:;" onclick="loadCaisse('1')" class="lx-price-filter">Appliquer</a>
										</label>
									</fieldset>
									<label>
										<a href="payments.php" class="lx-refresh-filter" style="margin-left:10px;"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-expense-total lx-expense-total-1"></div>
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-expense">

								</div>
								<div class="lx-expense-total lx-expense-total-2"></div>
								<div class="lx-expense-total-3"></div>
							</div>
							<?php
							$nb = 50;
							if($parametres['nbrows'] != "" AND $parametres['nbrows'] != "0"){
								$nb = $parametres['nbrows'];
							}
							?>
							<div class="lx-action-bulk">
								<label><span>Afficher: </span>
									<select name="nbrows"><option value="50" <?php if($nb==50){echo "selected";}?>>50</option>
										<option value="100" <?php if($nb==100){echo "selected";}?>>100</option>
										<option value="200" <?php if($nb==200){echo "selected";}?>>200</option>
										<option value="500" <?php if($nb==500){echo "selected";}?>>500</option>
									</select>
								</label><span>lignes par page</span>
							</div>
							<?php
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM payments WHERE trash='1'");
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="expense" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
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
			<div tabindex="0" class="lx-popup expense">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> dépence / recette</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="expenseform">
										<div class="lx-textfield lx-g1 lx-pb-0" style="<?php echo !preg_match("#Modification date opération#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
											<label><span>Date de création: </span><input type="text" name="dateaddcommand" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Affectée à la société<sup>*</sup>:</span>
												<select name="company" class="lx-companies-list" data-isnumber="" data-message="Choisissez une société!!">
													<?php
													$back = $bdd->query("SELECT id,rs FROM companies WHERE trash='1'".$companiesid." ORDER BY rs");
													if($back->rowCount() > 1){
													?>
													<option value="">Choisissez une société</option>
													<?php
													}
													while($row = $back->fetch()){
														?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['rs'];?></option>
														<?php 
													}
													?>
												</select>
											</label>
										</div>											
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Nature<sup>*</sup>:</span>
												<select name="type" data-isnotempty="" data-message="Choisissez un nature!!">
													<option value="">Choisissez un type</option>
													<option value="Entrée">Entrée</option>
													<option value="Sortie">Sortie</option>
												</select>
											</label>
										</div>	
										<div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yesno">
											<label><span>Facturé<sup>*</sup>:</span>
												<select name="invoiced" data-isnotempty="" data-message="Choisissez une option!!">
													<option value=""></option>
													<option value="Oui">Oui</option>
													<option value="Non">Non</option>
												</select>
											</label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-clientsupplier">
											<div class="lx-textfield lx-g1 lx-pb-0 lx-client-expense" style="display:none;">
												<label><span>Client:</span>
													<select name="client" class="todropdown" data-isnotempty="" data-message="Veuillez choisir un client de la liste!!">
														<option value="">Choisissez un client</option>
														<?php
														$back = $bdd->query("SELECT id,fullname FROM clients WHERE trash='1' AND fullname<>'' ORDER BY fullname");
														while($row = $back->fetch()){
															?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['fullname'];?></option>
															<?php 
														}
														?>
													</select>
												</label>
											</div>	
											<div class="lx-textfield lx-g1 lx-pb-0 lx-supplier-expense" style="display:none;">
												<label><span>Fournisseur:</span>
													<select name="supplier" class="todropdown" data-isnotempty="" data-message="Veuillez choisir un fournisseur de la liste!!">
														<option value="">Choisissez un fournisseur</option>
														<?php
														$back = $bdd->query("SELECT id,title FROM suppliers WHERE trash='1' AND title<>'' ORDER BY title");
														while($row = $back->fetch()){
															?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
															<?php 
														}
														?>
													</select>
												</label>
											</div>	
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Catégorie:</span>
												<select name="nature" class="todropdown">
													<option value="">Choisissez une catégorie</option>
													<?php
													$back = $bdd->query("SELECT DISTINCT nature FROM payments WHERE nature<>''".$multicompanies." ORDER BY nature");
													while($row = $back->fetch()){
														?>
													<option value="<?php echo $row['nature'];?>"><?php echo $row['nature'];?></option>
														<?php 
													}
													?>
												</select>
											</label>
										</div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Libellé<sup>*</sup>: </span><input type="text" autocomplete="off" name="title" data-isnotempty="" data-message="Saisissez un libellé!!" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Montant TTC<sup>*</sup>: </span><input type="text" autocomplete="off" name="price" data-isnumber="" data-message="Saisissez un montant!!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Mode de paiement<sup>*</sup>:</span>
												<select name="modepayment" data-isnotempty="" data-message="Choisissez un mode de paiement!">
													<option value="">Choisissez un mode de paiement</option>
													<option value="Espèce">Espèce</option>
													<option value="Chèque">Chèque</option>
													<option value="Effet">Effet</option>
													<option value="Virement">Virement</option>
													<option value="TPE">TPE</option>
												</select>
												<input type="hidden" name="modepayment" />
											</label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0 countinprofit">
											<label><span>Taux de TVA<sup>*</sup>:</span>
												<select name="tva" data-isnotempty="" data-message="Choisissez un taux de TVA!">
													<option value=""></option>
													<?php
													$back = $bdd->query("SELECT * FROM tvas ORDER BY tva");
													while($row = $back->fetch()){
														?>
													<option value="<?php echo $row['tva'];?>%"><?php echo $row['tva'];?>%</option>
														<?php
													}
													?>
												</select>
											</label>
										</div>	
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
											<label><span style="color:#FFFFFF;">Remis: </span></label>
											<label style="padding:10px 10px;font-weight:bold;border:1px solid #fb8500;color:#fb8500;border-radius:6px;"><input type="checkbox" name="remis" value="0" /> Marquer comme rémis<del class="checkmark" style="top:8px;left:8px;"></del></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
											<label><span>Date remis: </span><input type="text" name="dateremiscommand" data-isnotempty="" data-message="Choisissez une date!!" /></label>
										</div>
										<div class="lx-textfield lx-g3 lx-pb-0 lx-remis" style="display:none;">
											<label><span>N° de remise: </span><input type="text" autocomplete="off" name="nremise" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0 lx-umpaid" style="display:none;">
											<label style="padding:10px 10px;font-weight:bold;border:1px solid #FF0000;color:#FF0000;border-radius:6px;"><input type="checkbox" name="paid" value="0" /> Marquer comme impayé<del class="checkmark" style="top:8px;left:8px;"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
											<label><span>Date d'écheance: </span><input type="text" name="dateduecommand" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
											<label><span>Date d'encaissement / décaissement (Rapprochement): </span><input type="text" name="datepaidcommand" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
											<label><span>Compte banquaire (encaissement / décaissement):</span>
												<select name="rib" class="lx-bankaccounts-list">
													<option value="0">Choisissez un compte banquaire</option>
												</select>
											</label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0 lx-invoiced-yes" style="display:none;">
											<label><span>Imputation comptable:</span>
												<select name="imputation" class="todropdown">
													<option value="">Saisissez une imputation comptable</option>
													<?php
													$back = $bdd->query("SELECT DISTINCT imputation FROM payments WHERE imputation<>'' ORDER BY imputation");
													while($row = $back->fetch()){
														?>
													<option value="<?php echo $row['imputation'];?>"><?php echo $row['imputation'];?></option>
														<?php 
													}
													?>
												</select>
											</label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Description: </span><textarea type="text" name="description" id="description"></textarea></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="page1" value="expense" />
											<input type="hidden" name="page" value="expense" />
											<input type="hidden" name="id" value="0" />
											<a href="javascript:;" class="">Enregistrer</a>
											<br />
											<br />
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
									<h3>Export dépences et recettes</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="dateadd" data-title="Date d'ajout" checked /> Date d'ajout<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="company" data-title="Société" checked /> Société<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="code" data-title="Code" checked /> Code<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="type" data-title="Nature" checked /> Nature<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="modepayment" data-title="Mode de paiement" checked /> Mode de paiement<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="price" data-title="Montant TTC" checked /> Montant TTC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="title" data-title="Libellé" checked /> Libellé<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="note" data-title="Note" checked /> Note<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="datedue" data-title="Date d'écheance" checked /> Date d'écheance<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="client" data-title="Client" checked /> Client<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="supplier" data-title="Fournisseur" checked /> Fournisseur<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="imputation" data-title="Imputation comptable " checked /> Imputation comptable <del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="rib" data-title="Compte banquaire enexpensement / déexpensement " checked /> Compte banquaire enexpensement / déexpensement <del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="datepaid" data-title="Date d'enexpensement / déexpensement (Rapprochement)" checked /> Date d'enexpensement / déexpensement (Rapprochement)<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="remis" data-title="Remis" checked /> Remis<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="paid" data-title="Impayé" checked /> Impayé<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="user" data-title="Utilisateur" checked /> Utilisateur<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="table" value="payments" />
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
										<p>Voulez vous vraiment supprimer cette dépence / recette?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deleteexpense" data-id="">Oui</a>
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
		<script src="js/ion.rangeSlider.min.js"></script>
		<!-- Main Script -->
		<script src="js/script.js"></script>
		<script>
			$(document).ready(function(){
				loadExpenses($(".lx-pagination ul").attr("data-state"));
				loadPriceRange('payments');
				toDropDown();
			});
			$(function() {
				$('#dateadd').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY',
					  "separator": " - ",
						"applyLabel": "Appliquer",
						"cancelLabel": "Annuler",
						"fromLabel": "De",
						"toLabel": "à",
						"customRangeLabel": "Custom",
						"daysOfWeek": [
							"Di",
							"Lu",
							"Ma",
							"Me",
							"Je",
							"Ve",
							"Sa"
						],
						"monthNames": [
							"Janvier",
							"Février",
							"Mars",
							"Avril",
							"Mai",
							"Juin",
							"Juillet",
							"Août",
							"Septembre",
							"Octobere",
							"Novembre",
							"Decembre"
						],
					},
					ranges: {
						'Aujourd\'hui': [moment(), moment()],
						'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Derniers 7 Jours': [moment().subtract(6, 'days'), moment()],
						'Derniers 30 Jours': [moment().subtract(29, 'days'), moment()],
						'Ce mois': [moment().startOf('month'), moment().endOf('month')],
						'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					"linkedCalendars": false,
					"autoUpdateInput": false,
					"showCustomRangeLabel": false,
					"alwaysShowCalendars": true
					}, function(start, end, label) {
						$('#dateadd').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('#datestart').val(start.format('DD/MM/YYYY'));
						$('#dateend').val(end.format('DD/MM/YYYY'));
						$("input[name='orderby']").val("ASC");
						loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="dateadd"]').on('apply.daterangepicker', function(ev, picker) {
					$('input[name="dateadd"]').val(picker.startDate.format('DD/MM/YYYY') + " - " + picker.endDate.format('DD/MM/YYYY'));
					$('input[name="datestart"]').val(picker.startDate.format('DD/MM/YYYY'));
					$('input[name="dateend"]').val(picker.endDate.format('DD/MM/YYYY'));
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('#dateadd').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('#datestart').val('');
					$('#dateend').val('');
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				// Add an event listener to the "Today" button
				$("body").delegate('.daterangepicker .ranges li:first-child','click', function() {
					// Get today's date
					var today = moment();

					// Set the start and end dates to today
					$('input[name="dateadd"]').data('daterangepicker').setStartDate(today);
					$('input[name="dateadd"]').data('daterangepicker').setEndDate(today);

					// Update the input field with the selected date range
					/*$('input[name="dateadd"]').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));
					$('input[name="datestart"]').val(today.format('DD/MM/YYYY'));
					$('input[name="dateend"]').val(today.format('DD/MM/YYYY'));*/

					// Trigger the "Apply" button to update the date range
					$('input[name="dateadd"]').data('daterangepicker').clickApply();
					$("input[name='orderby']").val("ASC");
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				
				$('#datedue').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY',
					  "separator": " - ",
						"applyLabel": "Appliquer",
						"cancelLabel": "Annuler",
						"fromLabel": "De",
						"toLabel": "à",
						"customRangeLabel": "Custom",
						"daysOfWeek": [
							"Di",
							"Lu",
							"Ma",
							"Me",
							"Je",
							"Ve",
							"Sa"
						],
						"monthNames": [
							"Janvier",
							"Février",
							"Mars",
							"Avril",
							"Mai",
							"Juin",
							"Juillet",
							"Août",
							"Septembre",
							"Octobere",
							"Novembre",
							"Decembre"
						],
					},
					ranges: {
						'Aujourd\'hui': [moment(), moment()],
						'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Derniers 7 Jours': [moment().subtract(6, 'days'), moment()],
						'Derniers 30 Jours': [moment().subtract(29, 'days'), moment()],
						'Ce mois': [moment().startOf('month'), moment().endOf('month')],
						'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					"linkedCalendars": false,
					"autoUpdateInput": false,
					"showCustomRangeLabel": false,
					"alwaysShowCalendars": true
					}, function(start, end, label) {
						$('#datedue').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('#dateduestart').val(start.format('DD/MM/YYYY'));
						$('#datedueend').val(end.format('DD/MM/YYYY'));
						$("input[name='orderby']").val("ASC");
						loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="datedue"]').on('apply.daterangepicker', function(ev, picker) {
					$('input[name="datedue"]').val(picker.startDate.format('DD/MM/YYYY') + " - " + picker.endDate.format('DD/MM/YYYY'));
					$('input[name="dateduestart"]').val(picker.startDate.format('DD/MM/YYYY'));
					$('input[name="datedueend"]').val(picker.endDate.format('DD/MM/YYYY'));
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('#datedue').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('#dateduestart').val('');
					$('#datedueend').val('');
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				
				$('#datepaid').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY',
					  "separator": " - ",
						"applyLabel": "Appliquer",
						"cancelLabel": "Annuler",
						"fromLabel": "De",
						"toLabel": "à",
						"customRangeLabel": "Custom",
						"daysOfWeek": [
							"Di",
							"Lu",
							"Ma",
							"Me",
							"Je",
							"Ve",
							"Sa"
						],
						"monthNames": [
							"Janvier",
							"Février",
							"Mars",
							"Avril",
							"Mai",
							"Juin",
							"Juillet",
							"Août",
							"Septembre",
							"Octobere",
							"Novembre",
							"Decembre"
						],
					},
					ranges: {
						'Aujourd\'hui': [moment(), moment()],
						'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Derniers 7 Jours': [moment().subtract(6, 'days'), moment()],
						'Derniers 30 Jours': [moment().subtract(29, 'days'), moment()],
						'Ce mois': [moment().startOf('month'), moment().endOf('month')],
						'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					"linkedCalendars": false,
					"autoUpdateInput": false,
					"showCustomRangeLabel": false,
					"alwaysShowCalendars": true
					}, function(start, end, label) {
						$('#datepaid').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('#datepaidstart').val(start.format('DD/MM/YYYY'));
						$('#datepaidend').val(end.format('DD/MM/YYYY'));
						$("input[name='orderby']").val("ASC");
						loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="datepaid"]').on('apply.daterangepicker', function(ev, picker) {
					$('input[name="datepaid"]').val(picker.startDate.format('DD/MM/YYYY') + " - " + picker.endDate.format('DD/MM/YYYY'));
					$('input[name="datepaidstart"]').val(picker.startDate.format('DD/MM/YYYY'));
					$('input[name="datepaidend"]').val(picker.endDate.format('DD/MM/YYYY'));
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('#datepaid').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('#datepaidstart').val('');
					$('#datepaidend').val('');
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});

				$('#dateremis').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY',
					  "separator": " - ",
						"applyLabel": "Appliquer",
						"cancelLabel": "Annuler",
						"fromLabel": "De",
						"toLabel": "à",
						"customRangeLabel": "Custom",
						"daysOfWeek": [
							"Di",
							"Lu",
							"Ma",
							"Me",
							"Je",
							"Ve",
							"Sa"
						],
						"monthNames": [
							"Janvier",
							"Février",
							"Mars",
							"Avril",
							"Mai",
							"Juin",
							"Juillet",
							"Août",
							"Septembre",
							"Octobere",
							"Novembre",
							"Decembre"
						],
					},
					ranges: {
						'Aujourd\'hui': [moment(), moment()],
						'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Derniers 7 Jours': [moment().subtract(6, 'days'), moment()],
						'Derniers 30 Jours': [moment().subtract(29, 'days'), moment()],
						'Ce mois': [moment().startOf('month'), moment().endOf('month')],
						'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					"linkedCalendars": false,
					"autoUpdateInput": false,
					"showCustomRangeLabel": false,
					"alwaysShowCalendars": true
					}, function(start, end, label) {
						$('#dateremis').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('#dateremisstart').val(start.format('DD/MM/YYYY'));
						$('#dateremisend').val(end.format('DD/MM/YYYY'));
						$("input[name='orderby']").val("ASC");
						loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="dateremis"]').on('apply.daterangepicker', function(ev, picker) {
					$('input[name="dateremis"]').val(picker.startDate.format('DD/MM/YYYY') + " - " + picker.endDate.format('DD/MM/YYYY'));
					$('input[name="dateremisstart"]').val(picker.startDate.format('DD/MM/YYYY'));
					$('input[name="dateremisend"]').val(picker.endDate.format('DD/MM/YYYY'));
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});
				$('#dateremis').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('#dateremisstart').val('');
					$('#dateremisend').val('');
					loadExpenses($(".lx-pagination ul").attr("data-state"));
				});

				$('input[name="dateremiscommand"]').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY'
					},
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false
				}).on('apply.daterangepicker', function(ev, picker) {
					$(this).val(picker.startDate.format('DD/MM/YYYY'));
				});		
				$('input[name="dateduecommand"]').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY'
					},
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false
				}).on('apply.daterangepicker', function(ev, picker) {
					$(this).val(picker.startDate.format('DD/MM/YYYY'));
				});
				$('input[name="datepaidcommand"]').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY'
					},
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false
				}).on('apply.daterangepicker', function(ev, picker) {
					$(this).val(picker.startDate.format('DD/MM/YYYY'));
				});
				$('input[name="dateaddcommand"]').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY'
					},
					singleDatePicker: true,
					showDropdowns: true
				});
			});
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>
