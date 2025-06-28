<?php
session_start();
include("config.php");

$_SESSION['easybm_errorimport'] = "";

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Tableau de bord#",$_SESSION['easybm_roles'])){	
		if(preg_match("#Consulter Historique des paiements#",$_SESSION['easybm_roles'])){	
			$page = "payments.php";
		}
		elseif(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){	
			$page = "factures.php";
		}
		elseif(preg_match("#Consulter Devis#",$_SESSION['easybm_roles'])){	
			$page = "devis.php";
		}
		elseif(preg_match("#Consulter Factures proforma#",$_SESSION['easybm_roles'])){	
			$page = "facturesproforma.php";
		}
		elseif(preg_match("#Consulter Bons de livraison#",$_SESSION['easybm_roles'])){	
			$page = "bl.php";
		}
		elseif(preg_match("#Consulter Bons de sortie#",$_SESSION['easybm_roles'])){	
			$page = "bs.php";
		}
		elseif(preg_match("#Consulter Bons de retour#",$_SESSION['easybm_roles'])){	
			$page = "br.php";
		}
		elseif(preg_match("#Consulter Factures avoir#",$_SESSION['easybm_roles'])){	
			$page = "avoirs.php";
		}
		elseif(preg_match("#Consulter Clients#",$_SESSION['easybm_roles'])){	
			$page = "clients.php";
		}
		elseif(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){	
			$page = "bc.php";
		}
		elseif(preg_match("#Consulter Bons de récéption#",$_SESSION['easybm_roles'])){	
			$page = "bre.php";
		}
		elseif(preg_match("#Consulter Fournisseurs#",$_SESSION['easybm_roles'])){	
			$page = "suppliers.php";
		}
		elseif(preg_match("#Consulter Utilisateurs#",$_SESSION['easybm_roles'])){	
			$page = "users.php";
		}
		else{
			$page = "login.php";
		}
		header('location: '.$page);
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Tableau de bord</title>
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
        <!-- Modern Dashboard -->
        <link rel="stylesheet" href="css/modern_dashboard.css">
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
						<h2><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>
					</div>
					<div class="lx-g1">

					</div>
					<div class="lx-clear-fix"></div>
					<div class="lx-page-content">
						<div class="lx-g1-f">
							<div class="lx-keyword lx-g1">
								<h3><i class="fas fa-filter"></i> Filtre global: </h3><br />
								<?php
								$styleday = "";
								$rangedate = gmdate("d/m/Y",time()-(60*60*24*29))." - ".gmdate("d/m/Y");
								$rangedateplaceholder = gmdate("d/m/Y",time()-(60*60*24*29))." - ".gmdate("d/m/Y");
								$startdate = gmdate("d/m/Y",time()-(60*60*24*29));
								$enddate = gmdate("d/m/Y");
								if(preg_match("#Consultation de la journée en cours seulement Tableau de bord#",$_SESSION['easybm_roles'])){
									$styleday = "display:none;";
									$rangedate = gmdate("d/m/Y")." - ".gmdate("d/m/Y");
									$rangedateplaceholder = "Date création";
									$startdate = gmdate("d/m/Y");
									$enddate = gmdate("d/m/Y");	
								}
								?>
								<label style="<?php echo $styleday;?>" class="lx-advanced-select">
									<span><i class="fas fa-calendar-alt"></i> Date création:</span>
									<input type="text" autocomplete="off" name="dateadd" id="dateadd" title="Date création" value="<?php echo $rangedate;?>" placeholder="<?php echo $rangedateplaceholder;?>" readonly style="background:white;cursor:pointer;" /></label>
								<input type="hidden" name="datestart" id="datestart" value="<?php echo $startdate;?>" />
								<input type="hidden" name="dateend" id="dateend" value="<?php echo $enddate;?>" />
								<label class="lx-advanced-select">
									<span><i class="fas fa-building"></i> Sociétés</span>
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
								<label class="lx-advanced-select" style="<?php echo preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])?"":"display:none;";?>">
									<span><i class="fas fa-user-friends"></i> Clients:</span>
									<i class="fa fa-caret-down"></i>
									<input type="text" autocomplete="off" name="client" id="client" placeholder="Choisissez un client" data-ids="" title="Client" readonly />
									<div>
										<a href="javascript:;" class="lx-state-empty">Vider</a>
										<a href="javascript:;" class="lx-state-filter">Filtrer</a>
										<div class="lx-clear-fix"></div>
										<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
										<ul>
											<?php
											$back = $bdd->query("SELECT id,code,fullname FROM clients WHERE fullname<>''".$multicompanies." ORDER BY fullname");
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
									<span><i class="fas fa-truck"></i> Fournisseurs:</span>
									<i class="fa fa-caret-down"></i>
									<input type="text" autocomplete="off" name="supplier" id="supplier" placeholder="Choisissez un fournisseur" data-ids="" title="Fournisseur" readonly />
									<div>
										<a href="javascript:;" class="lx-state-empty">Vider</a>
										<a href="javascript:;" class="lx-state-filter">Filtrer</a>
										<div class="lx-clear-fix"></div>
										<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
										<ul>
											<?php
											$back = $bdd->query("SELECT id,code,title FROM suppliers WHERE title<>''".$multicompanies." ORDER BY title");
											while($row = $back->fetch()){
												?>
											<li><label><input type="checkbox" value="<?php echo $row['title'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['title']." (".$row['code'].")";?><del class="checkmark"></del></label></li>
												<?php
											}
											?>
										</ul>
									</div>
								</label>
								<label>
									<a href="index.php" class="lx-refresh-filter"><i class="fa fa-sync-alt"></i></a>
								</label>								
							</div>
						</div>
					</div>
					<div class="lx-page-content">
						<div class="lx-g1-f">
							<div class="lx-g1 lx-pb-0">
								<h3><i class="fas fa-wallet"></i> KPIs paiements</h3>
							</div>
							<div id="kpi">
								
							</div>
						</div>
					</div>
					<div class="lx-page-content">
						<div class="lx-g1-f">
							<div class="lx-g1 lx-pb-0">
								<h3><i class="fas fa-file-alt"></i> KPIs documents</h3>
							</div>
							<div id="documents">
								
							</div>
						</div>
					</div>
					<div class="lx-page-content">
						<div class="lx-g1-f">
							<div class="lx-keyword lx-g1">
								<h3><i class="fas fa-filter"></i> Filtre avancé: </h3><br />
								<label class="lx-advanced-select">
									<span><i class="fas fa-file-invoice"></i> Type de documents:</span>
									<i class="fa fa-caret-down"></i>
									<input type="text" autocomplete="off" name="typedoc" id="typedoc" placeholder="Type des documents" data-ids="" title="Type des documents" readonly />
									<div>
										<a href="javascript:;" class="lx-state-empty">Vider</a>
										<a href="javascript:;" class="lx-state-filter">Filtrer</a>
										<div class="lx-clear-fix"></div>
										<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
										<ul>
                                            <?php if(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Factures" data-ids="facture" /> <i class="fas fa-file-invoice-dollar"></i> Factures<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Devis#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Devis" data-ids="devis" /> <i class="fas fa-file-alt"></i> Devis<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Factures avoir#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Factures avoir" data-ids="avoir" /> <i class="fas fa-file-download"></i> Factures avoir<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Bons de retour#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Bons de retour" data-ids="br" /> <i class="fas fa-undo"></i> Bons de retour<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Factures proforma#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Factures proforma" data-ids="factureproforma" /> <i class="fas fa-file-alt"></i> Factures proforma<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Bons de livraison#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Bons de livraison" data-ids="bl" /> <i class="fas fa-truck"></i> Bons de livraison<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Bons de sortie#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Bons de sortie" data-ids="bs" /> <i class="fas fa-sign-out-alt"></i> Bons de sortie<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){ ?>
                                            <li><label><input type="checkbox" value="Bons de commande" data-ids="bc" /> <i class="fas fa-shopping-cart"></i> Bons de commande<del class="checkmark"></del></label></li>
                                            <?php } ?>
                                            <?php if(preg_match("#Consulter Bons de réception#",$_SESSION['easybm_roles'])){ ?>											
                                            <li><label><input type="checkbox" value="Bons de réception" data-ids="bre" /> <i class="fas fa-receipt"></i> Bons de réception<del class="checkmark"></del></label></li>
                                            <?php } ?>
										</ul>
									</div>
								</label>
								<label class="lx-advanced-select">
									<span><i class="fas fa-box"></i> Produits / Services:</span>
									<i class="fa fa-caret-down"></i>
									<input type="text" autocomplete="off" name="product" id="product" placeholder="Produits / Services" data-ids="" title="Produits / Services" readonly />
									<div>
										<a href="javascript:;" class="lx-state-empty">Vider</a>
										<a href="javascript:;" class="lx-state-filter">Filtrer</a>
										<div class="lx-clear-fix"></div>
										<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
										<ul>
											<?php
											$back = $bdd->query("SELECT DISTINCT title FROM detailsdocuments WHERE trash='1'".$multicompanies." ORDER BY title");
											while($row = $back->fetch()){
												?>
											<li><label><input type="checkbox" value="<?php echo $row['title'];?>" data-ids="<?php echo $row['title'];?>" /> <?php echo $row['title'];?><del class="checkmark"></del></label></li>
												<?php
											}
											?>
										</ul>
									</div>
								</label>
							</div>
							<div class="lx-g1" style="padding-top:30px;border-top:1px dashed #BEBEBE;">
								<h3><i class="fas fa-chart-line"></i> Evolution nombre, valeur des documents</h3>
							</div>
							<div class="lx-table lx-g1 lx-table-ca">
								<ul>
									<li><span>Par: </span></li>
									<li><a href="javascript:;" class="active" data-period="<?php echo 60*60*24;?>">Jour</a></li>
									<li><a href="javascript:;" data-period="<?php echo 60*60*24*7;?>">Semaine</a></li>
									<li><a href="javascript:;" data-period="<?php echo 60*60*24*30;?>">Mois</a></li>
									<li><a href="javascript:;" data-period="<?php echo 60*60*24*365;?>">Année</a></li>
									<input type="hidden" name="period" id="period" value="<?php echo 60*60*24;?>" />
								</ul>
								<div class="lx-clear-fix"></div>
								<div class="lx-g1 lx-pl-0">
									<div id="ca"></div>
								</div>
							</div>
						</div>
						<div class="lx-g1-f">
							<div class="lx-keyword lx-g1">
								<div class="lx-g1 lx-pl-0">
									<h3><i class="fas fa-trophy"></i> Top 10 &nbsp;
										<label>
											<select name="topwho" id="topwho">
												<?php
												if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
												?>
												<option value="clients">Clients</option>
												<?php
												}
												?>
												<option value="products">Produits et Services</option>
												<?php
												if(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
												?>
												<option value="suppliers">Fournisseurs</option>
												<?php
												}
												?>
											</select>
										</label> 
										par &nbsp;<label>
											<select name="topwhat" id="topwhat">
												<option value="nbdocs">Nb. documents</option>
												<option value="value">Valeurs</option>
											</select>
										</label>
									</h3>
									<div class="lx-clear-fix"></div>
									<div id="topca"></div>
								</div>
								<div class="lx-clear-fix"></div>
							</div>
						</div>
					</div>
					<div class="lx-clear-fix"></div>
				</div>
				<div class="lx-clear-fix"></div>
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
				loadKPI();
				loadKPI1();
				loadCA();
				loadTop();
			});
			$(function() {
				$('input[name="dateadd"]').daterangepicker({
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
					startDate: moment().startOf('day').subtract(30,'day'),
					endDate: moment(),
					ranges: {
						'Aujourd'hui': [moment(), moment()],
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
						$('input[name="dateadd"]').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('input[name="datestart"]').val(start.format('DD/MM/YYYY'));
						$('input[name="dateend"]').val(end.format('DD/MM/YYYY'));
						loadKPI();
						loadKPI1();
						loadCA();
						loadTop();
				});
				$('input[name="dateadd"]').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('input[name="datestart"]').val('');
					$('input[name="dateend"]').val('');
					loadKPI();
					loadKPI1();
					loadCA();
					loadTop();
				});
				// Add an event listener to the "Today" button
				$("body").delegate('.daterangepicker .ranges li:first-child','click', function() {
					// Get today's date
					var today = moment();

					// Set the start and end dates to today
					$('input[name="dateadd"]').data('daterangepicker').setStartDate(today);
					$('input[name="dateadd"]').data('daterangepicker').setEndDate(today);

					// Update the input field with the selected date range
					$('input[name="dateadd"]').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));

					// Trigger the "Apply" button to update the date range
					$('input[name="dateadd"]').data('daterangepicker').clickApply();
					loadKPI();
					loadKPI1();
					loadCA();
					loadTop();
				});
			});
		</script>
		<script src="js/highcharts.js"></script>
		<script src="js/exporting.js"></script>		
		<script src="js/export-data.js"></script>		
		<script src="js/annotations.js"></script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>