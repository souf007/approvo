<div class="lx-main-menu">
	<a href="javascript:;" class="lx-mobile-menu-hide"><i class="material-icons"><img src="images/close.svg" /></i></a>
	<div class="lx-logo">
		<a href="javascript:;"><img src="<?php echo $settings['logo']=="logo.png"?"images/".$settings['logo']:"uploads/".$settings['logo'];?>" /></a>
	</div>
	<div class="lx-main-menu-scroll">
		<ul>
			<li style="<?php echo !preg_match("#Consulter Tableau de bord#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "index.php"){echo 'active';}?>"><i class="fas fa-tachometer-alt"></i>Tableau de bord</a></li>
			<li style="<?php echo !preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="payments.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "payments.php"){echo 'active';}?>"><i class="fas fa-wallet"></i>Trésorerie</a></li>
			<?php
			if(preg_match("#Consulter Tableau de bord|Consulter Trésorerie#",$_SESSION['easybm_roles'])){
			?>
			<li class="menu-separator"><div class="separator-line"></div></li>
			<?php
			}
			?>
			<li style="<?php echo !preg_match("#Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
				<?php
				$page = "404.php";
				if(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){	
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
				?>
				<a href="<?php echo $page;?>" class="<?php echo preg_match("#factures.php|devis.php|facturesproforma.php|bl.php|bs.php|br.php|avoirs.php#",basename($_SERVER['PHP_SELF']))?'active':'';?>"><i class="fas fa-file-invoice-dollar"></i>Documents clients</a>
			</li>
			<li style="<?php echo !preg_match("#Consulter Clients#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="clients.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "clients.php"){echo 'active';}?>"><i class="fas fa-user-friends"></i>Clients</a></li>
			<?php
			if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
			?>
			<li class="menu-separator"><div class="separator-line"></div></li>
			<?php
			}
			?>
			<li style="<?php echo !preg_match("#Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])?"display:none;":"";?>">
				<?php
				$page = "404.php";
				if(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){	
					$page = "bc.php";
				}
				elseif(preg_match("#Consulter Bons de récéption#",$_SESSION['easybm_roles'])){	
					$page = "bre.php";
				}
				?>
				<a href="<?php echo $page;?>" class="<?php echo preg_match("#bc.php|bre.php#",basename($_SERVER['PHP_SELF']))?'active':'';?>"><i class="fas fa-clipboard-list"></i>Documents fournisseurs</a>
			</li>			
			<li style="<?php echo !preg_match("#Consulter Fournisseurs#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="suppliers.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "suppliers.php"){echo 'active';}?>"><i class="fas fa-truck"></i>Fournisseurs</a></li>
			<?php
			if(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
			?>
			<li class="menu-separator"><div class="separator-line"></div></li>
			<?php
			}
			?>
			<li style="<?php echo !preg_match("#Consulter Autres dépences et recettes#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="expenses.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "expenses.php"){echo 'active';}?>"><i class="fas fa-exchange-alt"></i>Autres dépences et recettes</a></li>
			<?php
			if(preg_match("#Consulter Autres dépences et recettes#",$_SESSION['easybm_roles'])){
			?>
			<li class="menu-separator"><div class="separator-line"></div></li>
			<?php
			}
			?>
			<li style="<?php echo !preg_match("#Consulter Sociétés#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="companies.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "companies.php"){echo 'active';}?>"><i class="fas fa-building"></i>Sociétés</a></li>
			<li style="<?php echo !preg_match("#Consulter Utilisateurs#",$_SESSION['easybm_roles'])?"display:none;":"";?>"><a href="users.php" class="<?php if(basename($_SERVER['PHP_SELF']) == "users.php"){echo 'active';}?>"><i class="fas fa-user-cog"></i>Utilisateurs</a></li>
		</ul>
	</div>
</div>