<?php
session_start();
include("config.php");

$_SESSION['easybm_errorimport'] = "";

if(!isset($_SESSION['easybm_id'])){
	header('location: login.php');
}
else{
	if(!preg_match("#Consulter Bons de réception#",$_SESSION['easybm_roles'])){	
		header('location: 404.php');
	}
}

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<title>Bons de réception</title>
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
						<h2>Bons de réception</h2>
					</div>
					<div class="lx-clear-fix"></div>
					<div class="lx-tabs">
						<?php
						if(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){	
							?>
						<a href="bc.php">Bons de commande</a>
							<?php
						}
						if(preg_match("#Consulter Bons de réception#",$_SESSION['easybm_roles'])){	
							?>
						<a href="bre.php" class="active">Bons de réception</a>
							<?php
						}
						?>
					</div>
					<div class="lx-page-content">
						<div class="lx-g1">
							<div class="lx-add-form">
								<?php
								if(preg_match("#Ajouter Bons de réception#",$_SESSION['easybm_roles'])){	
									?>							
								<a href="javascript:;" class="lx-new lx-new-command lx-open-popup" data-header="Ajouter un nouveau" data-table="bre" data-title="command">+ Nouveau bon de réception</a>
									<?php
								}					
								?>						
							</div>
							<div class="lx-keyword">
								<fieldset style="margin-bottom:20px;padding:10px;background:#F8F8F8;">
									<legend><b>Filtre avancé:</b></legend>
									<label><a href="javascript:;" class="lx-search-keyword"><i class="fa fa-search"></i></a><input type="text" autocomplete="off" name="keyword" id="keyword" placeholder="Réf bon de réception" data-table="bre" /></label>
									<?php
									$styleday = "";
									$rangedate = "";
									$rangedateplaceholder = "Date création";
									$startdate = "";
									$enddate = "";
									if(preg_match("#Consultation de la journée en cours seulement Bons de réception#",$_SESSION['easybm_roles'])){
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
									?>
									<label style="<?php echo $styleday;?>"><input type="text" autocomplete="off" name="dateadd" id="dateadd" title="Date création" value="<?php echo $rangedate;?>" placeholder="<?php echo $rangedateplaceholder;?>" readonly style="background:white;cursor:pointer;" /></label>
									<input type="hidden" name="datestart" id="datestart" value="<?php echo $startdate;?>" />
									<input type="hidden" name="dateend" id="dateend" value="<?php echo $enddate;?>" />
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
									<label class="lx-advanced-select">
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
									<label class="lx-advanced-select">
										<i class="fa fa-caret-down"></i>
										<input type="text" autocomplete="off" name="user" id="user" placeholder="Choisissez un utilisateur" data-ids="" title="Utilisateur" readonly />
										<div>
											<a href="javascript:;" class="lx-state-empty">Vider</a>
											<a href="javascript:;" class="lx-state-filter">Filtrer</a>
											<div class="lx-clear-fix"></div>
											<input type="text" autocomplete="off" name="searchadvanced" style="margin-bottom:20px;" />
											<ul>
												<?php
												$back = $bdd->query("SELECT id,fullname FROM users WHERE trash='1' ORDER BY fullname");
												while($row = $back->fetch()){
													?>
												<li><label><input type="checkbox" value="<?php echo $row['fullname'];?>" data-ids="<?php echo $row['id'];?>" /> <?php echo $row['fullname'];?><del class="checkmark"></del></label></li>
													<?php
												}
												?>
											</ul>
										</div>
									</label>
									<input type="hidden" name="sortby" value="" />
									<input type="hidden" name="orderby" value="DESC" />
									<br />
									<fieldset style="display:inline-block;padding:0px 10px;">
										<legend>Prix TTC</legend>
										<label style="margin-right:0px;margin-bottom:5px;" class="lx-price-range">
											<input type="text" class="js-range-slider" name="my_range" value="" />
											<input type="hidden" id="pricemin" />
											<input type="hidden" id="pricemax" />
											<a href="javascript:;" onclick="loadCommands('1')" class="lx-price-filter">Appliquer</a>
										</label>
									</fieldset>
									<label>
										<a href="bre.php" class="lx-refresh-filter" style="margin-left:10px;"><i class="fa fa-sync-alt"></i></a>
									</label>
								</fieldset>
							</div>
							<div class="lx-table-container">
								<div class="lx-caisse-total lx-caisse-total-1"></div>
								<div class="lx-caisse-total lx-caisse-total-2"></div>
								<div class="lx-caisse-total lx-caisse-total-4"></div>
								<div class="lx-table-shadow lx-table-shadow-left"></div>
								<div class="lx-table-shadow lx-table-shadow-right"></div>
								<div class="lx-table lx-table-bre">

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
								if(preg_match("#Exporter Bons de réception#",$_SESSION['easybm_roles'])){
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
							$back = $bdd->query("SELECT COUNT(*) AS nb FROM documents WHERE category='client' AND type='facture' AND trash='1'");
							$row = $back->fetch();
							?>
							<div class="lx-pagination" style="<?php if($row['nb'] <= $nb){echo "display:none;";}?>">
								<?php
								$nbpages = ceil($row['nb']/$nb);
								?>
								<ul data-table="bre" data-state="1" data-start="0" data-nbpage="<?php echo $nb;?>" data-posts="<?php echo $row['nb'];?>">
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
			<div class="lx-select-avoir" style="display:none;">
				<option value="avoir" data-prefix="AV">Facture avoir</option>
				<?php
				if(preg_match("#Ajouter Bons de retour#",$_SESSION['easybm_roles'])){
				?>
				<option value="br" data-prefix="BR">Bon de retour</option>
				<?php
				}
				?>		
			</div>
			<div class="lx-select-facture" style="display:none;">
				<option value="bre" data-prefix="BRC">Bon de réception</option>
				<?php
				if(preg_match("#Ajouter Bons de commande#",$_SESSION['easybm_roles'])){
				?>				
				<option value="bc" data-prefix="BC">Bon de commande</option>
				<?php
				}
				?>
			</div>
			<div tabindex="0" class="lx-popup command">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons lx-direct-click"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3><span></span> bon de commande</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="commandsform">
										<div class="lx-textfield lx-g1 lx-pb-0 lx-transform">
											<label><span>Duppliquer ou transformer vers:</span>
												<select name="transform">
													<option value="bre" data-prefix="BRC">Bon de réception</option>
													<option value="bc" data-prefix="BC">Bon de commande</option>	
												</select>
											</label>
										</div>										
										<div class="lx-clear-fix"></div>
										<div class="lx-transform" style="margin:20px;border-top:1px dashed #BEBEBE;"></div>
										<div class="lx-create-avoir">
											<div class="lx-textfield lx-g3 lx-pb-0 datereported" style="<?php echo !preg_match("#Modification date opération#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
												<label><span>Date: </span><input type="text" name="dateaddcommand" /></label>
											</div>
											<div class="lx-textfield lx-g<?php echo !preg_match("#Modification date opération#",$_SESSION['easybm_roles'])?"2":"3";?> lx-pb-0">
												<label><span>Société<sup>*</sup>:</span>
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
											<div class="lx-textfield lx-g<?php echo !preg_match("#Modification date opération#",$_SESSION['easybm_roles'])?"2":"3";?>">
												<label><span>Numéro<sup>*</sup>:</span>
													<strong class="lx-code-label">FA<?php echo date("ym");?>-</strong>
													<input type="text" autocomplete="off" name="code" data-isnumber="" data-message="Saisissez un numéro!!" />
													<i class="fa fa-sync-alt lx-refresh-codes"></i>
												</label>
											</div>											
											<div class="lx-clear-fix"></div>
											<div class="lx-textfield lx-g1 lx-pb-0" style="display:none;">
												<label><span style="font-weight:500;color:#FFA500;">Corrige la facture N°<sup>*</sup>:</span>
													<select name="correctdoc" id="correctdoc" class="todropdown" data-isnotempty="" data-message="Saisissez ou choisissez un numéro facture!!">
														<option value="">Saisissez ou choisissez un numéro facture</option>
														<?php
														$back = $bdd->query("SELECT code FROM documents WHERE trash='1' AND type='facture'".$companiesid." ORDER BY code");
														while($row = $back->fetch()){
															?>
														<option value="<?php echo $row['code'];?>"><?php echo $row['code'];?></option>
															<?php 
														}
														?>
													</select>
												</label>
											</div>
											<div class="lx-clear-fix"></div>
											<div style="margin:20px;border-top:1px dashed #BEBEBE;"></div>
											<div class="lx-clear-fix"></div>
											<div class="lx-textfield lx-g3-2 lx-pb-0">
												<label><span>Produits / services<sup>*</sup>:</span>
													<select name="product" id="product" class="todropdown" data-isnotempty="" data-message="Saisissez ou choisissez un produit / service de la liste!!">
														
													</select>
												</label>
											</div>	
											<div class="lx-textfield lx-g3 lx-pb-0">
												<label><span>Unité<sup>*</sup>:</span>
													<select name="unit" id="unit" class="todropdown" data-isnotempty="" data-message="Saisissez une unité!!">
														
													</select>
												</label>
											</div>
											<div class="lx-clear-fix"></div>
											<div class="lx-textfield lx-g3 lx-pb-0">
												<label><span>Quantité<i class="lx-unit-product"></i><sup>*</sup>: </span><input type="text" autocomplete="off" name="qty" value="1" data-isnumber="" data-message="Saisissez une quantité!!" /></label>
											</div>
											<div class="lx-textfield lx-g3 lx-pb-0">
												<label><span>P.U. de vente<sup>*</sup>: </span>
													<input type="text" autocomplete="off" name="uprice" data-isnumber="" data-message="Saisissez un prix!!" />
													<select name="pricebase">
														<option value="HT">HT</option>
														<option value="TTC">TTC</option>
													</select>
												</label>
											</div>
											<div class="lx-textfield lx-g3 lx-pb-0">
												<label><span>TVA<sup>*</sup>: </span>
													<select name="utva" data-isnotempty="" data-message="Choisissez une tva de la liste!!">
														<option value="">Choisissez une TVA</option>
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
											<div class="lx-textfield lx-g6-5 lx-pb-0"></div>
											<div class="lx-textfield lx-g6 lx-pb-0">
												<a href="javascript:;" class="lx-add-product-to-order" style="display:block;text-align:center;">Ajouter</a>
											</div>	
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0"></div>	
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Appliquer une remise:</span>
												<select name="typediscount" data-isnotempty="" data-message="Choisissez un type!!">
													<option value="">Choisissez un type</option>
													<option value="%">%</option>
													<option value="<?php echo $settings['currency'];?>"><?php echo $settings['currency'];?></option>
												</select>
											</label>
										</div>									
										<div class="lx-clear-fix"></div>
										<div class="lx-list-products lx-table" style="width:auto;">
											<table>
												<tr class="lx-firstrow">
													<td style="width:auto;">Designation</td>
													<td>Qté</td>
													<td>P.U. HT</td>
													<td class="lx-discount">Remise HT</td>
													<td>TVA</td>
													<td>Total HT</td>
													<td class="lx-qty-back">Qté à retourner</td>
													<td class="lx-edit-cell"><i class="fa fa-edit"></i></td>
													<td class="lx-delete-cell"><i class="fa fa-trash-alt"></i></td>
												</tr>
												<tr class="lx-secondrow">
													<td colspan="7">
														<center><i>0 produits / services</i></center>
													</td>
												</tr>
											</table>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1">	
											<div class="lx-error-products"></div>
										</div>	
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g3 lx-pb-0"></div>		
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label class="maindiscount"><span>Remise globale: </span><input type="number" min="0" name="maindiscount" /><u class="discounttype"></u></label>
										</div>		
										<div class="lx-textfield lx-g3 lx-pb-0 invoicedyes">
											<label><span>Montant Total HT: </span><input type="text" autocomplete="off" name="price" value="0" style="text-align:right;" readonly /></label>
										</div>		
										<div class="lx-clear-fix invoicedyes"></div>
										<div class="lx-textfield lx-g3-2 lx-pb-0 invoicedyes"></div>		
										<div class="lx-textfield lx-g3 lx-pb-0 invoicedyes">
											<label><span>Total TVA: </span><input type="text" autocomplete="off" name="tva" value="0" style="text-align:right;" readonly /></label>
										</div>
										<div class="lx-clear-fix invoicedyes"></div>
										<div class="lx-textfield lx-g3-2 lx-pb-0 invoicedyes"></div>		
										<div class="lx-textfield lx-g3 lx-pb-0">
											<label><span>Montant total TTC: </span><input type="text" autocomplete="off" name="ttcprice" value="0" style="text-align:right;" readonly /></label>
										</div>	
										<div class="lx-clear-fix"></div>
										<div class="lx-create-avoir">
											<div class="lx-g1">
												<fieldset>
													<legend>Informations fournisseur<sup>*</sup>:</legend>
													<div class="lx-textfield lx-g1 lx-pb-0">
														<label style="display:inline-block;"><input type="radio" name="new" value="0" style="position:relative;top:2px;transform:scale(1.3);margin-right:10px;" /> Nouveau fournisseur<u class="circlemark"></u></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
														<label style="display:inline-block;"><input type="radio" name="new" value="1" checked style="position:relative;top:2px;transform:scale(1.3);margin-right:10px;" /> Fournisseur existant<u class="circlemark"></u></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
														<div class="lx-clear-fix" style="margin-top:5px;margin-bottom:20px;"></div>
														<label><input type="hidden" name="exist" value="" data-isnotempty="" data-message="Choisissez une option" /></label>
													</div>												
													<div class="lx-clear-fix"></div>							
													<div class="lx-textfield lx-g2 lx-pb-0">
														<label><span>Fournisseur<sup>*</sup>: </span>
															<select name="client" data-isnotempty="" data-message="Choisissez un fournisseur !!" class="todropdown">
																<option value="">Choisissez un fournisseur</option>
																<?php
																$back = $bdd->query("SELECT * FROM suppliers WHERE title<>''".$multicompanies." ORDER BY title");
																while($row = $back->fetch()){
																	?>
																<option value="<?php echo $row['id'];?>"
																	data-id="<?php echo $row['id'];?>"
																	data-ice="<?php echo $row['respname'];?>"
																	data-name="<?php echo $row['title'];?>"
																	data-phone="<?php echo $row['respphone'];?>"
																	data-address="<?php echo $row['address'];?>"
																	data-email="<?php echo $row['respemail'];?>"><?php echo $row['title']." (".$row['code'].")";?></option>
																	<?php 
																}
																?>
															</select>											
														</label>
													</div>
													<div class="lx-textfield lx-g2 lx-pb-0">
														<label><span>Responsable<sup>*</sup>: </span><input type="text" autocomplete="off" name="ice" data-isnotempty="" data-message="Saisissez un ICE !!" /></label>
													</div>
													<div class="lx-clear-fix"></div>							
													<div class="lx-textfield lx-g2 lx-pb-0">
														<label><span>Téléphone: </span><input type="text" autocomplete="off" name="phone" /></label>
													</div>
													<div class="lx-textfield lx-g2 lx-pb-0">
														<label><span>Adresse<sup>*</sup>: </span><input type="text" autocomplete="off" name="address" data-isnotempty="" data-message="Saisissez une adresse !!" /></label>
													</div>
													<div class="lx-clear-fix"></div>
													<div class="lx-textfield lx-g1">
														<label><span>Email: </span><input type="text" autocomplete="off" name="email" /></label>
													</div>
												</fieldset>
											</div>
											<div class="lx-clear-fix lx-cash" style="display:none;"></div>
											<div class="lx-cash" style="display:none;margin:15px;border-top:1px dashed #BEBEBE;"></div>
											<div class="lx-clear-fix"></div>
											<div class="lx-textfield lx-g2 lx-pb-0 lx-cash">
												<label><span>Montant payé<sup>*</sup>: </span><input type="text" autocomplete="off" name="cash" data-isnumber="" data-message="Saisissez un montant!!" /></label>
											</div>
											<div class="lx-textfield lx-g2 lx-pb-0 lx-cash">
												<label><span>Reste: </span><input type="text" autocomplete="off" name="rest" style="text-align:right;" readonly /></label>
											</div>
											<div class="lx-clear-fix"></div>
											<div class="lx-textfield lx-g1 lx-pb-0 lx-cash">
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
											<div class="lx-textfield lx-g2 lx-pb-0 lx-cash">
												<label><span>Compte banquaire:</span>
													<select name="rib" class="lx-bankaccounts-list">
														<option value="0">Choisissez un compte banquaire</option>
													</select>
												</label>
											</div>
											<div class="lx-textfield lx-g2 lx-pb-0 lx-cash">
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
												<label><span>Note: </span><textarea name="note" id="note" /></textarea></label>
											</div>
										</div>
										<div class="lx-clear-fix"></div>							
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="modepayment" value="" />
											<input type="hidden" name="conditions" value="" />
											<input type="hidden" name="abovetable" value="" />
											<input type="hidden" name="category" value="supplier" />
											<input type="hidden" name="type" value="bre" />
											<input type="hidden" name="prefix" value="BRC" />
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
			<div tabindex="0" class="lx-popup extrainfo">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Informations supplimentaires sur le document</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="extrainfoform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Note (au-dessus du tableau): </span><textarea name="abovetable" id="abovetable" /></textarea></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Mode de paiement (au-dessous du tableau): </span><textarea name="modepayment" id="modepayment" /></textarea></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Conditions (au-dessous du tableau): </span><textarea name="conditions" id="conditions" /></textarea></label>
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
			<div tabindex="0" class="lx-popup payment">
				<div class="lx-popup-inside">
					<div class="lx-popup-content">
						<a href="javascript:;"><i class="material-icons"><img src="images/close.svg" /></i></a>
						<div class="lx-popup-details">
							<div class="lx-form">
								<div class="lx-form-title">
									<h3>Paiement facture</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="paymentform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<h3 style="margin-bottom:0px;text-align:center;text-decoration:underline;">Historique des paiements</h3>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<div class="lx-table">
											</div>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<a href="javascript:;" class="lx-payment-state" data-paid="Non payée" style="display:none;font-weight:500;background:#CC0000;color:#FFFFFF;">Non payée</a>
											<a href="javascript:;" class="lx-payment-state" data-paid="Partiellement payée" style="font-weight:500;background:orange;color:#FFFFFF;">Partiellement payée</a>
											<a href="javascript:;" class="lx-payment-state" data-paid="Payée" style="font-weight:500;background:#71b44c;color:#FFFFFF;">Payée</a>
											<input type="hidden" name="paid" data-required="yes" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
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
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Montant payé<sup>*</sup>: </span><input type="text" autocomplete="off" name="cash" data-isnumber="" data-message="Saisissez un montant!!" /></label>
										</div>
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Reste: </span><input type="text" autocomplete="off" name="rest" style="text-align:right;" readonly /></label>
											<input type="hidden" name="resthidden" /></label>
											<input type="hidden" name="price" /></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g2 lx-pb-0">
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
										<div class="lx-textfield lx-g2 lx-pb-0">
											<label><span>Compte banquaire (<b class="lx-payment-company"></b>):</span>
												<select name="rib">
													
												</select>
											</label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><span>Note: </span><textarea name="note1" id="note1" /></textarea></label>
										</div>
										<div class="lx-clear-fix"></div>							
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="type" value="bre" />
											<input type="hidden" name="category" value="supplier" />
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
									<h3>Export bons de réception</h3>
								</div>
								<div class="lx-add-form">
									<form autocomplete="off" action="#" method="post" id="exportform">
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="dateadd" data-title="Date" checked /> Date<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="company" data-title="Société" checked /> Société<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="code" data-title="Réf" checked /> Réf<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="supplier" data-title="Fournisseur" checked /> Fournisseur<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="products" data-title="Détails bon de commande" checked /> Détails bon de commande<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="price1" data-title="Montant TTC sans remise" checked /> Montant TTC sans remise<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="discount" data-title="Remise HT" checked /> Remise HT<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="price2" data-title="Montant TTC avec remise" checked /> Montant TTC avec remise<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="rest" data-title="Reste à payer TCC" checked /> Reste à payer TTC<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="note" data-title="Note" checked /> Note<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="modepayment" data-title="Mode de paiement" checked /> Mode de paiement<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="conditions" data-title="Conditions de paiement" checked /> Conditions de paiement<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-textfield lx-g1 lx-pb-0">
											<label><input type="checkbox" name="columns" value="abovetable" data-title="Message ou-dessus de la facture" checked /> Message ou-dessus de la facture<del class="checkmark"></del></label>
										</div>
										<div class="lx-clear-fix"></div>
										<div class="lx-submit lx-g1 lx-pb-0">
											<input type="hidden" name="table" value="documents" />
											<input type="hidden" name="type" value="bre" />
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
										<p>Voulez vous vraiment supprimer ce bon de commande?</p>
										<a href="javascript:;" class="lx-delete-record" data-action="deletedocument" data-id="">Oui</a>
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
				loadBRC($(".lx-pagination ul").attr("data-state"));
				loadPriceRange("bre");
				toDropDown();
				if($("#commandsform input[name='prefix']").val() === "BRC"){
					$("#commandsform input[name='code']").css("padding-left","77px");
				}
				else{
					$("#commandsform input[name='code']").css("padding-left","67px");
				}
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
						$('input[name="dateadd"]').val(start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY'));
						$('input[name="datestart"]').val(start.format('DD/MM/YYYY'));
						$('input[name="dateend"]').val(end.format('DD/MM/YYYY'));
						loadBRC($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="dateadd"]').on('apply.daterangepicker', function(ev, picker) {
					$('input[name="dateadd"]').val(picker.startDate.format('DD/MM/YYYY') + " - " + picker.endDate.format('DD/MM/YYYY'));
					$('input[name="datestart"]').val(picker.startDate.format('DD/MM/YYYY'));
					$('input[name="dateend"]').val(picker.endDate.format('DD/MM/YYYY'));
					loadBRC($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="dateadd"]').on('cancel.daterangepicker', function(ev, picker) {
					$(this).val('');
					$('input[name="datestart"]').val('');
					$('input[name="dateend"]').val('');
					loadBRC($(".lx-pagination ul").attr("data-state"));
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
					$('input[name="datestart"]').val(today.format('DD/MM/YYYY'));
					$('input[name="dateend"]').val(today.format('DD/MM/YYYY'));

					// Trigger the "Apply" button to update the date range
					$('input[name="dateadd"]').data('daterangepicker').clickApply();
					loadBRC($(".lx-pagination ul").attr("data-state"));
				});
				$('input[name="dateaddcommand"]').daterangepicker({
					locale: {
					  format: 'DD/MM/YYYY'
					},
					singleDatePicker: true,
					showDropdowns: true
				});
				$('input[name="dateaddcommand"]').on('apply.daterangepicker', function(ev, picker) {
					$(".lx-code-label").text($("#commandsform input[name='prefix']").val()+picker.startDate.format('YYMM')+"-");
				});
			});
			$('input[name="dateaddcommand"]').on("keyup",function(){
				$(".lx-code-label").text($("#commandsform input[name='prefix']").val()+convertDateFormat($(this).val())+"-");
			});
			function convertDateFormat(inputDate) {
				// Split the input date string into day, month, and year
				var parts = inputDate.split('/');
				var day = parts[0];
				var month = parts[1];
				var year = parts[2];

				// Extract the last two digits of the year and concatenate with the month
				var formattedDate = year.slice(-2) + month;

				return formattedDate;
			}
		</script>
	</body>
</html>
<?php
DB_Sanitize();
}
?>