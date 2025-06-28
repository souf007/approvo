<?php
session_start();
include("config.php");
include('classes/Utilities.class.php');
include('classes/SimpleImage.class.php');
require 'vendor/autoload.php';

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
	if($_SESSION['easybm_id'] != "" AND $_SESSION['easybm_fullname'] != ""){
		if(isset($_POST['action'])){
			if($_POST['action'] == "editsettings"){
				if($_SESSION['easybm_type'] == "moderator"){
					$back = $bdd->query("SELECT logo,cover FROM settings");
					$row = $back->fetch();
					if($row['logo'] != $_POST['logo'] AND $row['logo'] != "avatar.png"){
						if(file_exists("uploads/".$row['logo'])){
							unlink("uploads/".$row['logo']);
						}
						if(file_exists("uploads/micro_".$row['logo'])){
							unlink("uploads/micro_".$row['logo']);
						}
						if(file_exists("uploads/cropped_".$row['logo'])){
							unlink("uploads/cropped_".$row['logo']);
						}
						if(file_exists("uploads/small_".$row['logo'])){
							unlink("uploads/small_".$row['logo']);
						}
						if(file_exists("uploads/large_".$row['logo'])){
							unlink("uploads/large_".$row['logo']);
						}
					}
					if($row['cover'] != $_POST['cover'] AND $row['cover'] != "avatar.png"){
						if(file_exists("uploads/".$row['cover'])){
							unlink("uploads/".$row['cover']);
						}
						if(file_exists("uploads/micro_".$row['cover'])){
							unlink("uploads/micro_".$row['cover']);
						}
						if(file_exists("uploads/cropped_".$row['cover'])){
							unlink("uploads/cropped_".$row['cover']);
						}
						if(file_exists("uploads/small_".$row['cover'])){
							unlink("uploads/small_".$row['cover']);
						}
						if(file_exists("uploads/large_".$row['cover'])){
							unlink("uploads/large_".$row['cover']);
						}
					}
					$back = $bdd->query("SELECT id FROM settings");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO settings(id,logo,cover,store,defaultstate,currency) VALUE('','".$_POST['logo']."','".$_POST['cover']."','".$_POST['store']."','".$_POST['defaultstate']."','".$_POST['currency']."')");
					}
					else{
						$req = $bdd->prepare("UPDATE settings SET logo='".$_POST['logo']."',cover='".$_POST['cover']."',store='".$_POST['store']."',defaultstate='".$_POST['defaultstate']."',currency='".$_POST['currency']."'");
					}
					$req->execute();
				}
				$back = $bdd->query("SELECT id FROM parametres WHERE user='".$_SESSION['easybm_id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("INSERT INTO parametres(id,user,nbrows,rowcolor) VALUE('','".$_SESSION['easybm_id']."','".$_POST['nbrows']."','".$_POST['rowcolor']."')");
				}
				else{
					$req = $bdd->prepare("UPDATE parametres SET nbrows='".$_POST['nbrows']."',rowcolor='".$_POST['rowcolor']."' WHERE user='".$_SESSION['easybm_id']."'");
				}
				$req->execute();				
			}		

			if($_POST['action'] == "editnotifications"){
				$req = $bdd->prepare("UPDATE notifications SET facture='".$_POST['facture']."',
																avoir='".$_POST['avoir']."',
																unpaid='".$_POST['unpaid']."',
																inwaiting='".$_POST['inwaiting']."',
																outwaiting='".$_POST['outwaiting']."',
																caissein='".$_POST['caissein']."',
																caisseout='".$_POST['caisseout']."',
																remis='".$_POST['remis']."',
																incach='".$_POST['incach']."',
																outcach='".$_POST['outcach']."',
																rcaissein='".$_POST['rcaissein']."',
																rcaisseout='".$_POST['rcaisseout']."',
																rremis='".$_POST['rremis']."'");
				$req->execute();				
			}	

			if($_POST['action'] == "editinstall"){
				if($_SESSION['easybm_type'] == "moderator"){
					$back = $bdd->query("SELECT id FROM settings");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO settings(id,oneprice,onlyproduct,onlyservice,useproject,dategap) VALUE('','".$_POST['oneprice']."','".$_POST['onlyproduct']."','".$_POST['onlyservice']."','".$_POST['useproject']."','".$_POST['dategap']."')");
					}
					else{
						$req = $bdd->prepare("UPDATE settings SET oneprice='".$_POST['oneprice']."',onlyproduct='".$_POST['onlyproduct']."',onlyservice='".$_POST['onlyservice']."',useproject='".$_POST['useproject']."',dategap='".$_POST['dategap']."'");
					}
					$req->execute();
				}
				$req->execute();				
			}				

			if($_POST['action'] == "adduser"){
				if($_POST['companies'] == ""){
					$_POST['companies'] = ",0";
				}
				if($_POST['id'] == "0"){
					$back = $bdd->query("SELECT id FROM users WHERE email='".$_POST['email']."' AND trash='1'");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO users(id,fullname,picture,email,password,phone,type,roles,companies,datesignup,trash) VALUES ('0','".sanitize_vars($_POST['fullname'])."','avatar.png','".sanitize_vars($_POST['email'])."','".sanitize_vars($_POST['password'])."','".sanitize_vars($_POST['phone'])."','moderator','".substr($_POST['roles'],1)."','".sanitize_vars(substr($_POST['companies'],1))."','".time()."','1')");
						$req->execute();
					}
					else{
						echo "Login exist déjà !!";
					}
				}
				else{
					$req = $bdd->prepare("UPDATE users SET fullname='".sanitize_vars($_POST['fullname'])."',password='".sanitize_vars($_POST['password'])."',phone='".sanitize_vars($_POST['phone'])."',roles='".substr($_POST['roles'],1)."',companies='".sanitize_vars(substr($_POST['companies'],1))."' WHERE id='".$_POST['id']."'");
					$req->execute();
				}
			}

			if($_POST['action'] == "deleteuser"){
				$req = $bdd->prepare("UPDATE users SET trash='0' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "restoreuser"){
				$req = $bdd->prepare("UPDATE users SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deleteuserpermanently"){
				$req = $bdd->prepare("DELETE FROM users WHERE id='".$_POST['id']."'");
				$req->execute();
			}

			if($_POST['action'] == "resetpassword"){
				$req = $bdd->prepare("UPDATE users SET password=email WHERE id='".$_POST['id']."'");
				$req->execute();
			}
						
			if($_POST['action'] == "loadusers"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Nom et prénom <i class="fa fa-sort" data-sort="fullname"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Login <i class="fa fa-sort" data-sort="email"></i></td>
						<td>Permissions</td>
						<td>Sociétés à gérer</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM users WHERE trash='".$_POST['state']."'";
					if($_POST['keyword'] != ""){
						$req .= " AND (fullname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' OR email LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['sortby'] != ""){
						$req .= " ORDER BY ".$_POST['sortby'];
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="user" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td>
							<?php
							if($row['superadmin'] == "1"){
							?>
							<span style="color:#fb8500;">
								<i class="fa fa-shield-alt"></i> <?php echo $row['fullname'];?>
							</span>
							<?php
							}
							else{
							?>
							<span><?php echo $row['fullname'];?></span>
							<?php								
							}
							?>
						</td>
						<td><span><?php echo $row['phone'];?></span></td>
						<td><span><?php echo $row['email'];?></span></td>
						<td>
							<div class="lx-upload-files">
								<a href="javascript:;" class="lx-show-roles"><i class="fa fa-plus"></i> Afficher plus</a>
							</div>
							<div class="lx-table lx-table-user-droit">
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
										<td><i class="fa <?php echo preg_match("#Consulter Tableau de bord#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Tableau de bord#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td style="width:auto;white-space:nowrap;">Trésorerie</td>
										<td><i class="fa <?php echo preg_match("#Consulter Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Modifier Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Exporter Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Factures</td>
										<td><i class="fa <?php echo preg_match("#Consulter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Devis</td>
										<td><i class="fa <?php echo preg_match("#Consulter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Factures proforma</td>
										<td><i class="fa <?php echo preg_match("#Consulter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de livraison</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de sortie</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de retour</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Clients</td>
										<td><i class="fa <?php echo preg_match("#Consulter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Bons de commande</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Bons de réception</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Fournisseurs</td>
										<td><i class="fa <?php echo preg_match("#Consulter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Sociétés </td>
										<td><i class="fa <?php echo preg_match("#Consulter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Utilisateurs</td>
										<td><i class="fa <?php echo preg_match("#Consulter Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>TVA</td>
										<td><i class="fa <?php echo preg_match("#Consulter TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Formation</td>
										<td><i class="fa <?php echo preg_match("#Consulter Formation#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
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
											<label><i class="fa <?php echo preg_match("#Consultation des notifications#",$row['roles'])?"fa-check":"fa-times";?>"></i> Consultation des notifications</label>
											<label><i class="fa <?php echo preg_match("#Réglage des notifications#",$row['roles'])?"fa-check":"fa-times";?>"></i> Réglage des notifications</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Modification date opération</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Modification date opération#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Transformation / Dupplication documents</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Transformation / Dupplication documents#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Modification statut de paiement</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Modification statut de paiement#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Suppression d'historique de paiement</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Suppression historique de paiement#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Télécharger Backup</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Télécharger Backup#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>								
							</div>
						</td>
						<?php
						$companies = "";
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['companies']!=""?$row['companies']:0).")");
						while($row1 = $back1->fetch()){
							$companies .= ",".$row1['rs'];
						}
						?>
						<td><span><?php echo substr(str_replace(",",", ",$companies),1);?></span></td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Utilisateurs#",$_SESSION['easybm_roles'])){
									?>
							<a href="javascript:;" class="lx-edit lx-edit-user lx-open-popup" data-header="Modifier un"
								data-id="<?php echo $row['id'];?>"
								data-fullname="<?php echo $row['fullname'];?>"
								data-email="<?php echo $row['email'];?>"
								data-password="<?php echo $row['password'];?>"
								data-phone="<?php echo $row['phone'];?>"
								data-roles=",<?php echo $row['roles'];?>"
								data-companies=",<?php echo $row['companies'];?>" data-title="user">Modifier</a>
							<a href="javascript:;" class="lx-edit lx-reset-user lx-open-popup" data-id="<?php echo $row['id'];?>" data-title="resetpassword">Réinitialiser mot de passe</a>
								<?php
								}
								if(preg_match("#Supprimer Utilisateurs#",$_SESSION['easybm_roles']) AND $row['superadmin'] != "1"){
								?>
							<a href="javascript:;" class="lx-delete lx-delete-user lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Nom et prénom <i class="fa fa-sort" data-sort="fullname"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Login <i class="fa fa-sort" data-sort="email"></i></td>
						<td>Permissions</td>
						<td>Sociétés</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> utilisateur(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> utilisateur(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}
			
			if($_POST['action'] == "editaccount"){
				$back = $bdd->query("SELECT picture FROM users WHERE id='".$_SESSION['easybm_id']."'");
				$row = $back->fetch();
				if($row['picture'] != $_POST['picture'] AND $row['picture'] != "avatar.png"){
					if(file_exists("uploads/".$row['picture'])){
						unlink("uploads/".$row['picture']);
					}
					if(file_exists("uploads/micro_".$row['picture'])){
						unlink("uploads/micro_".$row['picture']);
					}
					if(file_exists("uploads/cropped_".$row['picture'])){
						unlink("uploads/cropped_".$row['picture']);
					}
					if(file_exists("uploads/small_".$row['picture'])){
						unlink("uploads/small_".$row['picture']);
					}
					if(file_exists("uploads/large_".$row['picture'])){
						unlink("uploads/large_".$row['picture']);
					}
				}
				$req = $bdd->prepare("UPDATE users SET fullname='".sanitize_vars($_POST['fullname'])."',picture='".sanitize_vars($_POST['picture'])."',phone='".sanitize_vars($_POST['phone'])."' WHERE id='".$_SESSION['easybm_id']."'");
				$req->execute();
			}

			if($_POST['action'] == "editpassword"){
				if($_POST['oldpassword'] == "" OR $_POST['newpassword1'] == "" OR $_POST['newpassword2'] == ""){
					echo '2';
				}
				else{
					$back = $bdd->query("SELECT * FROM users WHERE id='".$_SESSION['easybm_id']."' AND password='".$_POST['oldpassword']."'");
					if($back->rowCount() == 0){
						echo '3';
					}
					elseif($_POST['newpassword1'] != $_POST['newpassword2']){
						echo '4';
					}
					else{
						$req = $bdd->prepare("UPDATE users SET password='".sanitize_vars($_POST['newpassword1'])."' WHERE id='".$_SESSION['easybm_id']."'");
						$req->execute();
						echo '1';
					}
				}
			}
			
			if($_POST['action'] == "loadbankaccountslist"){
				?>
				<option value="0">Choisissez un compte banquaire</option>
				<?php
				$req = "SELECT * FROM bankaccounts WHERE company='".$_POST['id']."'";
				$back = $bdd->query($req);
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['bank']." | ".$row['rib'];?></option>
					<?php 
				}
			}

			if($_POST['action'] == "addcompany"){
				$back = $bdd->query("SELECT id FROM companies WHERE ((rs='".sanitize_vars($_POST['rs'])."' AND rs<>'')
																	OR (rc='".sanitize_vars($_POST['rc'])."' AND rc<>'') 
																	OR (patente='".sanitize_vars($_POST['patente'])."' AND patente<>'')
																	OR (iff='".sanitize_vars($_POST['iff'])."' AND iff<>'')
																	OR (cnss='".sanitize_vars($_POST['cnss'])."' AND cnss<>'')
																	OR (ice='".sanitize_vars($_POST['ice'])."' AND ice<>'')) 
																	AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO companies(id,rs,phone,address,email,website,capital,rc,patente,iff,cnss,ice,facture,devis,avoir,br,factureproforma,bl,bs,bc,bre,trash) 
						VALUES ('0','".sanitize_vars($_POST['rs'])."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['address'])."','".sanitize_vars($_POST['email'])."','".sanitize_vars($_POST['website'])."','".sanitize_vars($_POST['capital'])."','".sanitize_vars($_POST['rc'])."','".sanitize_vars($_POST['patente'])."','".sanitize_vars($_POST['iff'])."','".sanitize_vars($_POST['cnss'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['facture'])."','".sanitize_vars($_POST['devis'])."','".sanitize_vars($_POST['avoir'])."','".sanitize_vars($_POST['br'])."','".sanitize_vars($_POST['factureproforma'])."','".sanitize_vars($_POST['bl'])."','".sanitize_vars($_POST['bs'])."','".sanitize_vars($_POST['blcommand'])."','".sanitize_vars($_POST['brcommand'])."','1')");
						$req->execute();
						$back = $bdd->query("SELECT id FROM companies WHERE trash='1' ORDER BY id DESC LIMIT 0,1");
						$row = $back->fetch();
						$req = $bdd->prepare("UPDATE users SET companies=CONCAT(companies,',','".$row['id']."') WHERE (superadmin='1' OR id='".$_SESSION['easybm_id']."')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE companies SET rs='".sanitize_vars($_POST['rs'])."',
																	phone='".sanitize_vars($_POST['phone'])."',
																	address='".sanitize_vars($_POST['address'])."',
																	email='".sanitize_vars($_POST['email'])."',
																	website='".sanitize_vars($_POST['website'])."',
																	capital='".sanitize_vars($_POST['capital'])."',
																	rc='".sanitize_vars($_POST['rc'])."',
																	patente='".sanitize_vars($_POST['patente'])."',
																	iff='".sanitize_vars($_POST['iff'])."',
																	cnss='".sanitize_vars($_POST['cnss'])."',
																	ice='".sanitize_vars($_POST['ice'])."',
																	facture='".sanitize_vars($_POST['facture'])."',
																	devis='".sanitize_vars($_POST['devis'])."',
																	avoir='".sanitize_vars($_POST['avoir'])."',
																	br='".sanitize_vars($_POST['br'])."',
																	factureproforma='".sanitize_vars($_POST['factureproforma'])."',
																	bl='".sanitize_vars($_POST['bl'])."',
																	bs='".sanitize_vars($_POST['bs'])."',
																	bc='".sanitize_vars($_POST['blcommand'])."',
																	bre='".sanitize_vars($_POST['brcommand'])."'
																	WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Société exist déjà !!";
				}
			}

			if($_POST['action'] == "deletecompany"){
				$back = $bdd->query("SELECT id FROM documents WHERE FIND_IN_SET(".$_POST['id'].",company)");
				if($back->rowCount() == 0){
					$back = $bdd->query("SELECT logo1,signature FROM companies WHERE id='".$_POST['id']."'");
					$row = $back->fetch();
					if($row['logo1'] != ""){
						if(file_exists("uploads/".$row['logo1'])){
							unlink("uploads/".$row['logo1']);
						}
						if(file_exists("uploads/micro_".$row['logo1'])){
							unlink("uploads/micro_".$row['logo1']);
						}
						if(file_exists("uploads/small_".$row['logo1'])){
							unlink("uploads/small_".$row['logo1']);
						}
						if(file_exists("uploads/cropped_".$row['logo1'])){
							unlink("uploads/cropped_".$row['logo1']);
						}
						if(file_exists("uploads/large_".$row['logo1'])){
							unlink("uploads/large_".$row['logo1']);
						}
					}
					if($row['signature'] != ""){
						if(file_exists("uploads/".$row['signature'])){
							unlink("uploads/".$row['signature']);
						}
						if(file_exists("uploads/micro_".$row['signature'])){
							unlink("uploads/micro_".$row['signature']);
						}
						if(file_exists("uploads/small_".$row['signature'])){
							unlink("uploads/small_".$row['signature']);
						}
						if(file_exists("uploads/cropped_".$row['signature'])){
							unlink("uploads/cropped_".$row['signature']);
						}
						if(file_exists("uploads/large_".$row['signature'])){
							unlink("uploads/large_".$row['signature']);
						}
					}
					$req = $bdd->prepare("DELETE FROM companies WHERE id='".$_POST['id']."'");
					$req->execute();
					$req = $bdd->prepare("DELETE FROM bankaccounts WHERE company='".$_POST['id']."'");
					$req->execute();		
				}
				else{
					echo "Vous ne pouvez pas supprimer une société mouvementée !";
				}
			}
			
			if($_POST['action'] == "deleteproductpicture"){
				$allpics = str_replace(",".$_POST['pic'],"",$_POST['allpics']);
				$allpics = str_replace($_POST['pic'],"",$allpics);
				if($_POST['table'] == "companies" AND $_POST['column'] == "logo1"){
					$req = $bdd->prepare("UPDATE companies SET logo1='' WHERE id='".$_POST['id']."'");
				}
				elseif($_POST['table'] == "companies" AND $_POST['column'] == "signature"){
					$req = $bdd->prepare("UPDATE companies SET signature='' WHERE id='".$_POST['id']."'");
				}
				$req->execute();
				if(file_exists("uploads/".$_POST['pic'])){
					unlink("uploads/".$_POST['pic']);
				}
				if(file_exists("uploads/micro_".$_POST['pic'])){
					unlink("uploads/micro_".$_POST['pic']);
				}
				if(file_exists("uploads/small_".$_POST['pic'])){
					unlink("uploads/small_".$_POST['pic']);
				}
				if(file_exists("uploads/cropped_".$_POST['pic'])){
					unlink("uploads/cropped_".$_POST['pic']);
				}
				if(file_exists("uploads/large_".$_POST['pic'])){
					unlink("uploads/large_".$_POST['pic']);
				}
			}
			
			if($_POST['action'] == "restorecompany"){
				$req = $bdd->prepare("UPDATE companies SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletecompanypermanently"){
				$back = $bdd->query("SELECT id FROM products WHERE company='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM companies WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Cet enregistrement est lié à d'autres enregistrements, vous devez les supprimer avant de le supprimer !!";
				}
			}
			
			if($_POST['action'] == "addbankaccount"){
				$back = $bdd->query("SELECT id FROM bankaccounts WHERE rib='".sanitize_vars($_POST['rib'])."' AND company='".sanitize_vars($_POST['company'])."'  AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO bankaccounts(id,company,rib,bank,agency,trash) 
						VALUES ('0','".sanitize_vars($_POST['company'])."','".sanitize_vars($_POST['rib'])."','".sanitize_vars($_POST['bank'])."','".sanitize_vars($_POST['agency'])."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE bankaccounts SET rib='".sanitize_vars($_POST['rib'])."',
																	bank='".sanitize_vars($_POST['bank'])."',
																	agency='".sanitize_vars($_POST['agency'])."'
																	WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Compte banquaire exist déjà !!";
				}
			}

			if($_POST['action'] == "deletebankaccount"){
				$back = $bdd->query("SELECT id FROM payments WHERE FIND_IN_SET(".$_POST['id'].",rib)");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM bankaccounts WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Vous ne pouvez pas supprimer un compte banquaire mouvementé !";
				}
			}
			
			if($_POST['action'] == "loadcompanies"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>ID</td>
						<td>Raison social</td>
						<td>Logo</td>
						<td>Cachet / Signature</td>
						<td>Contacts société</td>
						<td>Informations société</td>
						<td>Comptes société</td>
						<td>Début de numérotation</td>
						<td>Informations supplimentaires sur le document</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM companies p WHERE trash='".$_POST['state']."'".$companiesid;
					if($_POST['keyword'] != ""){
						$req .= " AND (rs LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR address LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR email LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR website LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR rc LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR patente LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR iff LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR cnss LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR ice LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "ca"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company=p.id),0)";
						}
						elseif($_POST['sortby'] == "paid"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company=p.id),0)";
						}
						elseif($_POST['sortby'] == "encours"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company=p.id),0) - COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company=p.id),0)";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$ca = 0;
					$paid = 0;
					$rest = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="company" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo $row['id'];?></span></td>
						<td><span><?php echo $row['rs'];?></span></td>
						<td>
							<?php
							if($row['logo1'] != ""){
								?>
							<div class="lx-photos-preview">
								<?php
								if($_SESSION['easybm_type'] == "moderator"){
								?>
								<a href="javascript:;" class="lx-delete-images"><i class="material-icons"><img src="images/close.svg" style="width:22px;padding:3px;background:#EEEEEE;border-radius:6px;" /></i></a>
								<div class="lx-delete-image-choice">
									<a href="javascript:;" class="lx-yes-delete-image" data-allpics="<?php echo $row['logo1'];?>" data-pic="<?php echo $row['logo1'];?>" data-table="companies" data-column="logo1" data-id="<?php echo $row['id'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-image">Non</a>
								</div>		
								<?php
								}
								?>
								<img src="uploads/<?php echo $row['logo1'];?>" class="lx-open-popup lx-open-zoombox" data-title="zoombox" />
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<input type="file" name="uploadphotos[]" id="uploadphotos<?php echo $row['id'];?>" data-table="companies" data-column="logo1" data-id="<?php echo $row['id'];?>" accept="image/x-png,image/jpeg" />
								<a href="javascript:;" class="">Ajouter photo</a>
							</div>
						</td>
						<td>
							<?php
							if($row['signature'] != ""){
								?>
							<div class="lx-photos-preview">
								<?php
								if($_SESSION['easybm_type'] == "moderator"){
								?>
								<a href="javascript:;" class="lx-delete-images"><i class="material-icons"><img src="images/close.svg" style="width:22px;padding:3px;background:#EEEEEE;border-radius:6px;" /></i></a>
								<div class="lx-delete-image-choice">
									<a href="javascript:;" class="lx-yes-delete-image" data-allpics="<?php echo $row['signature'];?>" data-pic="<?php echo $row['signature'];?>" data-table="companies" data-column="signature" data-id="<?php echo $row['id'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-image">Non</a>
								</div>		
								<?php
								}
								?>
								<img src="uploads/<?php echo $row['signature'];?>" class="lx-open-popup lx-open-zoombox" data-title="zoombox" />
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<input type="file" name="uploadphotos[]" id="uploadphotos0<?php echo $row['id'];?>" data-table="companies" data-column="signature" data-id="<?php echo $row['id'];?>" accept="image/x-png,image/jpeg" />
								<a href="javascript:;" class="">Ajouter photo</a>
							</div>
						</td>						
						<td style="white-space:nowrap;">
							<span>Téléphone: <b><?php echo $row['phone'];?></b></span>
							<span>Adresse: <b><?php echo $row['address'];?></b></span>
							<span>Email: <b><?php echo $row['email'];?></b></span>
							<span>Siteweb: <b><?php echo $row['website'];?></b></span>
						</td>
						<td style="white-space:nowrap;">
							<span>Capital: <b><?php echo $row['capital'];?></b></span>
							<span>RC: <b><?php echo $row['rc'];?></b></span>
							<span>Patente: <b><?php echo $row['patente'];?></b></span>
							<span>IF: <b><?php echo $row['iff'];?></b></span>
							<span>CNSS: <b><?php echo $row['cnss'];?></b></span>
							<span>ICE: <b><?php echo $row['ice'];?></b></span>
						</td>
						<td style="white-space:nowrap;">
							<?php
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE company='".$row['id']."'");
							while($row1 = $back1->fetch()){
								?>
							<div class="lx-back-acocunt" style="margin-bottom:10px;padding:10px;background:#fcfdec;border:1px solid #242424;border-radius:6px;">
								<span>N° compte / RIB: <b><?php echo $row1['rib'];?></b></span>
								<span>La banque: <b><?php echo $row1['bank'];?></b></span>
								<span>Agence: <b><?php echo $row1['agency'];?></b></span>
								<p style="text-align:right;padding:0px;margin:0px;">
								<a href="javascript:;" class="lx-edit-bankaccount lx-open-popup" data-header="Modifier un" title="Modifier"
								data-id="<?php echo $row1['id'];?>"
								data-rib="<?php echo $row1['rib'];?>"
								data-bank="<?php echo $row1['bank'];?>"
								data-agency="<?php echo $row1['agency'];?>"
								data-company="<?php echo $row['id'];?>"
								data-title="bankaccount"><i class="fa fa-edit"></i></a>
								<a href="javascript:;" class="lx-delete lx-delete-bankaccount lx-open-popup" data-title="deleterecord1" title="Supprimer" style="position:relative;top:2px;font-size:16px;" 
								data-id="<?php echo $row1['id'];?>"><i class="fa fa-times"></i></a>
								</p>
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<a href="javascript:;" class="lx-new-bankaccount lx-open-popup" data-header="Ajouter un nouveau"
								data-id="0"
								data-company="<?php echo $row['id'];?>"
								data-title="bankaccount">Ajouter compte</a>
							</div>
						</div>
						<td style="white-space:nowrap;">
							<span>Factures: <b><?php echo $row['facture'];?></b></span>
							<span>Devis: <b><?php echo $row['devis'];?></b></span>
							<span>Factures avoir: <b><?php echo $row['avoir'];?></b></span>
							<span>Bons de retour: <b><?php echo $row['br'];?></b></span>
							<span>Factures proforma: <b><?php echo $row['factureproforma'];?></b></span>
							<span>Bons de livraison: <b><?php echo $row['bl'];?></b></span>
							<span>Bons de sortie: <b><?php echo $row['bs'];?></b></span>
							<span>Bons de commande: <b><?php echo $row['bc'];?></b></span>
							<span>Bons de réception : <b><?php echo $row['bre'];?></b></span>
						</td>
						<td>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='facture' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='devis' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Devis<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='avoir' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures avoir<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='br' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de retour<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='factureproforma' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures proforma<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bl' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de livraison<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bs' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de sortie<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bc' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de commande<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bre' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de réception<?php echo $check;?></span>
							<div class="lx-upload-photos">
								<a href="javascript:;" class="lx-new-extrainfo lx-open-popup" data-header="Ajouter un nouveau"
									data-company="<?php echo $row['id'];?>"
									data-title="costumizeextrainfo">Personnaliser</a>							
							</div>
						</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						$back1 = $bdd->query("SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['ca']!=""?$row1['ca']:"0"),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($tca - $row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<?php
						}
						?>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Sociétés#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-company lx-open-popup" data-header="Modifier un" 
								data-id="<?php echo $row['id'];?>"
								data-rs="<?php echo $row['rs'];?>"
								data-phone="<?php echo $row['phone'];?>"
								data-address="<?php echo $row['address'];?>"
								data-email="<?php echo $row['email'];?>"
								data-website="<?php echo $row['website'];?>"
								data-capital="<?php echo $row['capital'];?>"
								data-rc="<?php echo $row['rc'];?>"
								data-patente="<?php echo $row['patente'];?>"
								data-iff="<?php echo $row['iff'];?>"
								data-cnss="<?php echo $row['cnss'];?>"
								data-ice="<?php echo $row['ice'];?>"
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='facture' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-facturereadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-facture="<?php echo $row['facture'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='devis' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-devisreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-devis="<?php echo $row['devis'];?>"										
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='avoir' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-avoirreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-avoir="<?php echo $row['avoir'];?>"	
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='br' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-brreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-br="<?php echo $row['br'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='factureproforma' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-factureproformareadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-factureproforma="<?php echo $row['factureproforma'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bl' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-blreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-bl="<?php echo $row['bl'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bs' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-bsreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-bs="<?php echo $row['bs'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bc' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-blcommandreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-blcommand="<?php echo $row['bc'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bre' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-brcommandreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-brcommand="<?php echo $row['bre'];?>"								
								data-title="company">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Sociétés#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-company lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>ID</td>
						<td>Raison social</td>
						<td>Logo</td>
						<td>Cachet / Signature</td>
						<td>Contacts société</td>
						<td>Informations société</td>
						<td>Comptes société</td>
						<td>Début de numérotation</td>
						<td>Informations supplimentaires sur le document</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> société(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> société(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}

			if($_POST['action'] == "loaddocsextrainfo"){
				$back = $bdd->query("SELECT * FROM infodocs WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
				$row = $back->fetch();
				?>
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
					  'removeformat ',
					  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
					});
					window.setTimeout(function(){
						tinyMCE.get("modepayment").setContent(`<?php echo $row['modepayment'];?>`);
						tinyMCE.get("conditions").setContent(`<?php echo $row['conditions'];?>`);
						tinyMCE.get("abovetable").setContent(`<?php echo $row['abovetable'];?>`);						
					},1000);
				</script>
				<?php
			}	

			if($_POST['action'] == "savedocinfo"){
				$back = $bdd->query("SELECT id FROM infodocs WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
				if($back->rowCount() > 0){
					$row = $back->fetch();
					$req = $bdd->prepare("UPDATE infodocs SET modepayment='".addslashes($_POST['modepayment'])."',conditions='".addslashes($_POST['conditions'])."',abovetable='".addslashes($_POST['abovetable'])."' WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
					$req->execute();
				}
				else{
					$req = $bdd->prepare("INSERT INTO infodocs(id,company,document,modepayment,conditions,abovetable) VALUES(0,'".$_POST['company']."','".$_POST['document']."','".addslashes($_POST['modepayment'])."','".addslashes($_POST['conditions'])."','".addslashes($_POST['abovetable'])."')");
					$req->execute();					
				}
			}	
			
			if($_POST['action'] == "addsupplier"){
				$back = $bdd->query("SELECT id FROM suppliers WHERE title='".sanitize_vars($_POST['title'])."' AND codefo='".sanitize_vars($_POST['codefo'])."' AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$rand = "FR".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM suppliers ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "FR".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO suppliers(id,code,codefo,title,respname,respphone,respemail,respfax,ice,address,note,company,dateadd,trash) 
						VALUES ('0','".$rand."','".sanitize_vars($_POST['codefo'])."','".sanitize_vars($_POST['title'])."','".sanitize_vars($_POST['respname'])."','".sanitize_vars($_POST['respphone'])."','".sanitize_vars($_POST['respemail'])."','".sanitize_vars($_POST['respfax'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['address'])."','".addslashes($_POST['note'])."','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE suppliers SET codefo='".sanitize_vars($_POST['codefo'])."',title='".sanitize_vars($_POST['title'])."',respname='".sanitize_vars($_POST['respname'])."',respphone='".sanitize_vars($_POST['respphone'])."',respemail='".sanitize_vars($_POST['respemail'])."',respfax='".sanitize_vars($_POST['respfax'])."',note='".addslashes($_POST['note'])."',ice='".addslashes($_POST['ice'])."',address='".addslashes($_POST['address'])."',company='".sanitize_vars($_POST['company'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Fournisseur (Nom ou Code fournisseur) exist déjà !!";
				}
			}

			if($_POST['action'] == "deletesupplier"){
				$back = $bdd->query("SELECT id FROM documents WHERE supplier='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM suppliers WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Nous avons detecté des documents liés à ce fournisseur, vous ne pouvez pas le supprimer!";
				}
			}
			
			if($_POST['action'] == "restoresupplier"){
				$req = $bdd->prepare("UPDATE suppliers SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletesupplierpermanently"){
				$back = $bdd->query("SELECT id,supplier FROM (SELECT id,supplier FROM products UNION SELECT id,supplier FROM commandsuppliers) AS t WHERE supplier='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM suppliers WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Cet enregistrement est lié à d'autres enregistrements, vous devez les supprimer avant de le supprimer !!";
				}
			}

			if($_POST['action'] == "reloadsupplierlist"){
				?>
				<option value="">Choisissez un fournisseur</option>
				<?php
				$req = "";
				if($_POST['id'] != ""){
					$req = " AND FIND_IN_SET(".$_POST['id'].",company)";
				}
				$back = $bdd->query("SELECT id,title FROM suppliers WHERE trash='1'".$multicompanies.$req." ORDER BY title");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
					<?php 
				}
			}
			
			if($_POST['action'] == "loadsuppliers"){
				if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
				$req = "SELECT * FROM suppliers s WHERE trash='".$_POST['state']."'".$multicompanies;
				if($_POST['keyword'] != ""){
					$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codefo LIKE '%".$_POST['keyword']."%' OR respemail LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR title LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respphone LIKE '%".sanitize_vars($_POST['keyword'])."%')";
				}
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
						}
					}
					$req .= ")";
				}
				if($_POST['paid'] == "paid"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state IN('Payée') AND type IN('bc'))";
				}
				elseif($_POST['paid'] == "encours"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state NOT IN('Payée') AND type IN('bc'))";
				}
				$back4 = $bdd->query($req);
				$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
				$ca = 0;
				$paid = 0;
				$rest = 0;
				$back = $bdd->query($req);
				while($row = $back->fetch()){
						$back1 = $bdd->query("SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);	
				}
				$ca1 = $ca!=0?$ca:1;
				?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">En cours</h3>
					<p><?php echo number_format((float)(isset($rest)?$rest:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$rest*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $rest*100/$ca1?>%;background:#d9534f;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:#5cb85c;">Payé</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$paid*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $paid*100/$ca1?>%;background:#5cb85c;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#337ab7;">Chiffre d'affaires</h3>
					<p><?php echo number_format((float)(isset($ca)?$ca:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$ca*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $ca*100/$ca1?>%;background:#337ab7;"></ins>
					</h4>
				</div>
				<?php
				}
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Responsable <i class="fa fa-sort" data-sort="respname"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="respphone"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="respemail"></i></td>
						<td>Fax <i class="fa fa-sort" data-sort="respfax"></i></td>
						<td>Note</td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM suppliers s WHERE trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codefo LIKE '%".$_POST['keyword']."%' OR respemail LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR title LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respphone LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['paid'] == "paid"){
						$req .= " AND id IN(SELECT supplier FROM documents WHERE state IN('Payée') AND type IN('bc'))";
					}
					elseif($_POST['paid'] == "encours"){
						$req .= " AND id IN(SELECT supplier FROM documents WHERE state NOT IN('Payée') AND type IN('bc'))";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "ca"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier=s.id),0)";
						}
						elseif($_POST['sortby'] == "paid"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier=s.id),0)";
						}
						elseif($_POST['sortby'] == "encours"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier=s.id),0) - COALESCE((SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND client=s.id),0)";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$ca = 0;
					$paid = 0;
					$rest = 0;
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="supplier" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td>
							<span style="white-space:nowrap;"><?php echo $row['code'];?></span>
							<?php
							if($row['codefo'] != ""){
							?>
							<br />
							Code fournisseur:
							<br />
							<span style="white-space:nowrap;font-weight:500;"><?php echo $row['codefo'];?></span>
							<?php
							}
							?>
						</td>
						<td><span><?php echo $row['title'];?></span></td>
						<td><span><?php echo $row['respname'];?></span></td>
						<td style="white-space:nowrap;">
							<?php
							$companytxt = "";
							$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['company']!=""?$row['company']:0).")".$companiesid);
							while($company = $back1->fetch()){
								$companytxt .= ",".$company['rs'];
								?>
							<span><?php echo $company['rs'];?></span>
								<?php
							}
							$companytxt = substr($companytxt,1);
							?>
						</td>							
						<td><span><?php echo $row['address'];?></span></td>
						<td><span><?php echo $row['ice'];?></span></td>
						<td><span><?php echo $row['respphone'];?></span></td>
						<td><span><?php echo $row['respemail'];?></span></td>
						<td><span><?php echo $row['respfax'];?></span></td>
						<td><span><?php echo $row['note'];?></span></td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						$back1 = $bdd->query("SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['ca']!=""?$row1['ca']:"0"),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($tca - $row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						}
						?>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Fournisseurs#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-supplier lx-open-popup" data-header="Modifier un" 
								data-id="<?php echo $row['id'];?>"
								data-codefo="<?php echo $row['codefo'];?>"
								data-company="<?php echo $row['company'];?>"
								data-companytxt="<?php echo $companytxt;?>"
								data-titl="<?php echo $row['title'];?>"
								data-respname="<?php echo $row['respname'];?>"
								data-respphone="<?php echo $row['respphone'];?>"
								data-respemail="<?php echo $row['respemail'];?>" 
								data-respfax="<?php echo $row['respfax'];?>"
								data-ice="<?php echo $row['ice'];?>"
								data-address="<?php echo $row['address'];?>"
								data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="supplier">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Fournisseurs#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-supplier lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Responsable <i class="fa fa-sort" data-sort="respname"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="respphone"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="respemail"></i></td>
						<td>Fax <i class="fa fa-sort" data-sort="respfax"></i></td>
						<td>Note</td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						?>
						<td style="text-align:right;"><?php echo number_format((float)($ca),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)($paid),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)abs($rest),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> fournisseur(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> fournisseur(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}

			if($_POST['action'] == "addtva"){
				$back = $bdd->query("SELECT id FROM tvas WHERE tva='".sanitize_vars($_POST['tva'])."' AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO tvas(id,tva,trash) VALUES ('0','".sanitize_vars($_POST['tva'])."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE tvas SET tva='".sanitize_vars($_POST['tva'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "TVA existe déjà !!";
				}
			}

			if($_POST['action'] == "deletetva"){
				$req = $bdd->prepare("DELETE FROM tvas WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "restoretva"){
				$req = $bdd->prepare("UPDATE tvas SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletetvapermanently"){
				$req = $bdd->prepare("DELETE FROM tvas WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "loadtvas"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>TVA <i class="fa fa-sort" data-sort="title"></i></td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM tvas WHERE trash='1' ORDER BY tva";
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="tva" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo $row['tva'];?></span></td>
						<td style="position:relative;">
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier TVA#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-tva lx-open-popup" data-header="Modifier une" 
								data-id="<?php echo $row['id'];?>"
								data-tva="<?php echo $row['tva'];?>" data-title="tva">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer TVA#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-tva lx-open-popup" data-title="deleterecord1" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>TVA <i class="fa fa-sort" data-sort="title"></i></td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<?php
			}
			
			if($_POST['action'] == "addclient"){
				$back = $bdd->query("SELECT id FROM clients WHERE ((ice='".sanitize_vars($_POST['ice'])."' AND ice<>'') OR (iff='".sanitize_vars($_POST['iff'])."' AND iff<>'') OR (codecl='".sanitize_vars($_POST['codecl'])."' AND codecl<>'')) AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$rand = "CL".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM clients ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "CL".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO clients(id,code,codecl,ice,iff,fullname,phone,address,email,note,company,dateadd,trash) 
						VALUES ('0','".$rand."','".sanitize_vars($_POST['codecl'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['iff'])."','".sanitize_vars(removeParentheses($_POST['fullname']))."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['address'])."','".sanitize_vars($_POST['email'])."','".addslashes($_POST['note'])."','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE clients SET codecl='".sanitize_vars($_POST['codecl'])."',ice='".sanitize_vars($_POST['ice'])."',iff='".sanitize_vars($_POST['iff'])."',fullname='".sanitize_vars(removeParentheses($_POST['fullname']))."',phone='".sanitize_vars($_POST['phone'])."',address='".sanitize_vars($_POST['address'])."',email='".sanitize_vars($_POST['email'])."',note='".addslashes($_POST['note'])."',company='".sanitize_vars($_POST['company'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Code client, ICE ou IF exist déjà !!";
				}
			}

			if($_POST['action'] == "deleteclient"){
				$back = $bdd->query("SELECT id FROM documents WHERE client='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM clients WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Nous avons detecté des documents liés à ce client, vous ne pouvez pas le supprimer !";
				}
			}
			
			if($_POST['action'] == "restoreclient"){
				$req = $bdd->prepare("UPDATE clients SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deleteclientpermanently"){
				$req = $bdd->prepare("DELETE FROM clients WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "loadclientslist"){
				if($_POST['category'] == "client"){
					?>
				<option value="">Choisissez un client</option>
					<?php
					$req = "";
					if($_POST['company'] != ""){
						$req .= " AND FIND_IN_SET(".$_POST['company'].",company)";
					}
					$back = $bdd->query("SELECT id,code,ice,fullname,phone,address,email FROM clients WHERE fullname<>''".$multicompanies.$req." ORDER BY fullname");
					while($row = $back->fetch()){
						?>
				<option value="<?php echo $row['id'];?>"
					data-id="<?php echo $row['id'];?>"
					data-ice="<?php echo $row['ice'];?>"
					data-name="<?php echo $row['fullname'];?>"
					data-phone="<?php echo $row['phone'];?>"
					data-address="<?php echo $row['address'];?>"
					data-email="<?php echo $row['email'];?>"><?php echo $row['fullname']." (".$row['code'].")";?></option>
						<?php 
					}
				}
				else{
					?>
				<option value="">Choisissez un client</option>
					<?php
					$req = "";
					if($_POST['company'] != ""){
						$req .= " AND FIND_IN_SET(".$_POST['company'].",company)";
					}
					$back = $bdd->query("SELECT * FROM suppliers WHERE title<>''".$multicompanies.$req." ORDER BY title");
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
				}
			}			
			
			if($_POST['action'] == "reloadclientcommandlist"){
				?>
				<option value="">Choisissez un client</option>
				<?php
				$req = "";
				if($_POST['id'] != ""){
					$req = " AND FIND_IN_SET(".$_POST['id'].",company)";
				}
				$back = $bdd->query("SELECT id,code,ice,fullname,phone,address,email,source FROM clients WHERE fullname<>''".$multicompanies.$req." ORDER BY fullname");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"
					data-id="<?php echo $row['id'];?>"
					data-ice="<?php echo $row['ice'];?>"
					data-name="<?php echo $row['fullname'];?>"
					data-phone="<?php echo $row['phone'];?>"
					data-address="<?php echo $row['address'];?>"
					data-email="<?php echo $row['email'];?>"
					data-source="<?php echo $row['source'];?>"><?php echo $row['fullname']." (".$row['code'].")";?></option>
					<?php 
				}
			}				
			
<?php
session_start();
include("config.php");
include('classes/Utilities.class.php');
include('classes/SimpleImage.class.php');
require 'vendor/autoload.php';

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
	if($_SESSION['easybm_id'] != "" AND $_SESSION['easybm_fullname'] != ""){
		if(isset($_POST['action'])){
			if($_POST['action'] == "editsettings"){
				if($_SESSION['easybm_type'] == "moderator"){
					$back = $bdd->query("SELECT logo,cover FROM settings");
					$row = $back->fetch();
					if($row['logo'] != $_POST['logo'] AND $row['logo'] != "avatar.png"){
						if(file_exists("uploads/".$row['logo'])){
							unlink("uploads/".$row['logo']);
						}
						if(file_exists("uploads/micro_".$row['logo'])){
							unlink("uploads/micro_".$row['logo']);
						}
						if(file_exists("uploads/cropped_".$row['logo'])){
							unlink("uploads/cropped_".$row['logo']);
						}
						if(file_exists("uploads/small_".$row['logo'])){
							unlink("uploads/small_".$row['logo']);
						}
						if(file_exists("uploads/large_".$row['logo'])){
							unlink("uploads/large_".$row['logo']);
						}
					}
					if($row['cover'] != $_POST['cover'] AND $row['cover'] != "avatar.png"){
						if(file_exists("uploads/".$row['cover'])){
							unlink("uploads/".$row['cover']);
						}
						if(file_exists("uploads/micro_".$row['cover'])){
							unlink("uploads/micro_".$row['cover']);
						}
						if(file_exists("uploads/cropped_".$row['cover'])){
							unlink("uploads/cropped_".$row['cover']);
						}
						if(file_exists("uploads/small_".$row['cover'])){
							unlink("uploads/small_".$row['cover']);
						}
						if(file_exists("uploads/large_".$row['cover'])){
							unlink("uploads/large_".$row['cover']);
						}
					}
					$back = $bdd->query("SELECT id FROM settings");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO settings(id,logo,cover,store,defaultstate,currency) VALUE('','".$_POST['logo']."','".$_POST['cover']."','".$_POST['store']."','".$_POST['defaultstate']."','".$_POST['currency']."')");
					}
					else{
						$req = $bdd->prepare("UPDATE settings SET logo='".$_POST['logo']."',cover='".$_POST['cover']."',store='".$_POST['store']."',defaultstate='".$_POST['defaultstate']."',currency='".$_POST['currency']."'");
					}
					$req->execute();
				}
				$back = $bdd->query("SELECT id FROM parametres WHERE user='".$_SESSION['easybm_id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("INSERT INTO parametres(id,user,nbrows,rowcolor) VALUE('','".$_SESSION['easybm_id']."','".$_POST['nbrows']."','".$_POST['rowcolor']."')");
				}
				else{
					$req = $bdd->prepare("UPDATE parametres SET nbrows='".$_POST['nbrows']."',rowcolor='".$_POST['rowcolor']."' WHERE user='".$_SESSION['easybm_id']."'");
				}
				$req->execute();				
			}		

			if($_POST['action'] == "editnotifications"){
				$req = $bdd->prepare("UPDATE notifications SET facture='".$_POST['facture']."',
																avoir='".$_POST['avoir']."',
																unpaid='".$_POST['unpaid']."',
																inwaiting='".$_POST['inwaiting']."',
																outwaiting='".$_POST['outwaiting']."',
																caissein='".$_POST['caissein']."',
																caisseout='".$_POST['caisseout']."',
																remis='".$_POST['remis']."',
																incach='".$_POST['incach']."',
																outcach='".$_POST['outcach']."',
																rcaissein='".$_POST['rcaissein']."',
																rcaisseout='".$_POST['rcaisseout']."',
																rremis='".$_POST['rremis']."'");
				$req->execute();				
			}	

			if($_POST['action'] == "editinstall"){
				if($_SESSION['easybm_type'] == "moderator"){
					$back = $bdd->query("SELECT id FROM settings");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO settings(id,oneprice,onlyproduct,onlyservice,useproject,dategap) VALUE('','".$_POST['oneprice']."','".$_POST['onlyproduct']."','".$_POST['onlyservice']."','".$_POST['useproject']."','".$_POST['dategap']."')");
					}
					else{
						$req = $bdd->prepare("UPDATE settings SET oneprice='".$_POST['oneprice']."',onlyproduct='".$_POST['onlyproduct']."',onlyservice='".$_POST['onlyservice']."',useproject='".$_POST['useproject']."',dategap='".$_POST['dategap']."'");
					}
					$req->execute();
				}
				$req->execute();				
			}				

			if($_POST['action'] == "adduser"){
				if($_POST['companies'] == ""){
					$_POST['companies'] = ",0";
				}
				if($_POST['id'] == "0"){
					$back = $bdd->query("SELECT id FROM users WHERE email='".$_POST['email']."' AND trash='1'");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO users(id,fullname,picture,email,password,phone,type,roles,companies,datesignup,trash) VALUES ('0','".sanitize_vars($_POST['fullname'])."','avatar.png','".sanitize_vars($_POST['email'])."','".sanitize_vars($_POST['password'])."','".sanitize_vars($_POST['phone'])."','moderator','".substr($_POST['roles'],1)."','".sanitize_vars(substr($_POST['companies'],1))."','".time()."','1')");
						$req->execute();
					}
					else{
						echo "Login exist déjà !!";
					}
				}
				else{
					$req = $bdd->prepare("UPDATE users SET fullname='".sanitize_vars($_POST['fullname'])."',password='".sanitize_vars($_POST['password'])."',phone='".sanitize_vars($_POST['phone'])."',roles='".substr($_POST['roles'],1)."',companies='".sanitize_vars(substr($_POST['companies'],1))."' WHERE id='".$_POST['id']."'");
					$req->execute();
				}
			}

			if($_POST['action'] == "deleteuser"){
				$req = $bdd->prepare("UPDATE users SET trash='0' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "restoreuser"){
				$req = $bdd->prepare("UPDATE users SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deleteuserpermanently"){
				$req = $bdd->prepare("DELETE FROM users WHERE id='".$_POST['id']."'");
				$req->execute();
			}

			if($_POST['action'] == "resetpassword"){
				$req = $bdd->prepare("UPDATE users SET password=email WHERE id='".$_POST['id']."'");
				$req->execute();
			}
						
			if($_POST['action'] == "loadusers"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Nom et prénom <i class="fa fa-sort" data-sort="fullname"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Login <i class="fa fa-sort" data-sort="email"></i></td>
						<td>Permissions</td>
						<td>Sociétés à gérer</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM users WHERE trash='".$_POST['state']."'";
					if($_POST['keyword'] != ""){
						$req .= " AND (fullname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' OR email LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['sortby'] != ""){
						$req .= " ORDER BY ".$_POST['sortby'];
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="user" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td>
							<?php
							if($row['superadmin'] == "1"){
							?>
							<span style="color:#fb8500;">
								<i class="fa fa-shield-alt"></i> <?php echo $row['fullname'];?>
							</span>
							<?php
							}
							else{
							?>
							<span><?php echo $row['fullname'];?></span>
							<?php								
							}
							?>
						</td>
						<td><span><?php echo $row['phone'];?></span></td>
						<td><span><?php echo $row['email'];?></span></td>
						<td>
							<div class="lx-upload-files">
								<a href="javascript:;" class="lx-show-roles"><i class="fa fa-plus"></i> Afficher plus</a>
							</div>
							<div class="lx-table lx-table-user-droit">
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
										<td><i class="fa <?php echo preg_match("#Consulter Tableau de bord#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Tableau de bord#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td style="width:auto;white-space:nowrap;">Trésorerie</td>
										<td><i class="fa <?php echo preg_match("#Consulter Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Modifier Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Exporter Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Trésorerie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Factures</td>
										<td><i class="fa <?php echo preg_match("#Consulter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Factures#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Devis</td>
										<td><i class="fa <?php echo preg_match("#Consulter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Devis#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Factures proforma</td>
										<td><i class="fa <?php echo preg_match("#Consulter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Factures proforma#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de livraison</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de livraison#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de sortie</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de sortie#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Bons de retour</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td><i class="fa <?php echo preg_match("#Consultation de la journée en cours seulement Bons de retour#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
									</tr>
									<tr>
										<td>Clients</td>
										<td><i class="fa <?php echo preg_match("#Consulter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Clients#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Bons de commande</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de commande#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Bons de réception</td>
										<td><i class="fa <?php echo preg_match("#Consulter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Bons de réception#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Fournisseurs</td>
										<td><i class="fa <?php echo preg_match("#Consulter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Fournisseurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Sociétés </td>
										<td><i class="fa <?php echo preg_match("#Consulter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Exporter Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#CA Sociétés#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
									</tr>
									<tr>
										<td>Utilisateurs</td>
										<td><i class="fa <?php echo preg_match("#Consulter Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer Utilisateurs#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>TVA</td>
										<td><i class="fa <?php echo preg_match("#Consulter TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Ajouter TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Modifier TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td><i class="fa <?php echo preg_match("#Supprimer TVA#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Formation</td>
										<td><i class="fa <?php echo preg_match("#Consulter Formation#",$row['roles'])?"fa-check":"fa-times";?>"></i></td>
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
											<label><i class="fa <?php echo preg_match("#Consultation des notifications#",$row['roles'])?"fa-check":"fa-times";?>"></i> Consultation des notifications</label>
											<label><i class="fa <?php echo preg_match("#Réglage des notifications#",$row['roles'])?"fa-check":"fa-times";?>"></i> Réglage des notifications</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Modification date opération</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Modification date opération#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Transformation / Dupplication documents</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Transformation / Dupplication documents#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Modification statut de paiement</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Modification statut de paiement#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Suppression d'historique de paiement</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Suppression historique de paiement#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td>Télécharger Backup</td>
									</tr>
									<tr>
										<td>
											<label><i class="fa <?php echo preg_match("#Télécharger Backup#",$row['roles'])?"fa-check":"fa-times";?>"></i> Oui</label>
										</td>
									</tr>
								</table>								
							</div>
						</td>
						<?php
						$companies = "";
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['companies']!=""?$row['companies']:0).")");
						while($row1 = $back1->fetch()){
							$companies .= ",".$row1['rs'];
						}
						?>
						<td><span><?php echo substr(str_replace(",",", ",$companies),1);?></span></td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Utilisateurs#",$_SESSION['easybm_roles'])){
									?>
							<a href="javascript:;" class="lx-edit lx-edit-user lx-open-popup" data-header="Modifier un"
								data-id="<?php echo $row['id'];?>"
								data-fullname="<?php echo $row['fullname'];?>"
								data-email="<?php echo $row['email'];?>"
								data-password="<?php echo $row['password'];?>"
								data-phone="<?php echo $row['phone'];?>"
								data-roles=",<?php echo $row['roles'];?>"
								data-companies=",<?php echo $row['companies'];?>" data-title="user">Modifier</a>
							<a href="javascript:;" class="lx-edit lx-reset-user lx-open-popup" data-id="<?php echo $row['id'];?>" data-title="resetpassword">Réinitialiser mot de passe</a>
								<?php
								}
								if(preg_match("#Supprimer Utilisateurs#",$_SESSION['easybm_roles']) AND $row['superadmin'] != "1"){
								?>
							<a href="javascript:;" class="lx-delete lx-delete-user lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Nom et prénom <i class="fa fa-sort" data-sort="fullname"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Login <i class="fa fa-sort" data-sort="email"></i></td>
						<td>Permissions</td>
						<td>Sociétés</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> utilisateur(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> utilisateur(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}
			
			if($_POST['action'] == "editaccount"){
				$back = $bdd->query("SELECT picture FROM users WHERE id='".$_SESSION['easybm_id']."'");
				$row = $back->fetch();
				if($row['picture'] != $_POST['picture'] AND $row['picture'] != "avatar.png"){
					if(file_exists("uploads/".$row['picture'])){
						unlink("uploads/".$row['picture']);
					}
					if(file_exists("uploads/micro_".$row['picture'])){
						unlink("uploads/micro_".$row['picture']);
					}
					if(file_exists("uploads/cropped_".$row['picture'])){
						unlink("uploads/cropped_".$row['picture']);
					}
					if(file_exists("uploads/small_".$row['picture'])){
						unlink("uploads/small_".$row['picture']);
					}
					if(file_exists("uploads/large_".$row['picture'])){
						unlink("uploads/large_".$row['picture']);
					}
				}
				$req = $bdd->prepare("UPDATE users SET fullname='".sanitize_vars($_POST['fullname'])."',picture='".sanitize_vars($_POST['picture'])."',phone='".sanitize_vars($_POST['phone'])."' WHERE id='".$_SESSION['easybm_id']."'");
				$req->execute();
			}

			if($_POST['action'] == "editpassword"){
				if($_POST['oldpassword'] == "" OR $_POST['newpassword1'] == "" OR $_POST['newpassword2'] == ""){
					echo '2';
				}
				else{
					$back = $bdd->query("SELECT * FROM users WHERE id='".$_SESSION['easybm_id']."' AND password='".$_POST['oldpassword']."'");
					if($back->rowCount() == 0){
						echo '3';
					}
					elseif($_POST['newpassword1'] != $_POST['newpassword2']){
						echo '4';
					}
					else{
						$req = $bdd->prepare("UPDATE users SET password='".sanitize_vars($_POST['newpassword1'])."' WHERE id='".$_SESSION['easybm_id']."'");
						$req->execute();
						echo '1';
					}
				}
			}
			
			if($_POST['action'] == "loadbankaccountslist"){
				?>
				<option value="0">Choisissez un compte banquaire</option>
				<?php
				$req = "SELECT * FROM bankaccounts WHERE company='".$_POST['id']."'";
				$back = $bdd->query($req);
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['bank']." | ".$row['rib'];?></option>
					<?php 
				}
			}

			if($_POST['action'] == "addcompany"){
				$back = $bdd->query("SELECT id FROM companies WHERE ((rs='".sanitize_vars($_POST['rs'])."' AND rs<>'')
																	OR (rc='".sanitize_vars($_POST['rc'])."' AND rc<>'') 
																	OR (patente='".sanitize_vars($_POST['patente'])."' AND patente<>'')
																	OR (iff='".sanitize_vars($_POST['iff'])."' AND iff<>'')
																	OR (cnss='".sanitize_vars($_POST['cnss'])."' AND cnss<>'')
																	OR (ice='".sanitize_vars($_POST['ice'])."' AND ice<>'')) 
																	AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO companies(id,rs,phone,address,email,website,capital,rc,patente,iff,cnss,ice,facture,devis,avoir,br,factureproforma,bl,bs,bc,bre,trash) 
						VALUES ('0','".sanitize_vars($_POST['rs'])."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['address'])."','".sanitize_vars($_POST['email'])."','".sanitize_vars($_POST['website'])."','".sanitize_vars($_POST['capital'])."','".sanitize_vars($_POST['rc'])."','".sanitize_vars($_POST['patente'])."','".sanitize_vars($_POST['iff'])."','".sanitize_vars($_POST['cnss'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['facture'])."','".sanitize_vars($_POST['devis'])."','".sanitize_vars($_POST['avoir'])."','".sanitize_vars($_POST['br'])."','".sanitize_vars($_POST['factureproforma'])."','".sanitize_vars($_POST['bl'])."','".sanitize_vars($_POST['bs'])."','".sanitize_vars($_POST['blcommand'])."','".sanitize_vars($_POST['brcommand'])."','1')");
						$req->execute();
						$back = $bdd->query("SELECT id FROM companies WHERE trash='1' ORDER BY id DESC LIMIT 0,1");
						$row = $back->fetch();
						$req = $bdd->prepare("UPDATE users SET companies=CONCAT(companies,',','".$row['id']."') WHERE (superadmin='1' OR id='".$_SESSION['easybm_id']."')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE companies SET rs='".sanitize_vars($_POST['rs'])."',
																	phone='".sanitize_vars($_POST['phone'])."',
																	address='".sanitize_vars($_POST['address'])."',
																	email='".sanitize_vars($_POST['email'])."',
																	website='".sanitize_vars($_POST['website'])."',
																	capital='".sanitize_vars($_POST['capital'])."',
																	rc='".sanitize_vars($_POST['rc'])."',
																	patente='".sanitize_vars($_POST['patente'])."',
																	iff='".sanitize_vars($_POST['iff'])."',
																	cnss='".sanitize_vars($_POST['cnss'])."',
																	ice='".sanitize_vars($_POST['ice'])."',
																	facture='".sanitize_vars($_POST['facture'])."',
																	devis='".sanitize_vars($_POST['devis'])."',
																	avoir='".sanitize_vars($_POST['avoir'])."',
																	br='".sanitize_vars($_POST['br'])."',
																	factureproforma='".sanitize_vars($_POST['factureproforma'])."',
																	bl='".sanitize_vars($_POST['bl'])."',
																	bs='".sanitize_vars($_POST['bs'])."',
																	bc='".sanitize_vars($_POST['blcommand'])."',
																	bre='".sanitize_vars($_POST['brcommand'])."'
																	WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Société exist déjà !!";
				}
			}

			if($_POST['action'] == "deletecompany"){
				$back = $bdd->query("SELECT id FROM documents WHERE FIND_IN_SET(".$_POST['id'].",company)");
				if($back->rowCount() == 0){
					$back = $bdd->query("SELECT logo1,signature FROM companies WHERE id='".$_POST['id']."'");
					$row = $back->fetch();
					if($row['logo1'] != ""){
						if(file_exists("uploads/".$row['logo1'])){
							unlink("uploads/".$row['logo1']);
						}
						if(file_exists("uploads/micro_".$row['logo1'])){
							unlink("uploads/micro_".$row['logo1']);
						}
						if(file_exists("uploads/small_".$row['logo1'])){
							unlink("uploads/small_".$row['logo1']);
						}
						if(file_exists("uploads/cropped_".$row['logo1'])){
							unlink("uploads/cropped_".$row['logo1']);
						}
						if(file_exists("uploads/large_".$row['logo1'])){
							unlink("uploads/large_".$row['logo1']);
						}
					}
					if($row['signature'] != ""){
						if(file_exists("uploads/".$row['signature'])){
							unlink("uploads/".$row['signature']);
						}
						if(file_exists("uploads/micro_".$row['signature'])){
							unlink("uploads/micro_".$row['signature']);
						}
						if(file_exists("uploads/small_".$row['signature'])){
							unlink("uploads/small_".$row['signature']);
						}
						if(file_exists("uploads/cropped_".$row['signature'])){
							unlink("uploads/cropped_".$row['signature']);
						}
						if(file_exists("uploads/large_".$row['signature'])){
							unlink("uploads/large_".$row['signature']);
						}
					}
					$req = $bdd->prepare("DELETE FROM companies WHERE id='".$_POST['id']."'");
					$req->execute();
					$req = $bdd->prepare("DELETE FROM bankaccounts WHERE company='".$_POST['id']."'");
					$req->execute();		
				}
				else{
					echo "Vous ne pouvez pas supprimer une société mouvementée !";
				}
			}
			
			if($_POST['action'] == "deleteproductpicture"){
				$allpics = str_replace(",".$_POST['pic'],"",$_POST['allpics']);
				$allpics = str_replace($_POST['pic'],"",$allpics);
				if($_POST['table'] == "companies" AND $_POST['column'] == "logo1"){
					$req = $bdd->prepare("UPDATE companies SET logo1='' WHERE id='".$_POST['id']."'");
				}
				elseif($_POST['table'] == "companies" AND $_POST['column'] == "signature"){
					$req = $bdd->prepare("UPDATE companies SET signature='' WHERE id='".$_POST['id']."'");
				}
				$req->execute();
				if(file_exists("uploads/".$_POST['pic'])){
					unlink("uploads/".$_POST['pic']);
				}
				if(file_exists("uploads/micro_".$_POST['pic'])){
					unlink("uploads/micro_".$_POST['pic']);
				}
				if(file_exists("uploads/small_".$_POST['pic'])){
					unlink("uploads/small_".$_POST['pic']);
				}
				if(file_exists("uploads/cropped_".$_POST['pic'])){
					unlink("uploads/cropped_".$_POST['pic']);
				}
				if(file_exists("uploads/large_".$_POST['pic'])){
					unlink("uploads/large_".$_POST['pic']);
				}
			}
			
			if($_POST['action'] == "restorecompany"){
				$req = $bdd->prepare("UPDATE companies SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletecompanypermanently"){
				$back = $bdd->query("SELECT id FROM products WHERE company='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM companies WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Cet enregistrement est lié à d'autres enregistrements, vous devez les supprimer avant de le supprimer !!";
				}
			}
			
			if($_POST['action'] == "addbankaccount"){
				$back = $bdd->query("SELECT id FROM bankaccounts WHERE rib='".sanitize_vars($_POST['rib'])."' AND company='".sanitize_vars($_POST['company'])."'  AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO bankaccounts(id,company,rib,bank,agency,trash) 
						VALUES ('0','".sanitize_vars($_POST['company'])."','".sanitize_vars($_POST['rib'])."','".sanitize_vars($_POST['bank'])."','".sanitize_vars($_POST['agency'])."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE bankaccounts SET rib='".sanitize_vars($_POST['rib'])."',
																	bank='".sanitize_vars($_POST['bank'])."',
																	agency='".sanitize_vars($_POST['agency'])."'
																	WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Compte banquaire exist déjà !!";
				}
			}

			if($_POST['action'] == "deletebankaccount"){
				$back = $bdd->query("SELECT id FROM payments WHERE FIND_IN_SET(".$_POST['id'].",rib)");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM bankaccounts WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Vous ne pouvez pas supprimer un compte banquaire mouvementé !";
				}
			}
			
			if($_POST['action'] == "loadcompanies"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>ID</td>
						<td>Raison social</td>
						<td>Logo</td>
						<td>Cachet / Signature</td>
						<td>Contacts société</td>
						<td>Informations société</td>
						<td>Comptes société</td>
						<td>Début de numérotation</td>
						<td>Informations supplimentaires sur le document</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM companies p WHERE trash='".$_POST['state']."'".$companiesid;
					if($_POST['keyword'] != ""){
						$req .= " AND (rs LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR address LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR email LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR website LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR rc LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR patente LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR iff LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR cnss LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR ice LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "ca"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company=p.id),0)";
						}
						elseif($_POST['sortby'] == "paid"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company=p.id),0)";
						}
						elseif($_POST['sortby'] == "encours"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company=p.id),0) - COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company=p.id),0)";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$ca = 0;
					$paid = 0;
					$rest = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="company" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo $row['id'];?></span></td>
						<td><span><?php echo $row['rs'];?></span></td>
						<td>
							<?php
							if($row['logo1'] != ""){
								?>
							<div class="lx-photos-preview">
								<?php
								if($_SESSION['easybm_type'] == "moderator"){
								?>
								<a href="javascript:;" class="lx-delete-images"><i class="material-icons"><img src="images/close.svg" style="width:22px;padding:3px;background:#EEEEEE;border-radius:6px;" /></i></a>
								<div class="lx-delete-image-choice">
									<a href="javascript:;" class="lx-yes-delete-image" data-allpics="<?php echo $row['logo1'];?>" data-pic="<?php echo $row['logo1'];?>" data-table="companies" data-column="logo1" data-id="<?php echo $row['id'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-image">Non</a>
								</div>		
								<?php
								}
								?>
								<img src="uploads/<?php echo $row['logo1'];?>" class="lx-open-popup lx-open-zoombox" data-title="zoombox" />
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<input type="file" name="uploadphotos[]" id="uploadphotos<?php echo $row['id'];?>" data-table="companies" data-column="logo1" data-id="<?php echo $row['id'];?>" accept="image/x-png,image/jpeg" />
								<a href="javascript:;" class="">Ajouter photo</a>
							</div>
						</td>
						<td>
							<?php
							if($row['signature'] != ""){
								?>
							<div class="lx-photos-preview">
								<?php
								if($_SESSION['easybm_type'] == "moderator"){
								?>
								<a href="javascript:;" class="lx-delete-images"><i class="material-icons"><img src="images/close.svg" style="width:22px;padding:3px;background:#EEEEEE;border-radius:6px;" /></i></a>
								<div class="lx-delete-image-choice">
									<a href="javascript:;" class="lx-yes-delete-image" data-allpics="<?php echo $row['signature'];?>" data-pic="<?php echo $row['signature'];?>" data-table="companies" data-column="signature" data-id="<?php echo $row['id'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-image">Non</a>
								</div>		
								<?php
								}
								?>
								<img src="uploads/<?php echo $row['signature'];?>" class="lx-open-popup lx-open-zoombox" data-title="zoombox" />
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<input type="file" name="uploadphotos[]" id="uploadphotos0<?php echo $row['id'];?>" data-table="companies" data-column="signature" data-id="<?php echo $row['id'];?>" accept="image/x-png,image/jpeg" />
								<a href="javascript:;" class="">Ajouter photo</a>
							</div>
						</td>						
						<td style="white-space:nowrap;">
							<span>Téléphone: <b><?php echo $row['phone'];?></b></span>
							<span>Adresse: <b><?php echo $row['address'];?></b></span>
							<span>Email: <b><?php echo $row['email'];?></b></span>
							<span>Siteweb: <b><?php echo $row['website'];?></b></span>
						</td>
						<td style="white-space:nowrap;">
							<span>Capital: <b><?php echo $row['capital'];?></b></span>
							<span>RC: <b><?php echo $row['rc'];?></b></span>
							<span>Patente: <b><?php echo $row['patente'];?></b></span>
							<span>IF: <b><?php echo $row['iff'];?></b></span>
							<span>CNSS: <b><?php echo $row['cnss'];?></b></span>
							<span>ICE: <b><?php echo $row['ice'];?></b></span>
						</td>
						<td style="white-space:nowrap;">
							<?php
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE company='".$row['id']."'");
							while($row1 = $back1->fetch()){
								?>
							<div class="lx-back-acocunt" style="margin-bottom:10px;padding:10px;background:#fcfdec;border:1px solid #242424;border-radius:6px;">
								<span>N° compte / RIB: <b><?php echo $row1['rib'];?></b></span>
								<span>La banque: <b><?php echo $row1['bank'];?></b></span>
								<span>Agence: <b><?php echo $row1['agency'];?></b></span>
								<p style="text-align:right;padding:0px;margin:0px;">
								<a href="javascript:;" class="lx-edit-bankaccount lx-open-popup" data-header="Modifier un" title="Modifier"
								data-id="<?php echo $row1['id'];?>"
								data-rib="<?php echo $row1['rib'];?>"
								data-bank="<?php echo $row1['bank'];?>"
								data-agency="<?php echo $row1['agency'];?>"
								data-company="<?php echo $row['id'];?>"
								data-title="bankaccount"><i class="fa fa-edit"></i></a>
								<a href="javascript:;" class="lx-delete lx-delete-bankaccount lx-open-popup" data-title="deleterecord1" title="Supprimer" style="position:relative;top:2px;font-size:16px;" 
								data-id="<?php echo $row1['id'];?>"><i class="fa fa-times"></i></a>
								</p>
							</div>
								<?php
							}
							?>
							<div class="lx-upload-photos">
								<a href="javascript:;" class="lx-new-bankaccount lx-open-popup" data-header="Ajouter un nouveau"
								data-id="0"
								data-company="<?php echo $row['id'];?>"
								data-title="bankaccount">Ajouter compte</a>
							</div>
						</div>
						<td style="white-space:nowrap;">
							<span>Factures: <b><?php echo $row['facture'];?></b></span>
							<span>Devis: <b><?php echo $row['devis'];?></b></span>
							<span>Factures avoir: <b><?php echo $row['avoir'];?></b></span>
							<span>Bons de retour: <b><?php echo $row['br'];?></b></span>
							<span>Factures proforma: <b><?php echo $row['factureproforma'];?></b></span>
							<span>Bons de livraison: <b><?php echo $row['bl'];?></b></span>
							<span>Bons de sortie: <b><?php echo $row['bs'];?></b></span>
							<span>Bons de commande: <b><?php echo $row['bc'];?></b></span>
							<span>Bons de réception : <b><?php echo $row['bre'];?></b></span>
						</td>
						<td>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='facture' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='devis' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Devis<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='avoir' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures avoir<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='br' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de retour<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='factureproforma' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Factures proforma<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bl' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de livraison<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bs' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de sortie<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bc' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de commande<?php echo $check;?></span>
							<?php
							$check = ' <i class="fa fa-times" style="font-weight:bold;color:#FF0000;"></i>';
							$back1 = $bdd->query("SELECT id FROM infodocs WHERE company='".$row['id']."' AND document='bre' AND (modepayment<>'' OR conditions<>'' OR abovetable<>'')");
							if($back1->rowCount() > 0){
								$check = ' <i class="fa fa-check" style="font-weight:bold;color:green;"></i>';
							}
							?>
							<span>Bons de réception<?php echo $check;?></span>
							<div class="lx-upload-photos">
								<a href="javascript:;" class="lx-new-extrainfo lx-open-popup" data-header="Ajouter un nouveau"
									data-company="<?php echo $row['id'];?>"
									data-title="costumizeextrainfo">Personnaliser</a>							
							</div>
						</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						$back1 = $bdd->query("SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE company='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['ca']!=""?$row1['ca']:"0"),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE company='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($tca - $row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<?php
						}
						?>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Sociétés#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-company lx-open-popup" data-header="Modifier un" 
								data-id="<?php echo $row['id'];?>"
								data-rs="<?php echo $row['rs'];?>"
								data-phone="<?php echo $row['phone'];?>"
								data-address="<?php echo $row['address'];?>"
								data-email="<?php echo $row['email'];?>"
								data-website="<?php echo $row['website'];?>"
								data-capital="<?php echo $row['capital'];?>"
								data-rc="<?php echo $row['rc'];?>"
								data-patente="<?php echo $row['patente'];?>"
								data-iff="<?php echo $row['iff'];?>"
								data-cnss="<?php echo $row['cnss'];?>"
								data-ice="<?php echo $row['ice'];?>"
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='facture' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-facturereadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-facture="<?php echo $row['facture'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='devis' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-devisreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-devis="<?php echo $row['devis'];?>"										
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='avoir' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-avoirreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-avoir="<?php echo $row['avoir'];?>"	
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='br' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-brreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-br="<?php echo $row['br'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='factureproforma' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-factureproformareadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-factureproforma="<?php echo $row['factureproforma'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bl' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-blreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-bl="<?php echo $row['bl'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bs' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-bsreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-bs="<?php echo $row['bs'];?>"								
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bc' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-blcommandreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-blcommand="<?php echo $row['bc'];?>"							
								<?php
								$back1 = $bdd->query("SELECT id FROM documents WHERE type='bre' AND company='".$row['id']."' LIMIT 0,1");
								?>
								data-brcommandreadonly="<?php echo $back1->rowCount()>0?"true":"false";?>"	
								data-brcommand="<?php echo $row['bre'];?>"								
								data-title="company">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Sociétés#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-company lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>ID</td>
						<td>Raison social</td>
						<td>Logo</td>
						<td>Cachet / Signature</td>
						<td>Contacts société</td>
						<td>Informations société</td>
						<td>Comptes société</td>
						<td>Début de numérotation</td>
						<td>Informations supplimentaires sur le document</td>
						<?php
						if(preg_match("#CA Sociétés#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> société(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> société(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}

			if($_POST['action'] == "loaddocsextrainfo"){
				$back = $bdd->query("SELECT * FROM infodocs WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
				$row = $back->fetch();
				?>
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
					  'removeformat ',
					  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
					});
					window.setTimeout(function(){
						tinyMCE.get("modepayment").setContent(`<?php echo $row['modepayment'];?>`);
						tinyMCE.get("conditions").setContent(`<?php echo $row['conditions'];?>`);
						tinyMCE.get("abovetable").setContent(`<?php echo $row['abovetable'];?>`);						
					},1000);
				</script>
				<?php
			}	

			if($_POST['action'] == "savedocinfo"){
				$back = $bdd->query("SELECT id FROM infodocs WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
				if($back->rowCount() > 0){
					$row = $back->fetch();
					$req = $bdd->prepare("UPDATE infodocs SET modepayment='".addslashes($_POST['modepayment'])."',conditions='".addslashes($_POST['conditions'])."',abovetable='".addslashes($_POST['abovetable'])."' WHERE company='".$_POST['company']."' AND document='".$_POST['document']."'");
					$req->execute();
				}
				else{
					$req = $bdd->prepare("INSERT INTO infodocs(id,company,document,modepayment,conditions,abovetable) VALUES(0,'".$_POST['company']."','".$_POST['document']."','".addslashes($_POST['modepayment'])."','".addslashes($_POST['conditions'])."','".addslashes($_POST['abovetable'])."')");
					$req->execute();					
				}
			}	
			
			if($_POST['action'] == "addsupplier"){
				$back = $bdd->query("SELECT id FROM suppliers WHERE title='".sanitize_vars($_POST['title'])."' AND codefo='".sanitize_vars($_POST['codefo'])."' AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$rand = "FR".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM suppliers ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "FR".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO suppliers(id,code,codefo,title,respname,respphone,respemail,respfax,ice,address,note,company,dateadd,trash) 
						VALUES ('0','".$rand."','".sanitize_vars($_POST['codefo'])."','".sanitize_vars($_POST['title'])."','".sanitize_vars($_POST['respname'])."','".sanitize_vars($_POST['respphone'])."','".sanitize_vars($_POST['respemail'])."','".sanitize_vars($_POST['respfax'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['address'])."','".addslashes($_POST['note'])."','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE suppliers SET codefo='".sanitize_vars($_POST['codefo'])."',title='".sanitize_vars($_POST['title'])."',respname='".sanitize_vars($_POST['respname'])."',respphone='".sanitize_vars($_POST['respphone'])."',respemail='".sanitize_vars($_POST['respemail'])."',respfax='".sanitize_vars($_POST['respfax'])."',note='".addslashes($_POST['note'])."',ice='".addslashes($_POST['ice'])."',address='".addslashes($_POST['address'])."',company='".sanitize_vars($_POST['company'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Fournisseur (Nom ou Code fournisseur) exist déjà !!";
				}
			}

			if($_POST['action'] == "deletesupplier"){
				$back = $bdd->query("SELECT id FROM documents WHERE supplier='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM suppliers WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Nous avons detecté des documents liés à ce fournisseur, vous ne pouvez pas le supprimer!";
				}
			}
			
			if($_POST['action'] == "restoresupplier"){
				$req = $bdd->prepare("UPDATE suppliers SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletesupplierpermanently"){
				$back = $bdd->query("SELECT id,supplier FROM (SELECT id,supplier FROM products UNION SELECT id,supplier FROM commandsuppliers) AS t WHERE supplier='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM suppliers WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Cet enregistrement est lié à d'autres enregistrements, vous devez les supprimer avant de le supprimer !!";
				}
			}

			if($_POST['action'] == "reloadsupplierlist"){
				?>
				<option value="">Choisissez un fournisseur</option>
				<?php
				$req = "";
				if($_POST['id'] != ""){
					$req = " AND FIND_IN_SET(".$_POST['id'].",company)";
				}
				$back = $bdd->query("SELECT id,title FROM suppliers WHERE trash='1'".$multicompanies.$req." ORDER BY title");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
					<?php 
				}
			}
			
			if($_POST['action'] == "loadsuppliers"){
				if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
				$req = "SELECT * FROM suppliers s WHERE trash='".$_POST['state']."'".$multicompanies;
				if($_POST['keyword'] != ""){
					$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codefo LIKE '%".$_POST['keyword']."%' OR respemail LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR title LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respphone LIKE '%".sanitize_vars($_POST['keyword'])."%')";
				}
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
						}
					}
					$req .= ")";
				}
				if($_POST['paid'] == "paid"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state IN('Payée') AND type IN('bc'))";
				}
				elseif($_POST['paid'] == "encours"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state NOT IN('Payée') AND type IN('bc'))";
				}
				$back4 = $bdd->query($req);
				$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
				$ca = 0;
				$paid = 0;
				$rest = 0;
				$back = $bdd->query($req);
				while($row = $back->fetch()){
						$back1 = $bdd->query("SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);	
				}
				$ca1 = $ca!=0?$ca:1;
				?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">En cours</h3>
					<p><?php echo number_format((float)(isset($rest)?$rest:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$rest*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $rest*100/$ca1?>%;background:#d9534f;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:#5cb85c;">Payé</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$paid*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $paid*100/$ca1?>%;background:#5cb85c;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#337ab7;">Chiffre d'affaires</h3>
					<p><?php echo number_format((float)(isset($ca)?$ca:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$ca*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $ca*100/$ca1?>%;background:#337ab7;"></ins>
					</h4>
				</div>
				<?php
				}
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Responsable <i class="fa fa-sort" data-sort="respname"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="respphone"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="respemail"></i></td>
						<td>Fax <i class="fa fa-sort" data-sort="respfax"></i></td>
						<td>Note</td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM suppliers s WHERE trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codefo LIKE '%".$_POST['keyword']."%' OR respemail LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR title LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR respphone LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['paid'] == "paid"){
						$req .= " AND id IN(SELECT supplier FROM documents WHERE state IN('Payée') AND type IN('bc'))";
					}
					elseif($_POST['paid'] == "encours"){
						$req .= " AND id IN(SELECT supplier FROM documents WHERE state NOT IN('Payée') AND type IN('bc'))";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "ca"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier=s.id),0)";
						}
						elseif($_POST['sortby'] == "paid"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier=s.id),0)";
						}
						elseif($_POST['sortby'] == "encours"){
							$req .= " ORDER BY COALESCE((SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier=s.id),0) - COALESCE((SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND client=s.id),0)";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$ca = 0;
					$paid = 0;
					$rest = 0;
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="supplier" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td>
							<span style="white-space:nowrap;"><?php echo $row['code'];?></span>
							<?php
							if($row['codefo'] != ""){
							?>
							<br />
							Code fournisseur:
							<br />
							<span style="white-space:nowrap;font-weight:500;"><?php echo $row['codefo'];?></span>
							<?php
							}
							?>
						</td>
						<td><span><?php echo $row['title'];?></span></td>
						<td><span><?php echo $row['respname'];?></span></td>
						<td style="white-space:nowrap;">
							<?php
							$companytxt = "";
							$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['company']!=""?$row['company']:0).")".$companiesid);
							while($company = $back1->fetch()){
								$companytxt .= ",".$company['rs'];
								?>
							<span><?php echo $company['rs'];?></span>
								<?php
							}
							$companytxt = substr($companytxt,1);
							?>
						</td>							
						<td><span><?php echo $row['address'];?></span></td>
						<td><span><?php echo $row['ice'];?></span></td>
						<td><span><?php echo $row['respphone'];?></span></td>
						<td><span><?php echo $row['respemail'];?></span></td>
						<td><span><?php echo $row['respfax'];?></span></td>
						<td><span><?php echo $row['note'];?></span></td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						$back1 = $bdd->query("SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['ca']!=""?$row1['ca']:"0"),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($tca - $row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						}
						?>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Fournisseurs#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-supplier lx-open-popup" data-header="Modifier un" 
								data-id="<?php echo $row['id'];?>"
								data-codefo="<?php echo $row['codefo'];?>"
								data-company="<?php echo $row['company'];?>"
								data-companytxt="<?php echo $companytxt;?>"
								data-titl="<?php echo $row['title'];?>"
								data-respname="<?php echo $row['respname'];?>"
								data-respphone="<?php echo $row['respphone'];?>"
								data-respemail="<?php echo $row['respemail'];?>" 
								data-respfax="<?php echo $row['respfax'];?>"
								data-ice="<?php echo $row['ice'];?>"
								data-address="<?php echo $row['address'];?>"
								data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="supplier">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Fournisseurs#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-supplier lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Responsable <i class="fa fa-sort" data-sort="respname"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="respphone"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="respemail"></i></td>
						<td>Fax <i class="fa fa-sort" data-sort="respfax"></i></td>
						<td>Note</td>
						<?php
						if(preg_match("#CA Fournisseurs#",$_SESSION['easybm_roles'])){
						?>
						<td style="text-align:right;"><?php echo number_format((float)($ca),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)($paid),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)abs($rest),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<?php
						}
						?>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> fournisseur(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> fournisseur(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}

			if($_POST['action'] == "addtva"){
				$back = $bdd->query("SELECT id FROM tvas WHERE tva='".sanitize_vars($_POST['tva'])."' AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$req = $bdd->prepare("INSERT INTO tvas(id,tva,trash) VALUES ('0','".sanitize_vars($_POST['tva'])."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE tvas SET tva='".sanitize_vars($_POST['tva'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "TVA existe déjà !!";
				}
			}

			if($_POST['action'] == "deletetva"){
				$req = $bdd->prepare("DELETE FROM tvas WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "restoretva"){
				$req = $bdd->prepare("UPDATE tvas SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletetvapermanently"){
				$req = $bdd->prepare("DELETE FROM tvas WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "loadtvas"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>TVA <i class="fa fa-sort" data-sort="title"></i></td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM tvas WHERE trash='1' ORDER BY tva";
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="tva" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo $row['tva'];?></span></td>
						<td style="position:relative;">
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier TVA#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-tva lx-open-popup" data-header="Modifier une" 
								data-id="<?php echo $row['id'];?>"
								data-tva="<?php echo $row['tva'];?>" data-title="tva">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer TVA#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-tva lx-open-popup" data-title="deleterecord1" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>TVA <i class="fa fa-sort" data-sort="title"></i></td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<?php
			}
			
			if($_POST['action'] == "addclient"){
				$back = $bdd->query("SELECT id FROM clients WHERE ((ice='".sanitize_vars($_POST['ice'])."' AND ice<>'') OR (iff='".sanitize_vars($_POST['iff'])."' AND iff<>'') OR (codecl='".sanitize_vars($_POST['codecl'])."' AND codecl<>'')) AND id NOT IN(".$_POST['id'].")");
				if($back->rowCount() == 0){
					if($_POST['id'] == "0"){
						$rand = "CL".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM clients ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "CL".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO clients(id,code,codecl,ice,iff,fullname,phone,address,email,note,company,dateadd,trash) 
						VALUES ('0','".$rand."','".sanitize_vars($_POST['codecl'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['iff'])."','".sanitize_vars(removeParentheses($_POST['fullname']))."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['address'])."','".sanitize_vars($_POST['email'])."','".addslashes($_POST['note'])."','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
					}
					else{
						$req = $bdd->prepare("UPDATE clients SET codecl='".sanitize_vars($_POST['codecl'])."',ice='".sanitize_vars($_POST['ice'])."',iff='".sanitize_vars($_POST['iff'])."',fullname='".sanitize_vars(removeParentheses($_POST['fullname']))."',phone='".sanitize_vars($_POST['phone'])."',address='".sanitize_vars($_POST['address'])."',email='".sanitize_vars($_POST['email'])."',note='".addslashes($_POST['note'])."',company='".sanitize_vars($_POST['company'])."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				else{
					echo "Code client, ICE ou IF exist déjà !!";
				}
			}

			if($_POST['action'] == "deleteclient"){
				$back = $bdd->query("SELECT id FROM documents WHERE client='".$_POST['id']."'");
				if($back->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM clients WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Nous avons detecté des documents liés à ce client, vous ne pouvez pas le supprimer !";
				}
			}
			
			if($_POST['action'] == "restoreclient"){
				$req = $bdd->prepare("UPDATE clients SET trash='1' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deleteclientpermanently"){
				$req = $bdd->prepare("DELETE FROM clients WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "loadclientslist"){
				if($_POST['category'] == "client"){
					?>
				<option value="">Choisissez un client</option>
					<?php
					$req = "";
					if($_POST['company'] != ""){
						$req .= " AND FIND_IN_SET(".$_POST['company'].",company)";
					}
					$back = $bdd->query("SELECT id,code,ice,fullname,phone,address,email FROM clients WHERE fullname<>''".$multicompanies.$req." ORDER BY fullname");
					while($row = $back->fetch()){
						?>
				<option value="<?php echo $row['id'];?>"
					data-id="<?php echo $row['id'];?>"
					data-ice="<?php echo $row['ice'];?>"
					data-name="<?php echo $row['fullname'];?>"
					data-phone="<?php echo $row['phone'];?>"
					data-address="<?php echo $row['address'];?>"
					data-email="<?php echo $row['email'];?>"><?php echo $row['fullname']." (".$row['code'].")";?></option>
						<?php 
					}
				}
				else{
					?>
				<option value="">Choisissez un client</option>
					<?php
					$req = "";
					if($_POST['company'] != ""){
						$req .= " AND FIND_IN_SET(".$_POST['company'].",company)";
					}
					$back = $bdd->query("SELECT * FROM suppliers WHERE title<>''".$multicompanies.$req." ORDER BY title");
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
				}
			}			
			
			if($_POST['action'] == "reloadclientcommandlist"){
				?>
				<option value="">Choisissez un client</option>
				<?php
				$req = "";
				if($_POST['id'] != ""){
					$req = " AND FIND_IN_SET(".$_POST['id'].",company)";
				}
				$back = $bdd->query("SELECT id,code,ice,fullname,phone,address,email,source FROM clients WHERE fullname<>''".$multicompanies.$req." ORDER BY fullname");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['id'];?>"
					data-id="<?php echo $row['id'];?>"
					data-ice="<?php echo $row['ice'];?>"
					data-name="<?php echo $row['fullname'];?>"
					data-phone="<?php echo $row['phone'];?>"
					data-address="<?php echo $row['address'];?>"
					data-email="<?php echo $row['email'];?>"
					data-source="<?php echo $row['source'];?>"><?php echo $row['fullname']." (".$row['code'].")";?></option>
					<?php 
				}
			}				
			
			if($_POST['action'] == "loadclients"){
				if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])){
					$req = "SELECT * FROM clients cl WHERE trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codecl LIKE '%".$_POST['keyword']."%' OR iff LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR ice LIKE '%".sanitize_vars($_POST['keyword'])."%' OR fullname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' OR address LIKE '%".sanitize_vars($_POST['keyword'])."%' OR email LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['paid'] == "paid"){
						$req .= " AND id IN(SELECT client FROM documents WHERE state IN('Payée','Remboursée') AND type IN('facture','avoir'))";
					}
					elseif($_POST['paid'] == "encours"){
						$req .= " AND id IN(SELECT client FROM documents WHERE state NOT IN('Payée','Remboursée') AND type IN('facture','avoir'))";
					}
					$ca = 0;
					$paid = 0;
					$rest = 0;
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						$back1 = $bdd->query("SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);				
					}
					$ca1 = $ca!=0?$ca:1;
					?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">En cours</h3>
					<p><?php echo number_format((float)(isset($rest)?$rest:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$rest*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $rest*100/$ca1?>%;background:#d9534f;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:#5cb85c;">Payé</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$paid*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $paid*100/$ca1?>%;background:#5cb85c;"></ins>
					</h4>
				</div>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#337ab7;">Chiffre d'affaires</h3>
					<p><?php echo number_format((float)(isset($ca)?$ca:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$ca*100/$ca1,2,"."," ")?>%</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $ca*100/$ca1?>%;background:#337ab7;"></ins>
					</h4>
				</div>
					<?php
				}
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Nom ét prénom <i class="fa fa-sort" data-sort="fullname"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>IF <i class="fa fa-sort" data-sort="iff"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="email"></i></td>
						<?php
						if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])){
						?>
						<td>Chiffre d'affaires <i class="fa fa-sort" data-sort="ca"></i></td>
						<td>Payé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>En cours <i class="fa fa-sort" data-sort="encours"></i></td>
						<?php
						}
						?>
						<td>Note</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM clients cl WHERE trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".$_POST['keyword']."%' OR codecl LIKE '%".$_POST['keyword']."%' OR iff LIKE '%".$_POST['keyword']."%' OR note LIKE '%".$_POST['keyword']."%' OR ice LIKE '%".sanitize_vars($_POST['keyword'])."%' OR fullname LIKE '%".sanitize_vars($_POST['keyword'])."%' OR phone LIKE '%".sanitize_vars($_POST['keyword'])."%' OR address LIKE '%".sanitize_vars($_POST['keyword'])."%' OR email LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['paid'] == "paid"){
						$req .= " AND id IN(SELECT client FROM documents WHERE state IN('Payée','Remboursée') AND type IN('facture','avoir'))";
					}
					elseif($_POST['paid'] == "encours"){
						$req .= " AND id IN(SELECT client FROM documents WHERE state NOT IN('Payée','Remboursée') AND type IN('facture','avoir'))";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "ca"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE client=cl.id),0)";
						}
						elseif($_POST['sortby'] == "paid"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE client=cl.id),0)";
						}
						elseif($_POST['sortby'] == "encours"){
							$req .= " ORDER BY COALESCE((SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE client=cl.id),0) - COALESCE((SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE client=cl.id),0)";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$ca = 0;
					$paid = 0;
					$rest = 0;
					$back = $bdd->query($req);
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="client" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td>
							<span style="white-space:nowrap;"><?php echo $row['code'];?></span>
							<?php
							if($row['codecl'] != ""){
							?>
							<br />
							Code client:
							<br />
							<span style="white-space:nowrap;font-weight:500;"><?php echo $row['codecl'];?></span>
							<?php
							}
							?>
						</td>
						<td><span><?php echo $row['fullname'];?></span></td>
						<td style="white-space:nowrap;">
							<?php
							$companytxt = "";
							$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['company']!=""?$row['company']:0).")".$companiesid);
							while($company = $back1->fetch()){
								$companytxt .= ",".$company['rs'];
								?>
							<span><?php echo $company['rs'];?></span>
								<?php
							}
							$companytxt = substr($companytxt,1);
							?>
						</td>							
						<td><span><?php echo $row['ice'];?></span></td>
						<td><span><?php echo $row['iff'];?></span></td>
						<td><span><?php echo $row['phone'];?></span></td>
						<td><span><?php echo $row['address'];?></span></td>
						<td><span><?php echo $row['email'];?></span></td>
						<?php
						if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])){
						$back1 = $bdd->query("SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca += $row1['ca'];
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['ca']!=""?$row1['ca']:"0"),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</span></td>
						<?php
						$tca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid += $row1['paid'];
						$rest += ($tca - $row1['paid']);
						?>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<td><span style="white-space:nowrap;text-align:right;"><?php echo number_format((float)($tca - $row1['paid']),2,'.',' ');?> <?php echo $settings['currency'];?>  TTC</span></td>
						<?php
						}
						?>
						<td><span><?php echo $row['note'];?></span></td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Clients#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-edit lx-edit-client lx-open-popup" data-header="Modifier un" 
								data-id="<?php echo $row['id'];?>"
								data-codecl="<?php echo $row['codecl'];?>"
								data-company="<?php echo $row['company'];?>"
								data-companytxt="<?php echo $companytxt;?>"
								data-ice="<?php echo $row['ice'];?>"
								data-iff="<?php echo $row['iff'];?>"
								data-fullname="<?php echo $row['fullname'];?>"
								data-phone="<?php echo $row['phone'];?>"
								data-address="<?php echo $row['address'];?>"
								data-email="<?php echo $row['email'];?>"
								data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="client">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Clients#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-client lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Nom ét prénom <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>ICE <i class="fa fa-sort" data-sort="ice"></i></td>
						<td>IF <i class="fa fa-sort" data-sort="iff"></i></td>
						<td>Téléphone <i class="fa fa-sort" data-sort="phone"></i></td>
						<td>Adresse <i class="fa fa-sort" data-sort="address"></i></td>
						<td>Email <i class="fa fa-sort" data-sort="email"></i></td>
						<?php
						if(preg_match("#CA Clients#",$_SESSION['easybm_roles'])){
						?>
						<td style="text-align:right;"><?php echo number_format((float)($ca),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)($paid),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<td style="text-align:right;"><?php echo number_format((float)abs($rest),2,'.',' ');?> <?php echo $settings['currency'];?> TTC</td>
						<?php
						}
						?>
						<td>Note</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> client(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> client(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}
			
			if($_POST['action'] == "loadpricerange"){
				$function = "";
				if($_POST['page'] == "facture"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='facture'".$multicompanies);
					$function = "loadFactures('1')";
				}
				if($_POST['page'] == "devis"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='devis'".$multicompanies);
					$function = "loadDevis('1')";
				}
				if($_POST['page'] == "factureproforma"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='factureproforma'".$multicompanies);
					$function = "loadFacturesProforma('1')";
				}
				if($_POST['page'] == "bl"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='bl'".$multicompanies);
					$function = "loadBL('1')";
				}
				if($_POST['page'] == "bs"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='bs'".$multicompanies);
					$function = "loadBS('1')";
				}
				if($_POST['page'] == "br"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='br'".$multicompanies);
					$function = "loadBR('1')";
				}
				if($_POST['page'] == "avoir"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='avoir'".$multicompanies);
					$function = "loadAvoirs('1')";
				}
				if($_POST['page'] == "bc"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='bc'".$multicompanies);
					$function = "loadBC('1')";
				}
				if($_POST['page'] == "bre"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM documents WHERE trash='1' AND type='bre'".$multicompanies);
					$function = "loadBRC('1')";
				}
				if($_POST['page'] == "payments"){
					$back = $bdd->query("SELECT MAX(price) mx,MIN(price) AS mn FROM payments WHERE trash='1'".$multicompanies);
					$function = "loadCaisse('1')";
				}
				$row = $back->fetch();
				?>
				<input type="text" class="js-range-slider" name="my_range" value="" />
				<input type="hidden" id="pricemin" />
				<input type="hidden" id="pricemax" />
				<a href="javascript:;" onclick="<?php echo $function;?>" class="lx-price-filter">Appliquer</a>
				<script>
				$(function() {
					$(".js-range-slider").ionRangeSlider({
						type: "double",
						min: <?php echo $row['mn']!=""?$row['mn']:0;?>,
						max: <?php echo $row['mx']!=""?$row['mx']:0;?>,
						from: <?php echo $row['mn']!=""?$row['mn']:0;?>,
						to: <?php echo $row['mx']!=""?$row['mx']:0;?>,
						grid: true,
						onChange: function (data) {
							$("#pricemin").val(data.from);
							$("#pricemax").val(data.to);
						}
					});
				});
				</script>
				<?php
			}

			if($_POST['action'] == "addcommand"){
				$randcode = $_POST['prefix'].date("ym")."-".sprintf('%06d',$_POST['code']);
				
				$back = $bdd->query("SELECT id FROM documents WHERE code='".$randcode."' AND company='".$_POST['company']."' AND id NOT IN('".$_POST['id']."')");
				if($back->rowCount() === 0){
					// Save Client
					$client = 0;
					$supplier = 0;
					if($_POST['prefix'] == "BC" OR $_POST['prefix'] == "BRC"){
						if($_POST['exist'] == "1"){
							$req = $bdd->prepare("UPDATE suppliers SET respname='".sanitize_vars($_POST['ice'])."',respphone='".sanitize_vars($_POST['phone'])."',address='".sanitize_vars($_POST['address'])."',respemail='".sanitize_vars($_POST['email'])."' WHERE id='".$_POST['client']."'");
							$req->execute();
						}
						else{
							if($_POST['fullname'] != ""){
								$rand = "FR".date("ym")."-0001";
								$back1 = $bdd->query("SELECT code FROM suppliers ORDER BY id DESC LIMIT 0,1");
								if($back1->rowCount() > 0){
									$row1 = $back1->fetch();
									$rand = "FR".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
								}
								$req = $bdd->prepare("INSERT INTO suppliers(id,code,title,respname,respphone,respemail,respfax,ice,address,note,company,dateadd,trash) VALUES ('0','".$rand."','".sanitize_vars($_POST['fullname'])."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['email'])."','','','".sanitize_vars($_POST['address'])."','','".sanitize_vars($_POST['company'])."','".time()."','1')");
								$req->execute();
								$back = $bdd->query("SELECT id FROM suppliers WHERE respname='".$_POST['ice']."' AND respphone='".$_POST['phone']."' ORDER BY id DESC LIMIT 0,1");
								$row = $back->fetch();
								$_POST['client'] = $row['id'];
							}
						}
						$supplier = $_POST['client'];						
					}
					else{
						if($_POST['exist'] == "1"){
							$req = $bdd->prepare("UPDATE clients SET ice='".sanitize_vars($_POST['ice'])."',fullname='".sanitize_vars($_POST['fullname'])."',phone='".sanitize_vars($_POST['phone'])."',address='".sanitize_vars($_POST['address'])."',email='".sanitize_vars($_POST['email'])."' WHERE id='".$_POST['client']."'");
							$req->execute();
						}
						else{
							if($_POST['fullname'] != ""){
								$rand = "CL".date("ym")."-0001";
								$back1 = $bdd->query("SELECT code FROM clients ORDER BY id DESC LIMIT 0,1");
								if($back1->rowCount() > 0){
									$row1 = $back1->fetch();
									$rand = "CL".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
								}
								$req = $bdd->prepare("INSERT INTO clients(id,code,ice,fullname,phone,address,email,company,dateadd,trash) VALUES ('0','".$rand."','".sanitize_vars($_POST['ice'])."','".sanitize_vars($_POST['fullname'])."','".sanitize_vars($_POST['phone'])."','".sanitize_vars($_POST['address'])."','".sanitize_vars($_POST['email'])."','".sanitize_vars($_POST['company'])."','".time()."','1')");
								$req->execute();
								$back = $bdd->query("SELECT id FROM clients WHERE fullname='".$_POST['fullname']."' AND phone='".$_POST['phone']."' ORDER BY id DESC LIMIT 0,1");
								$row = $back->fetch();
								$_POST['client'] = $row['id'];
							}
						}
						$client = $_POST['client'];
					}
						
					$dateorder = getDateNow($_POST['dateadd']);
					$commandid = $_POST['id'];
					if($_POST['id'] == "0" OR $_POST['id'] == "-1"){
						if($_POST['type'] == "avoir" OR $_POST['type'] == "br"){
							$paid = "Non remboursée";
							if($_POST['cash'] != "" AND $_POST['cash'] != "0"){
								$paid = "Partiellement remboursée";
								if($_POST['rest'] == "0"){
									$paid = "Remboursée";
								}
							}				
						}
						else{
							$paid = "Non payée";
							if($_POST['cash'] != "" AND $_POST['cash'] != "0"){
								$paid = "Partiellement payée";
								if($_POST['rest'] == "0"){
									$paid = "Payée";
								}
							}							
						}
						$back = $bdd->query("SELECT * FROM infodocs WHERE company='".$_POST['company']."' AND document='".$_POST['type']."'");
						$extrainfos = $back->fetch();
						if(addslashes($_POST['modepayment']) == "" OR addslashes($_POST['conditions']) == "" OR addslashes($_POST['abovetable'])){
							$_POST['modepayment'] = $extrainfos['modepayment'];
							$_POST['conditions'] = $extrainfos['conditions'];
							$_POST['abovetable'] = $extrainfos['abovetable'];
						}
						$req = $bdd->prepare("INSERT INTO `documents`(`id`, `code`, `price`, `state`, `type`, `category`, `client`, `supplier`, `company`, `note`, `correctdoc`, `modepayment`, `conditions`, `abovetable`, `user`, `dateadd`, `trash`) 
						VALUES ('0','".$randcode."','0','".$paid."','".$_POST['type']."','".$_POST['category']."','".$client."','".$supplier."','".$_POST['company']."','".addslashes($_POST['note'])."','".sanitize_vars($_POST['correctdoc'])."','".addslashes($_POST['modepayment'])."','".addslashes($_POST['conditions'])."','".addslashes($_POST['abovetable'])."','".$_SESSION['easybm_id']."','".$dateorder."','1')");
						$req->execute();
						$back = $bdd->query("SELECT id FROM documents ORDER BY id DESC LIMIT 0,1");
						$command = $back->fetch();
						$commandid = $command['id'];
						
						if($_POST['cash'] != "" AND $_POST['cash'] != "0"){
							$type = "Sortie";
							if($_POST['type'] == "facture"){
								$type = "Entrée";
							}	

							$rand = substr($type,0,1).date("ym")."-000001";
							$back = $bdd->query("SELECT code,dateadd FROM payments WHERE code LIKE '".substr($rand,0,1)."%' AND company='".$_POST['company']."' ORDER BY id DESC LIMIT 0,1");
							if($back->rowCount() > 0){
								$row1 = $back->fetch();
								if(date("Y") == date("Y",$row1['dateadd'])){
									$rand = substr($rand,0,1).date("ym")."-".sprintf('%06d',intval(substr($row1['code'],6))+1);
								}
							}

							$title = "Paiement facture N° ".$randcode;
							if($_POST['type'] == "avoir"){
								$title = "Remboursement facture avoir N° ".$randcode;
							}
							elseif($_POST['type'] == "bc"){
								$title = "Paiement bon de commande N° ".$randcode;
							}

							$req = $bdd->prepare("INSERT INTO `payments`(`id`, `code`, `doc`, `client`, `supplier`, `type`, `title`, `price`, `modepayment`, `imputation`, `rib`, `paid`, `remis`, `dateremis`, `company`, `note`, `typedoc`, `category`, `dateadd`, `datedue`, `datepaid`, `user`, `trash`) 
							VALUES ('0','".$rand."','".$commandid."','".$client."','".$supplier."','".$type."','".$title."','".$_POST['cash']."','".$_POST['modepayment']."','".sanitize_vars($_POST['imputation'])."','".$_POST['rib']."','0','0','','".$_POST['company']."','','".$_POST['type']."','".$_POST['category']."','".$dateorder."','','','".$_SESSION['easybm_id']."','1')");
							$req->execute();	
						}
					}
					else{
						$req = $bdd->prepare("UPDATE documents SET 
																client='".sanitize_vars($_POST['client'])."',
																company='".sanitize_vars($_POST['company'])."',
																note='".addslashes($_POST['note'])."',
																correctdoc='".sanitize_vars($_POST['correctdoc'])."',
																user='".sanitize_vars($_SESSION['easybm_id'])."',
																dateadd='".$dateorder."' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
					
					// Save Order
					$products = explode(",",$_POST['products']);
					$qtys = explode(",",$_POST['qtys']);
					$oqtys = explode(",",$_POST['qtys']);
					if($_POST['id'] == "-1"){
						$qtys = explode(",",$_POST['qtyback']);
					}
					$units = explode(",",$_POST['units']);
					$uprices = explode(",",$_POST['uprices']);
					$tprices = explode(",",$_POST['tprices']);
					$tvas = explode(",",$_POST['tvas']);
					$discounts = explode(",",$_POST['discounts']);
					
					$tprice = 0;
					$req = $bdd->prepare("DELETE FROM detailsdocuments WHERE doc='".$commandid."' AND typedoc='".$_POST['type']."' AND category='".$_POST['category']."'");
					$req->execute();
					for($i=1;$i<count($products);$i++){
						if($qtys[$i] != 0){
							if($_POST['id'] == "-1"){
								$tprices[$i] = number_format((float)(($tprices[$i] / $oqtys[$i]) * $qtys[$i]),2,".","");
							}						
							$req = $bdd->prepare("INSERT INTO `detailsdocuments`(`id`, `doc`, `title`, `unit`, `qty`, `uprice`, `tva`, `discounttype`, `discount`, `tprice`, `client`, `supplier`, `company`, `category`, `typedoc`, `dateadd`, `trash`) 
							VALUES ('0','".$commandid."','".addslashes($products[$i])."','".$units[$i]."','".$qtys[$i]."','".$uprices[$i]."','".$tvas[$i]."','".$_POST['discounttype']."','".$discounts[$i]."','".$tprices[$i]."','".$client."','".$supplier."','".$_POST['company']."','".$_POST['category']."','".$_POST['type']."','".$dateorder."','1')");
							$req->execute();
							$tprice += $tprices[$i] * (1+($tvas[$i]/100));
						}
					}
					
					$req = $bdd->prepare("UPDATE documents SET price='".number_format((float)$tprice,2,".","")."' WHERE id='".$commandid."'");
					$req->execute();
				}
				else{
					echo "Numéro de facture exist déjà";
				}
			}

			if($_POST['action'] == "addpayment"){
				if($_POST['cash'] != "0"){
					$back = $bdd->query("SELECT * FROM documents d WHERE id='".$_POST['id']."'");
					$document = $back->fetch();
					
					$type = "Sortie";
					if($_POST['type'] == "facture"){
						$type = "Entrée";
					}	

					$rand = substr($type,0,1).date("ym")."-000001";
					$back = $bdd->query("SELECT code,dateadd FROM payments WHERE code LIKE '".substr($rand,0,1)."%' AND company='".$document['company']."' ORDER BY id DESC LIMIT 0,1");
					if($back->rowCount() > 0){
						$row1 = $back->fetch();
						if(date("Y") == date("Y",$row1['dateadd'])){
							$rand = substr($rand,0,1).date("ym")."-".sprintf('%06d',intval(substr($row1['code'],6))+1);
						}
					}
					
					$title = "Paiement facture N° ".$document['code'];
					if($_POST['type'] == "avoir"){
						$title = "Remboursement facture avoir N° ".$document['code'];
					}
					elseif($_POST['type'] == "bc"){
						$title = "Paiement bon de commande N° ".$document['code'];
					}
					
					$datedue = "";
					$datepaid = "";
					if($_POST['modepayment'] == "Espèce"){
						$datedue = time();
						$datepaid = "";
					}
					elseif($_POST['modepayment'] == "Virement" OR $_POST['modepayment'] == "TPE"){
						$datedue = time();
						$datepaid = time();					
					}
					else{
						if($_POST['datedue'] != ""){
							$datedue = getDateNow($_POST['datedue']);
						}
						if($_POST['datepaid'] != ""){
							$datepaid = getDateNow($_POST['datepaid']);
						}					
					}
					
					$req = $bdd->prepare("INSERT INTO `payments`(`id`, `code`, `doc`, `client`, `supplier`, `type`, `title`, `price`, `modepayment`, `imputation`, `rib`, `paid`, `remis`, `dateremis`, `company`, `note`, `typedoc`, `category`, `dateadd`, `datedue`, `datepaid`, `user`, `trash`) 
					VALUES ('0', '".$rand."','".$_POST['id']."','".$document['client']."','".$document['supplier']."','".$type."','".$title."','".$_POST['cash']."','".$_POST['modepayment']."','".sanitize_vars($_POST['imputation'])."','".$_POST['rib']."','0','0','','".$document['company']."','".addslashes($_POST['note'])."','".$_POST['type']."','".$_POST['category']."','".time()."','".$datedue."','".$datepaid."','".$_SESSION['easybm_id']."','1')");
					$req->execute();
					
					$back = $bdd->query("SELECT SUM(price) AS sm FROM payments WHERE doc='".$_POST['id']."'");
					$payment = $back->fetch();
					
					if($document['type'] == "avoir" OR $document['type'] == "br"){
						$state = "Non remboursée";
						if($payment['sm'] > 0){
							if($document['price'] - $payment['sm'] > 0){
								$state = "Partiellement remboursée";
							}
							else{
								$state = "Remboursée";
							}
						}					
					}
					else{
						$state = "Non payée";
						if($payment['sm'] > 0){
							if($document['price'] - $payment['sm'] > 0){
								$state = "Partiellement payée";
							}
							else{
								$state = "Payée";
							}
						}					
					}
					
					$req = $bdd->prepare("UPDATE documents SET state='".$state."' WHERE id='".$_POST['id']."'");
					$req->execute();
				}
			}
			
			if($_POST['action'] == "deletepaymenthistory"){
				$back = $bdd->query("SELECT doc,attachments FROM payments WHERE id='".$_POST['id']."'");
				$row = $back->fetch();

				$req = $bdd->prepare("DELETE FROM payments WHERE id='".$_POST['id']."'");
				$req->execute();
				
				$back = $bdd->query("SELECT * FROM documents d WHERE id='".$row['doc']."'");
				$document = $back->fetch();

				$back = $bdd->query("SELECT SUM(price) AS sm FROM payments WHERE doc='".$row['doc']."'");
				$payment = $back->fetch();
				
				if($document['type'] == "avoir" OR $document['type'] == "br"){
					$state = "Non remboursée";
					if($payment['sm'] > 0){
						if($document['price'] - $payment['sm'] > 0){
							$state = "Partiellement remboursée";
						}
						else{
							$state = "Remboursée";
						}
					}					
				}
				else{
					$state = "Non payée";
					if($payment['sm'] > 0){
						if($document['price'] - $payment['sm'] > 0){
							$state = "Partiellement payée";
						}
						else{
							$state = "Payée";
						}
					}					
				}
				
				$req = $bdd->prepare("UPDATE documents SET state='".$state."' WHERE id='".$row['doc']."'");
				$req->execute();
				
				$attachments = explode(",",$row['attachments']);
				for($i=1;$i<count($attachments);$i++){
					if(file_exists("uploads/".$attachments[$i])){
						unlink("uploads/".$attachments[$i]);
					}						
				}
			}
			
			if($_POST['action'] == "deletefiles"){
				$files = str_replace(",".$_POST['file'],"",$_POST['files']);
				$files = str_replace($_POST['file'],"",$files);
				$req = $bdd->prepare("UPDATE ".$_POST['table']." SET attachments='".$files."' WHERE id='".$_POST['id']."'");
				$req->execute();
				if(file_exists("uploads/".$_POST['file'])){
					unlink("uploads/".$_POST['file']);
				}
			}
			
			if($_POST['action'] == "loadproductslist"){
				?>
				<option value="">Saisissez ou choisissez un produit / service</option>
				<?php
				$req = "";
				if($_POST['company'] != ""){
					$req .= " AND company='".$_POST['company']."'";
				}
				$back = $bdd->query("SELECT DISTINCT title,unit FROM detailsdocuments WHERE title<>'' AND trash='1'".$req.$multicompanies." ORDER BY title");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['title'];?>" data-unit="<?php echo $row['unit'];?>"><?php echo $row['title'];?></option>
					<?php
				}
			}

			if($_POST['action'] == "loadunitslist"){
				?>
				<option value="">Saisissez une unité</option>
				<?php
				$req = "";
				if($_POST['company'] != ""){
					$req .= " AND company='".$_POST['company']."'";
				}
				$back = $bdd->query("SELECT DISTINCT unit FROM detailsdocuments WHERE unit<>'' AND trash='1'".$req.$multicompanies." ORDER BY unit");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['unit'];?>"><?php echo $row['unit'];?></option>
					<?php
				}
			}

			if($_POST['action'] == "loadfactureslist"){
				?>
				<option value="">Saisissez ou choisissez un numéro facture</option>
				<?php
				$req = "";
				if($_POST['company'] != ""){
					$req .= " AND company='".$_POST['company']."'";
				}
				$back = $bdd->query("SELECT code FROM documents WHERE trash='1' AND type='facture'".$req.$multicompanies." ORDER BY code");
				while($row = $back->fetch()){
					?>
				<option value="<?php echo $row['code'];?>"><?php echo $row['code'];?></option>
					<?php
				}
			}

			if($_POST['action'] == "loadcode"){
				$back1 = $bdd->query("SELECT ".$_POST['type']." FROM companies WHERE id='".$_POST['company']."'");
				$row1 = $back1->fetch();
				$code = sprintf('%06d',$row1[$_POST['type']]);
				$back1 = $bdd->query("SELECT code,dateadd FROM documents WHERE company='".$_POST['company']."' AND type='".$_POST['type']."' ORDER BY id DESC LIMIT 0,1");
				if($back1->rowCount() > 0){
					$row1 = $back1->fetch();
					$code = "000001";
					if(date("Y") == date("Y",$row1['dateadd'])){
						$code = sprintf('%06d',intval(substr($row1['code'],($_POST['type']=="bre"?8:7)))+1);
					}
				}
				echo $code;
			}

			if($_POST['action'] == "deletedocument"){
				$back = $bdd->query("SELECT code,company FROM documents WHERE id='".$_POST['id']."'");
				$row = $back->fetch();
				
				$back1 = $bdd->query("SELECT id FROM documents WHERE correctdoc='".$row['code']."' AND company='".$row['company']."'");
				if($back1->rowCount() == 0){
					$req = $bdd->prepare("DELETE FROM payments WHERE doc='".$_POST['id']."'");
					$req->execute();
					$req = $bdd->prepare("DELETE FROM detailsdocuments WHERE doc='".$_POST['id']."'");
					$req->execute();
					$req = $bdd->prepare("DELETE FROM documents WHERE id='".$_POST['id']."'");
					$req->execute();
				}
				else{
					echo "Vous ne pouvez pas supprimer une facture liée à des avoirs / bons de retour";
				}
			}		

			if($_POST['action'] == "editextrainfo"){
				$req = $bdd->prepare("UPDATE documents SET modepayment='".addslashes($_POST['modepayment'])."',conditions='".addslashes($_POST['conditions'])."',abovetable='".addslashes($_POST['abovetable'])."' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "loadfactures"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Factures avoir / Bons de retour N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails facture</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des paiements <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='facture' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['statee'] != ""){
						$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}
					if($_POST['avoir'] == "1"){
						$req .= " AND code IN(SELECT correctdoc FROM documents WHERE company=d.company)";
					}
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="facture" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printfacture.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<td>
							<?php
							$smavoir = 0;
							$back1 = $bdd->query("SELECT id,code,type,price FROM documents WHERE type='avoir' AND correctdoc='".$row['code']."' AND company='".$row['company']."'");
							if($back1->rowCount() > 0){
							?>
							<span>Avoirs:</span>
							<?php
							}
							while($row1 = $back1->fetch()){
								?>
							<ins style="white-space:nowrap;"><a href="javascript:;" data-href="print<?php echo $row1['type'];?>.php?id=<?php echo $row1['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="color:#fb8500;white-space:nowrap;"><?php echo $row1['code'];?></a> (<?php echo number_format((float)$row1['price'],2,"."," ")." ".$settings['currency'];?> TTC)<ins><br />
								<?php
								$back2 = $bdd->query("SELECT SUM(price) AS sm FROM payments WHERE doc='".$row1['id']."'");
								$row2 = $back2->fetch();
								$smavoir += ($row1['price'] - $row2['sm']);
							}						
							?>						
							<?php
							$back1 = $bdd->query("SELECT id,code,type,price FROM documents WHERE type='br' AND correctdoc='".$row['code']."' AND company='".$row['company']."'");
							if($back1->rowCount() > 0){
							?>
							<br /><span>Bons de retour:</span>
							<?php
							}
							while($row1 = $back1->fetch()){
								?>
							<ins style="white-space:nowrap;"><a href="javascript:;" data-href="print<?php echo $row1['type'];?>.php?id=<?php echo $row1['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="color:#fb8500;white-space:nowrap;"><?php echo $row1['code'];?></a> (<?php echo number_format((float)$row1['price'],2,"."," ")." ".$settings['currency'];?> TTC)<ins><br />
								<?php
							}						
							?>	
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='facture' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td>
							<?php
							$smpaid = 0;
							$phtable = "";
							$back1 = $bdd->query("SELECT * FROM payments WHERE doc='".$row['id']."'");
							if($back1->rowCount() > 0){
								$phtable .= '<table><tr class="lx-first-tr">';
								$phtable .= '<td>Montant payé</td>';
								$phtable .= '<td>Mode de paiement</td>';
								$phtable .= '<td>Imputation</td>';
								$phtable .= '<td>Compte banquaire</td>';
								$phtable .= '<td>Note</td>';
								$phtable .= '<td>Date</td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>Supprimer</td>';
								}
								$phtable .= '</tr>';
							}
							while($row1 = $back1->fetch()){
								$back2 = $bdd->query("SELECT * FROM bankaccounts WHERE id='".$row1['rib']."'");
								$row2 = $back2->fetch();
								$smpaid += $row1['price'];
								$phtable .= '<tr>';
								$phtable .= '<td><span>'.$row1['price'].' DH</span></td>';
								$phtable .= '<td><span>'.$row1['modepayment'].'</span></td>';
								$phtable .= '<td><span>'.$row1['imputation'].'</span></td>';
								$phtable .= '<td><span>'.$row2['bank'].':'.$row2['rib'].'</span></td>';
								$phtable .= '<td><span>'.$row1['note'].'</span></td>';
								$phtable .= '<td><span>'.($row1['dateadd']!=""?date("d/m/Y H:i",$row1['dateadd']):"").'</span></td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>
													<a href="javascript:;" class="lx-delete lx-delete-payment"
														data-id="'.$row['id'].'"
														data-company="'.$row['company'].'"
														data-modepayment="'.$row1['modepayment'].'"
														data-rib="'.$row1['rib'].'"
														data-imputation="'.$row1['imputation'].'"
														data-libelle="Suppression historique paiement facture N° '.$row['code'].'"
														data-type="Entrée"
														data-price="'.$row1['price'].'">Supprimer</a>
													<div class="lx-delete-payment-choice">
														<a href="javascript:;" class="lx-yes-delete-payment" data-paid="'.$row1['price'].'" data-id="'.$row1['id'].'">Oui</a>
														<a href="javascript:;" class="lx-no-delete-payment">Non</a>
													</div>
												</td>';
								}
								$phtable .= '</tr>';
							}
							if($back1->rowCount() > 0){
								$phtable .= '</table>';
							}
														
							$bankaccounts = "<option value=''>Choisissez un compte banquaire</option>";
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE company='".$row['company']."'");
							while($row1 = $back1->fetch()){
								$bankaccounts .= "<option value='".$row1['id']."'>".$row1['bank']." | ".$row1['rib']."</option>";
							}
							?>
							<span style="display:inline-block;padding:2px 5px;font-weight:500;background:<?php echo $row['state']=='Payée'?"#71b44c":($row['state']=='Non payée'?"#CC0000":"orange");?>;color:#FFFFFF;border-radius:4px;cursor:pointer;" 
								data-id="<?php echo $row['id'];?>" 
								data-paid="<?php echo $row['state'];?>" 
								data-rest="<?php echo number_format((float)($price2-$smpaid-$smavoir),2,".","");?>" 
								data-price="<?php echo number_format((float)abs($price2),2,".","");?>" 
								data-company="<?php echo $company;?>" 
								data-bankaccounts="<?php echo $bankaccounts;?>" 
								data-table="<?php echo str_replace("\"","'",$phtable);?>" class="<?php echo preg_match("#Modification statut de paiement#",$_SESSION['easybm_roles'])?"lx-edit-payment lx-open-popup":"";?>" 
								data-title="payment"><?php echo $row['state'];?></span>
							<span>Reste: <b><?php echo number_format((float)($price2-$smpaid-$smavoir),2,"."," ")." ".$settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printfacture.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Factures#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								if(preg_match("#Ajouter Factures avoir#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" style="color:#FFA500;" data-table="commands" data-header="Avoir / bon de retour" 
									data-id="-1"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['code'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Avoir / bon de retour</a>
									<?php
								}	
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Factures#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Factures avoir / Bons de retour N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails facture</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des paiements <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<?php
				$req1 = "SELECT COUNT(id) AS nb FROM documents c WHERE trash='1' AND type='facture'".$multicompanies;
				$req = "";
				if($_POST['keyword'] != ""){
					$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
				}
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
						}
					}
					$req .= ")";
				}
				if($_POST['client'] != ""){
					$req .= " AND client IN(".$_POST['client'].")";
				}
				if($_POST['user'] != ""){
					$req .= " AND user IN(".$_POST['user'].")";
				}
				if($_POST['product'] != ""){
					$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
				}				
				if($_POST['statee'] != ""){
					$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
				}
				if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
					$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
				}					
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
					$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				}
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$nbcmds = 0.1;
				if($row['nb'] != 0){
					$nbcmds = $row['nb'];
				}
				
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Non payée' AND type='facture' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$unpaid = $row['sm'];
				$nbunpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">Non payée</h3>
					<p><?php echo number_format((float)(isset($unpaid)?$unpaid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbunpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbunpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbunpaid*100/$nbcmds?>%;background:#d9534f;">
						</ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT (SELECT SUM(price) FROM payments WHERE doc=d.id) AS sm,COUNT(id) AS nb FROM documents d WHERE state='Partiellement payée' AND type='facture' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$partiel = $row['sm'];					
				$nbpartiel = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:orange;">Partiellement payée</h3>
					<p><?php echo number_format((float)(isset($partiel)?$partiel:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpartiel*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpartiel . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpartiel*100/$nbcmds?>%;background:orange;"></ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Payée' AND type='facture' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$paid = $row['sm'];
				$nbpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#5cb85c;">Payée</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpaid*100/$nbcmds?>%;background:#5cb85c;">
						</ins>
					</h4>
				</div>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> facture(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> facture(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}
			
			if($_POST['action'] == "loadavoirs"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Corrige la facture N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails avoir</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des remboursement <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='avoir' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['statee'] != ""){
						$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="avoir" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printavoir.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<td>
							<?php
							$facturepaid = 0;
							$back1 = $bdd->query("SELECT id,code,price FROM documents WHERE code='".$row['correctdoc']."' AND company='".$row['company']."'");
							if($back1->rowCount() > 0){
								$row1 = $back1->fetch();
								?>
							<a href="javascript:;" data-href="printfacture.php?id=<?php echo $row1['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['correctdoc'];?></a>
								<?php
								$back2 = $bdd->query("SELECT SUM(price) AS sm FROM payments WHERE doc='".$row1['id']."'");
								$row2 = $back2->fetch();
								$back3 = $bdd->query("SELECT SUM(price) AS sm FROM documents WHERE correctdoc='".$row1['code']."' AND code<>'".$row['code']."' AND company='".$row['company']."'");
								$row3 = $back3->fetch();
								$facturepaid += ($row1['price'] - $row3['sm'] - $row2['sm']);
							}
							else{
								?>
							<span><?php echo $row['correctdoc'];?></span>
								<?php								
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='avoir' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>						
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td>
							<?php
							$smpaid = 0;
							$phtable = "";
							$back1 = $bdd->query("SELECT * FROM payments WHERE doc='".$row['id']."'");
							if($back1->rowCount() > 0){
								$phtable .= '<table><tr class="lx-first-tr">';
								$phtable .= '<td>Montant payé</td>';
								$phtable .= '<td>Mode de paiement</td>';
								$phtable .= '<td>Imputation</td>';
								$phtable .= '<td>Compte banquaire</td>';
								$phtable .= '<td>Note</td>';
								$phtable .= '<td>Date</td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>Supprimer</td>';
								}
								$phtable .= '</tr>';
							}
							while($row1 = $back1->fetch()){
								$back2 = $bdd->query("SELECT * FROM bankaccounts WHERE id='".$row1['rib']."'");
								$row2 = $back2->fetch();
								$smpaid += $row1['price'];
								$phtable .= '<tr>';
								$phtable .= '<td><span>'.$row1['price'].' DH</span></td>';
								$phtable .= '<td><span>'.$row1['modepayment'].'</span></td>';
								$phtable .= '<td><span>'.$row1['imputation'].'</span></td>';
								$phtable .= '<td><span>'.$row2['bank'].':'.$row2['rib'].'</span></td>';
								$phtable .= '<td><span>'.$row1['note'].'</span></td>';
								$phtable .= '<td><span>'.($row1['dateadd']!=""?date("d/m/Y H:i",$row1['dateadd']):"").'</span></td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>
													<a href="javascript:;" class="lx-delete lx-delete-payment"
														data-id="'.$row['id'].'"
														data-company="'.$row['company'].'"
														data-modepayment="'.$row1['modepayment'].'"
														data-rib="'.$row1['rib'].'"
														data-imputation="'.$row1['imputation'].'"
														data-libelle="Suppression historique remboursement facture avoir N° '.$row['code'].'"
														data-type="Entrée"
														data-price="'.$row1['price'].'">Supprimer</a>
													<div class="lx-delete-payment-choice">
														<a href="javascript:;" class="lx-yes-delete-payment" data-paid="'.$row1['price'].'" data-id="'.$row1['id'].'">Oui</a>
														<a href="javascript:;" class="lx-no-delete-payment">Non</a>
													</div>
												</td>';
								}
								$phtable .= '</tr>';
							}
							if($back1->rowCount() > 0){
								$phtable .= '</table>';
							}
							
							$bankaccounts = "<option value=''>Choisissez un compte banquaire</option>";
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE company='".$row['company']."'");
							while($row1 = $back1->fetch()){
								$bankaccounts .= "<option value='".$row1['id']."'>".$row1['bank']." | ".$row1['rib']."</option>";
							}
							?>
							<span style="display:inline-block;padding:2px 5px;font-weight:500;background:<?php echo $row['state']=='Remboursée'?"#71b44c":($row['state']=='Non remboursée'?"#CC0000":"orange");?>;color:#FFFFFF;border-radius:4px;cursor:pointer;" 
								data-id="<?php echo $row['id'];?>" 
								data-paid="<?php echo $row['state'];?>" 
								data-rest="<?php echo number_format((float)abs($price2-$smpaid-$facturepaid),2,".","");?>" 
								data-price="<?php echo number_format((float)abs($price2),2,".","");?>" 
								data-company="<?php echo $company;?>" 
								data-bankaccounts="<?php echo $bankaccounts;?>" 
								data-table="<?php echo str_replace("\"","'",$phtable);?>" class="<?php echo preg_match("#Modification statut de paiement#",$_SESSION['easybm_roles'])?"lx-edit-payment lx-open-popup":"";?>" 
								data-title="payment"><?php echo $row['state'];?></span>
							<span>Reste: <b><?php echo number_format((float)abs($price2-$smpaid-$facturepaid),2,"."," ")." ".$settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de remboursement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printfacture.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Factures avoir#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Factures avoir#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Corrige la facture N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails facture avoir</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des remboursements <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<?php
				$req1 = "SELECT COUNT(id) AS nb FROM documents c WHERE trash='1' AND type='avoir'".$multicompanies;
				$req = "";
				if($_POST['keyword'] != ""){
					$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
				}
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
						}
					}
					$req .= ")";
				}
				if($_POST['client'] != ""){
					$req .= " AND client IN(".$_POST['client'].")";
				}
				if($_POST['user'] != ""){
					$req .= " AND user IN(".$_POST['user'].")";
				}
				if($_POST['product'] != ""){
					$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
				}				
				if($_POST['statee'] != ""){
					$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
				}
				if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
					$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
				}					
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
					$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				}
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$nbcmds = 0.1;
				if($row['nb'] != 0){
					$nbcmds = $row['nb'];
				}
				
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Non remboursée' AND type='avoir' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$unpaid = $row['sm'];
				$nbunpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">Non remboursée</h3>
					<p><?php echo number_format((float)(isset($unpaid)?$unpaid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbunpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbunpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbunpaid*100/$nbcmds?>%;background:#d9534f;">
						</ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT (SELECT SUM(price) FROM payments WHERE doc=d.id) AS sm,COUNT(id) AS nb FROM documents d WHERE state='Partiellement remboursée' AND type='avoir' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$partiel = $row['sm'];					
				$nbpartiel = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:orange;">Partiellement remboursée</h3>
					<p><?php echo number_format((float)(isset($partiel)?$partiel:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpartiel*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpartiel . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpartiel*100/$nbcmds?>%;background:orange;"></ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Remboursée' AND type='avoir' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$paid = $row['sm'];
				$nbpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#5cb85c;">Remboursée</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpaid*100/$nbcmds?>%;background:#5cb85c;">
						</ins>
					</h4>
				</div>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> facture(s) avoir de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> facture(s) avoir de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}		

			if($_POST['action'] == "loadbr"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Corrige la facture N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de retour</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='br' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="avoir" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printbr.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<td>
							<?php
							$back1 = $bdd->query("SELECT id,code FROM documents WHERE code='".$row['correctdoc']."' AND company='".$row['company']."'");
							if($back1->rowCount() > 0){
								$row1 = $back1->fetch();
								?>
							<a href="javascript:;" data-href="printfacture.php?id=<?php echo $row1['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['correctdoc'];?></a>
								<?php
							}
							else{
								?>
							<span><?php echo $row['correctdoc'];?></span>
								<?php								
							}
							?>						
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='br' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>						
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printfacture.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Factures avoir#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Factures avoir#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
					</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Corrige la facture N°</td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de retour</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> facture(s) avoir de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> facture(s) avoir de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}		

			if($_POST['action'] == "loaddevis"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails devis</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='devis' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="devis" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printdevis.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='devis' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printdevis.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Devis#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Devis#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails devis</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> devis(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> devis(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}			

			if($_POST['action'] == "loadfacturesproforma"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails facture proforma</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='factureproforma' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="factureproforma" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printfacturesproforma.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='factureproforma' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printfacturesproforma.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Factures proforma#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Factures proforma#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails facture proforma</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> facture(s) proforma de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> facture(s) proforma de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}	

			if($_POST['action'] == "loadbl"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de livraison</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='bl' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="bl" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printbl.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='bl' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printbl.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Bons de livraison#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Bons de livraison#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de livraison</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> bon(s) de livraison de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> bon(s) de livraison de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}
			
			if($_POST['action'] == "loadbs"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de sortie</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='client' AND type='bs' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN(".$_POST['client'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="bs" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printbs.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<?php
						$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
						<td>
							<span style="white-space:nowrap;"><?php echo $row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='bs' AND category='client' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printbl.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Bons de sortie#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['client'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Bons de sortie#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de sortie</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> bon(s) de sortie de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> bon(s) de sortie de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}			
			
			if($_POST['action'] == "loadbc"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de commande</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des paiements <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='supplier' AND type='bc' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND supplier IN(".$_POST['supplier'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}				
					if($_POST['statee'] != ""){
						$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="bc" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printbc.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<td>
							<?php
							$back1 = $bdd->query("SELECT title,code,codefo FROM suppliers WHERE id='".$row['supplier']."'");
							$row1 = $back1->fetch();
							?>
							<span style="white-space:nowrap;"><?php echo $row1['title']!=""?$row1['title']." (".($row1['codefo']!=""?$row1['codefo']:$row1['code']).")":"";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='bc' AND category='supplier' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td>
							<?php
							$smpaid = 0;
							$phtable = "";
							$back1 = $bdd->query("SELECT * FROM payments WHERE doc='".$row['id']."'");
							if($back1->rowCount() > 0){
								$phtable .= '<table><tr class="lx-first-tr">';
								$phtable .= '<td>Montant payé</td>';
								$phtable .= '<td>Mode de paiement</td>';
								$phtable .= '<td>Imputation</td>';
								$phtable .= '<td>Compte banquaire</td>';
								$phtable .= '<td>Note</td>';
								$phtable .= '<td>Date</td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>Supprimer</td>';
								}
								$phtable .= '</tr>';
							}
							while($row1 = $back1->fetch()){
								$back2 = $bdd->query("SELECT * FROM bankaccounts WHERE id='".$row1['rib']."'");
								$row2 = $back2->fetch();
								$smpaid += $row1['price'];
								$phtable .= '<tr>';
								$phtable .= '<td><span>'.$row1['price'].' DH</span></td>';
								$phtable .= '<td><span>'.$row1['modepayment'].'</span></td>';
								$phtable .= '<td><span>'.$row1['imputation'].'</span></td>';
								$phtable .= '<td><span>'.$row2['bank'].':'.$row2['rib'].'</span></td>';
								$phtable .= '<td><span>'.$row1['note'].'</span></td>';
								$phtable .= '<td><span>'.($row1['dateadd']!=""?date("d/m/Y H:i",$row1['dateadd']):"").'</span></td>';
								if(preg_match("#Suppression historique de paiement#",$_SESSION['easybm_roles'])){
									$phtable .= '<td>
													<a href="javascript:;" class="lx-delete lx-delete-payment"
														data-id="'.$row['id'].'"
														data-company="'.$row['company'].'"
														data-modepayment="'.$row1['modepayment'].'"
														data-rib="'.$row1['rib'].'"
														data-imputation="'.$row1['imputation'].'"
														data-libelle="Suppression historique paiement bon de commande N° '.$row['code'].'"
														data-type="Entrée"
														data-price="'.$row1['price'].'">Supprimer</a>
													<div class="lx-delete-payment-choice">
														<a href="javascript:;" class="lx-yes-delete-payment" data-paid="'.$row1['price'].'" data-id="'.$row1['id'].'">Oui</a>
														<a href="javascript:;" class="lx-no-delete-payment">Non</a>
													</div>
												</td>';
								}
								$phtable .= '</tr>';
							}
							if($back1->rowCount() > 0){
								$phtable .= '</table>';
							}
							
							$bankaccounts = "<option value=''>Choisissez un compte banquaire</option>";
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE company='".$row['company']."'");
							while($row1 = $back1->fetch()){
								$bankaccounts .= "<option value='".$row1['id']."'>".$row1['bank']." | ".$row1['rib']."</option>";
							}
							?>
							<span style="display:inline-block;padding:2px 5px;font-weight:500;background:<?php echo $row['state']=='Payée'?"#71b44c":($row['state']=='Non payée'?"#CC0000":"orange");?>;color:#FFFFFF;border-radius:4px;cursor:pointer;" 
								data-id="<?php echo $row['id'];?>" 
								data-paid="<?php echo $row['state'];?>" 
								data-rest="<?php echo number_format((float)abs($price2-$smpaid),2,".","");?>" 
								data-price="<?php echo number_format((float)abs($price2),2,".","");?>" 
								data-company="<?php echo $company;?>" 
								data-bankaccounts="<?php echo $bankaccounts;?>" 
								data-table="<?php echo str_replace("\"","'",$phtable);?>" class="<?php echo preg_match("#Modification statut de paiement#",$_SESSION['easybm_roles'])?"lx-edit-payment lx-open-popup":"";?>" 
								data-title="payment"><?php echo $row['state'];?></span>
							<span>Reste: <b><?php echo number_format((float)abs($price2-$smpaid),2,"."," ")." ".$settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printbc.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Bons de sortie#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['supplier'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],7);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['supplier'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Bons de sortie#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de commande</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Statut et historique des paiements <i class="fa fa-sort" data-sort="rest"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<?php
				$req1 = "SELECT COUNT(id) AS nb FROM documents c WHERE trash='1' AND type='bc'".$multicompanies;
				$req = "";
				if($_POST['keyword'] != ""){
					$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
									OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
									OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
				}
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
						}
					}
					$req .= ")";
				}
				if($_POST['supplier'] != ""){
					$req .= " AND supplier IN(".$_POST['supplier'].")";
				}
				if($_POST['user'] != ""){
					$req .= " AND user IN(".$_POST['user'].")";
				}
				if($_POST['product'] != ""){
					$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
				}				
				if($_POST['statee'] != ""){
					$req .= " AND state IN ('".str_replace(",","','",$_POST['statee'])."')";
				}
				if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
					$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
				}					
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
					$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				}
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$nbcmds = 0.1;
				if($row['nb'] != 0){
					$nbcmds = $row['nb'];
				}
				
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Non payée' AND type='bc' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$unpaid = $row['sm'];
				$nbunpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total1">
					<h3 style="background:#d9534f;">Non payée</h3>
					<p><?php echo number_format((float)(isset($unpaid)?$unpaid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbunpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbunpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbunpaid*100/$nbcmds?>%;background:#d9534f;">
						</ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT (SELECT SUM(price) FROM payments WHERE doc=d.id) AS sm,COUNT(id) AS nb FROM documents d WHERE state='Partiellement payée' AND type='bc' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$partiel = $row['sm'];					
				$nbpartiel = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total2">
					<h3 style="background:orange;">Partiellement payée</h3>
					<p><?php echo number_format((float)(isset($partiel)?$partiel:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpartiel*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpartiel . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpartiel*100/$nbcmds?>%;background:orange;"></ins>
					</h4>
				</div>
				<?php
				$req1 = "SELECT SUM(price) AS sm,COUNT(id) AS nb FROM documents WHERE state='Payée' AND type='bc' AND trash='1'".$multicompanies;
				$back = $bdd->query($req1.$req);
				$row = $back->fetch();
				$paid = $row['sm'];
				$nbpaid = $row['nb']!=""?$row['nb']:0;
				?>
				<div class="lx-caisse-total lx-caisse-total3">
					<h3 style="background:#5cb85c;">Payée</h3>
					<p><?php echo number_format((float)(isset($paid)?$paid:0),2,"."," ")." ".$settings['currency'];?> TTC</p>
					<ins><?php echo number_format((float)$nbpaid*100/$nbcmds,2,"."," ")?>% (<?php echo $nbpaid . " / " .round($nbcmds);?>)</ins>
					<h4 class="lx-progress-container">
						<ins class="lx-progress-bar" style="width:<?php echo $nbpaid*100/$nbcmds?>%;background:#5cb85c;">
						</ins>
					</h4>
				</div>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> bon(s) de commande de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> bon(s) de commande de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}			

			if($_POST['action'] == "loadbre"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de commande</td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM documents d WHERE category='supplier' AND type='bre' AND trash='".$_POST['state']."'".$multicompanies;
					if($_POST['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR note LIKE '%".sanitize_vars($_POST['keyword'])."%' 
										OR modepayment LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR conditions LIKE '%".sanitize_vars($_POST['keyword'])."%'
										OR abovetable LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND supplier IN(".$_POST['supplier'].")";
					}
					if($_POST['user'] != ""){
						$req .= " AND user IN(".$_POST['user'].")";
					}
					if($_POST['product'] != ""){
						$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_POST['product'])."'))";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}					
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$montant = 0;
					while($row = $back->fetch()){
						?>
					<tr>
						<td><label><input type="checkbox" name="bc" value="<?php echo $row['id'];?>" /><del class="checkmark"></del></label></td>
						<td><span><?php echo date("d/m/Y H:i",$row['dateadd']);?></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$company = $row1['rs'];
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['rs'];?></span></td>
						<td><a href="javascript:;" data-href="printbre.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature" style="white-space:nowrap;"><?php echo $row['code'];?></a></td>
						<td>
							<?php
							$back1 = $bdd->query("SELECT title,code,codefo FROM suppliers WHERE id='".$row['supplier']."'");
							$row1 = $back1->fetch();
							?>
							<span style="white-space:nowrap;"><?php echo $row1['title']!=""?$row1['title']." (".($row1['codefo']!=""?$row1['codefo']:$row1['code']).")":"";?></span>
						</td>
						<td>
							<?php
							$products = '<table>
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
											<tr class="lx-secondrow" style="display:none;">
												<td colspan="9">
													<center><i>0 produits / services</i></center>
												</td>
											</tr>';
							$price1 = 0;
							$price2 = 0;
							$discount = 0;
							$discounttype = "";
							$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='bre' AND category='supplier' AND trash='1' ORDER BY title");
							while($row1 = $back1->fetch()){
								?>
							<span style="white-space:nowrap;border-bottom:1px dashed #000000;">- <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,"."," ")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?></span>
								<?php
								$d = 0;
								if($row1['discounttype'] == "DH"){
									$discount += $row1['discount'];
									$d = $row1['discount'];
								}
								else{
									$discount += (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
									$d = (($row1['uprice']*$row1['qty'])*($row1['discount']/100));
								}
								$price1 += (($row1['tprice']+$d)*(1+($row1['tva']/100)));
								$price2 += ($row1['tprice']*(1+($row1['tva']/100)));
								$discounttype = $row1['discounttype'];
								$products .= '<tr class="lx-otherrow" data-title="'.$row1['title'].'" data-qty="'.$row1['qty'].'" data-unit="'.$row1['unit'].'" data-uprice="'.$row1['uprice'].'" data-remise="" data-tva="'.$row1['tva'].'" data-tprice="'.($row1['uprice']*$row1['qty']).'" data-ttprice="'.$row1['tprice'].'">
												<td style="width:auto;"><span>'.$row1['title'].'</span></td>
												<td><span>'.$row1['qty'].' '.$row1['unit'].'</span></td>
												<td><span>'.number_format((float)$row1['uprice'],2,"."," ").'</span></td>
												<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" value="'.$row1['discount'].'" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;">'.$discounttype.'</u></label></div></td>
												<td><span>'.$row1['tva'].'%</span></td>
												<td><span>'.number_format((float)$row1['tprice'],2,"."," ").'</span></td>
												<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>
												<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#fb8500;"></i></a></td>
												<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>
											</tr>';
							}
							$products .= '</table>';
							$montant += $price2;
							?>				
						</td>
						<td>
							<span><ins style="white-space:nowrap;">Sans remise TTC:</ins><br /><b><?php echo number_format((float)$price1,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Remise HT:</ins><br /><b><?php echo number_format((float)$discount,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
							<span><ins style="white-space:nowrap;">Avec remise TTC:</ins><br /><b><?php echo number_format((float)$price2,2,"."," ");?> <?php echo $settings['currency'];?></b></span>
						</td>
						<td><span><?php echo $row['note'];?></span></td>
						<td>
							<?php
							if($row['abovetable'] != ""){
							?>
							<span style="white-space:nowrap;width:300px;"><strong>Note (au-dessus de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['abovetable'];?></span>
							<?php
							}
							if($row['modepayment'] != ""){
							?>
							<span><strong>Mode de paiement (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['modepayment'];?></span>
							<?php
							}
							if($row['conditions'] != ""){
							?>
							<span><strong>Conditions (au-dessous de tableau) :</strong></span>
							<span style="margin-bottom:10px;"><?php echo $row['conditions'];?></span>
							<?php
							}
							?>
						</td>
						<?php
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
						<td><span style="white-space:nowrap"><?php echo $row1['fullname'];?></span></td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="documents" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="documents" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<a href="javascript:;" data-href="printbre.php?id=<?php echo $row['id'];?>" class="lx-download-file1 lx-open-popup" data-title="signature">Imprimer</a>
								<?php
								if(preg_match("#Modifier Bons de sortie#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Modifier" 
									data-id="<?php echo $row['id'];?>"
									data-code="<?php echo substr($row['code'],8);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['supplier'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>" data-title="command">Modifier</a>
									<?php
								}
								if(preg_match("#Transformation / Dupplication documents#",$_SESSION['easybm_roles'])){
									?>
								<a href="javascript:;" class="lx-edit lx-edit-command lx-open-popup" data-table="commands" data-header="Transformer / Dupliquer" 
									data-id="0"
									data-code="<?php echo substr($row['code'],8);?>"
									data-dateadd="<?php echo date("d/m/Y",$row['dateadd']);?>"
									data-company="<?php echo $row['company'];?>"
									data-exist="1"
									data-client="<?php echo $row['supplier'];?>"
									data-products="<?php echo str_replace("\"","'",$products);?>"
									data-discounttype="<?php echo $discounttype;?>"
									data-correctdoc="<?php echo $row['correctdoc'];?>"
									data-note="<?php echo str_replace("\"","'",sanitize_vars($row['note']));?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="command">Transformer / Duppliquer</a>
									<?php
								}
								?>
								<a href="javascript:;" class="lx-edit lx-edit-extrainfo lx-open-popup" data-table="extrainfo" data-header="Informations supplimentaires sur le document" 
									data-id="<?php echo $row['id'];?>"
									data-modepayment="<?php echo str_replace("\"","'",sanitize_vars($row['modepayment']));?>"
									data-conditions="<?php echo str_replace("\"","'",sanitize_vars($row['conditions']));?>"
									data-abovetable="<?php echo str_replace("\"","'",sanitize_vars($row['abovetable']));?>" data-title="extrainfo">Informations supplimentaires sur le document</a>
								<?php
								if(preg_match("#Supprimer Bons de sortie#",$_SESSION['easybm_roles'])){
								?>
								<a href="javascript:;" class="lx-delete lx-delete-command lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
								<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Fournisseur <i class="fa fa-sort" data-sort="client"></i></td>
						<td>Détails bon de commande</td>
						<td style="text-align:right;"><?php echo number_format((float)$montant,2,"."," ")." ".$settings['currency'];?> TTC <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Note</td>
						<td>Informations supplimentaires sur le document</td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="user"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> bon(s) de réception de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> bon(s) de réception de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}	

			if($_POST['action'] == "addexpense"){
				$date = getDateNow($_POST['dateadd']);
				if($_POST['datedue'] != "" AND preg_match("#^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$#",$_POST['datedue'])){
					$datedue = getDateNow($_POST['datedue']);
				}
				else{
					$datedue = "";
				}
				if($_POST['paid'] != "1" AND $_POST['datepaid'] != "" AND preg_match("#^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$#",$_POST['datepaid'])){
					$datepaid = getDateNow($_POST['datepaid']);
				}
				else{
					$datepaid = "";
				}
				if($_POST['remis'] == "1" AND $_POST['dateremis'] != "" AND preg_match("#^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$#",$_POST['dateremis'])){
					$dateremis = getDateNow($_POST['dateremis']);
				}
				else{
					$dateremis = "";
				}
				if($_POST['type'] == "Sortie"){
					$_POST['remis'] = "0";
					$dateremis = "";
				}
				if($_POST['modepayment'] == "Espèce" AND $_POST['invoiced'] == "Non"){
					$datedue = $date;
					$datepaid = $date;
				}
				if($_POST['type'] == "Entrée"){
					$back = $bdd->query("SELECT * FROM clients WHERE fullname='".$_POST['client']."'");
					if($back->rowCount() == 0){
						$rand = "CL".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM clients ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "CL".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO clients(id,code,ice,fullname,phone,address,email,company,dateadd,trash) 
						VALUES ('0','".$rand."','','".sanitize_vars($_POST['client'])."','','','','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
						$back = $bdd->query("SELECT id FROM clients WHERE fullname='".$_POST['client']."' ORDER BY id DESC LIMIT 0,1");
						$row = $back->fetch();
						$_POST['client'] = $row['id'];
					}
					else{
						$row = $back->fetch();
						$_POST['client'] = $row['id'];
					}
				}
				else{
					$back = $bdd->query("SELECT * FROM suppliers WHERE title='".$_POST['supplier']."'");
					if($back->rowCount() == 0){
						$rand = "FR".date("ym")."-0001";
						$back1 = $bdd->query("SELECT code FROM suppliers ORDER BY id DESC LIMIT 0,1");
						if($back1->rowCount() > 0){
							$row1 = $back1->fetch();
							$rand = "FR".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
						}
						$req = $bdd->prepare("INSERT INTO suppliers(id,code,title,respname,respphone,respemail,respfax,ice,address,note,company,dateadd,trash) 
						VALUES ('0','".$rand."','".sanitize_vars($_POST['supplier'])."','','','','','','','','".sanitize_vars($_POST['company'])."','".time()."','1')");
						$req->execute();
						$back = $bdd->query("SELECT id FROM suppliers WHERE title='".$_POST['supplier']."' ORDER BY id DESC LIMIT 0,1");
						$row = $back->fetch();
						$_POST['supplier'] = $row['id'];
					}
					else{
						$row = $back->fetch();
						$_POST['supplier'] = $row['id'];
					}							
				}
				if($_POST['id'] == "0"){
					$rand = substr($_POST['type'],0,1).date("ym")."-000001";
					$back = $bdd->query("SELECT code,dateadd FROM payments WHERE code LIKE '".substr($rand,0,1)."%' AND company='".$_POST['company']."' ORDER BY id DESC LIMIT 0,1");
					if($back->rowCount() > 0){
						$row1 = $back->fetch();
						if(date("Y") == date("Y",$row1['dateadd'])){
							$rand = substr($rand,0,1).date("ym")."-".sprintf('%06d',intval(substr($row1['code'],6))+1);
						}
					}
															
					$req = $bdd->prepare("INSERT INTO payments(id,code,worker,company,doc,type,nature,title,note,price,invoiced,modepayment,remis,dateremis,nremise,paid,tva,client,supplier,rib,imputation,datedue,datepaid,dateadd,trash) 
					VALUES ('0','".$rand."','".$_SESSION['id']."','".sanitize_vars($_POST['company'])."','0','".sanitize_vars($_POST['type'])."','".sanitize_vars($_POST['nature'])."','".sanitize_vars($_POST['title'])."','".addslashes($_POST['description'])."','".sanitize_vars($_POST['price'])."','".sanitize_vars($_POST['invoiced'])."','".sanitize_vars($_POST['modepayment'])."','".sanitize_vars($_POST['remis'])."','".$dateremis."','".sanitize_vars($_POST['nremise'])."','".sanitize_vars($_POST['paid'])."','".sanitize_vars($_POST['tva'])."','".sanitize_vars($_POST['client'])."','".sanitize_vars($_POST['supplier'])."','".sanitize_vars($_POST['rib'])."','".sanitize_vars($_POST['imputation'])."','".$datedue."','".$datepaid."','".$date."','1')");
					$req->execute();
				}
				else{
					$req = $bdd->prepare("UPDATE payments SET company='".sanitize_vars($_POST['company'])."',type='".sanitize_vars($_POST['type'])."',nature='".sanitize_vars($_POST['nature'])."',title='".sanitize_vars($_POST['title'])."',note='".addslashes($_POST['description'])."',price='".sanitize_vars($_POST['price'])."',invoiced='".sanitize_vars($_POST['invoiced'])."',modepayment='".sanitize_vars($_POST['modepayment'])."',remis='".sanitize_vars($_POST['remis'])."',dateremis='".$dateremis."',nremise='".sanitize_vars($_POST['nremise'])."',paid='".sanitize_vars($_POST['paid'])."',tva='".sanitize_vars($_POST['tva'])."',client='".sanitize_vars($_POST['client'])."',supplier='".sanitize_vars($_POST['supplier'])."',rib='".sanitize_vars($_POST['rib'])."',imputation='".sanitize_vars($_POST['imputation'])."',dateadd='".$date."',datedue='".$datedue."',datepaid='".$datepaid."' WHERE id='".$_POST['id']."'");
					$req->execute();
				}
			}

			if($_POST['action'] == "addcaisse"){
				$dateremis = "";
				$datedue = "";
				$datepaid = "";
				if($_POST['dateremis'] != ""){
					$dateremis = getDateNow($_POST['dateremis']);
				}
				if($_POST['datedue'] != ""){
					$datedue = getDateNow($_POST['datedue']);
				}
				if($_POST['datepaid'] != "" AND $_POST['paid'] == "0"){
					$datepaid = getDateNow($_POST['datepaid']);
				}
				$req = $bdd->prepare("UPDATE payments SET imputation='".sanitize_vars($_POST['imputation'])."',rib='".sanitize_vars($_POST['rib'])."',datedue='".$datedue."',datepaid='".$datepaid."',paid='".$_POST['paid']."',remis='".$_POST['remis']."',dateremis='".$dateremis."',nremise='".sanitize_vars($_POST['nremise'])."',note='".addslashes($_POST['note'])."' WHERE id='".$_POST['id']."'");
				$req->execute();
			}
			
			if($_POST['action'] == "deletecaisse"){
				$back = $bdd->query("SELECT attachments FROM payments WHERE id='".$_POST['id']."'");
				$command = $back->fetch();
				$pictures = explode(",",$command['attachments']);
				for($i=1;$i<count($pictures);$i++){
					if(file_exists("uploads/".$pictures[$i])){
						unlink("uploads/".$pictures[$i]);
					}
				}
				$req = $bdd->prepare("DELETE FROM payments WHERE id='".$_POST['id']."'");
				$req->execute();
			}

			if($_POST['action'] == "editcaissebulk"){
				if($_POST['datepaid'] != "" AND preg_match("#^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$#",$_POST['datepaid'])){
					$datepaid = getDateNow($_POST['datepaid']);
				}
				else{
					$datepaid = "";
				}
				$req = $bdd->prepare("UPDATE payments SET rib='".$_POST['rib']."',datepaid='".$datepaid."' WHERE id IN(".$_POST['ids'].")");
				$req->execute();
			}	

			if($_POST['action'] == "editremisebulk"){
				if($_POST['dateremis'] != "" AND preg_match("#^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$#",$_POST['dateremis'])){
					$datepaid = getDateNow($_POST['dateremis']);
				}
				else{
					$datepaid = "";
				}
				$req = $bdd->prepare("UPDATE payments SET remis='1',dateremis='".$datepaid."',nremise='".$_POST['nremise']."' WHERE id IN(".$_POST['ids'].")");
				$req->execute();
			}	
			
			if($_POST['action'] == "loadcaisse"){
				?>
				<table cellpadding="0" cellspacing="0">
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Nature <i class="fa fa-sort" data-sort="type"></i></td>
						<td>Mode de paiement <i class="fa fa-sort" data-sort="modepayment"></i></td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Catégorie <i class="fa fa-sort" data-sort="nature"></i></td>
						<td>Libellé <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Note <i class="fa fa-sort" data-sort="note"></i></td>
						<td>Date d'écheance <i class="fa fa-sort" data-sort="datedue"></i></td>
						<?php
						if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
						?>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<?php
						}
						if(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
						?>
						<td>Fournisseur <i class="fa fa-sort" data-sort="supplier"></i></td>
						<?php
						}
						?>
						<td>Imputation comptable <i class="fa fa-sort" data-sort="imputation"></i></td>
						<td>Compte banquaire encaissement / décaissement <i class="fa fa-sort" data-sort="rib"></i></td>
						<td>Date d'encaissement / décaissement (Rapprochement): <i class="fa fa-sort" data-sort="datepaid"></i></td>
						<td>Remis <i class="fa fa-sort" data-sort="remis"></i></td>
						<td>Impayé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>Facturé <i class="fa fa-sort" data-sort="invoiced"></i></td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="worker"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
					<?php
					$req = "SELECT * FROM payments WHERE trash='".$_POST['state']."'".$companies;
					if($_POST['keyword'] != ""){
						$req .= " AND (nremise LIKE '%".sanitize_vars($_POST['keyword'])."%' OR code LIKE '%".sanitize_vars($_POST['keyword'])."%' OR title LIKE '%".sanitize_vars($_POST['keyword'])."%' OR note LIKE '%".addslashes($_POST['keyword'])."%' OR type LIKE '%".sanitize_vars($_POST['keyword'])."%' OR price LIKE '%".sanitize_vars($_POST['keyword'])."%' OR imputation LIKE '%".sanitize_vars($_POST['keyword'])."%')";
					}
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", company)";
							}
						}
						$req .= ")";
					}
					if($_POST['status'] != ""){
						if($_POST['status'] != "a11a"){
							$req .= " AND (1=0 ";
						}
						else{
							$req .= " AND (1=1 ";
						}
						if(preg_match("#a1a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datepaid='')";
						}
						if(preg_match("#a2a#",$_POST['status'])){
							$req .= " OR (type='Sortie' AND datepaid='')";
						}
						if(preg_match("#a3a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datepaid='' AND datedue<".time()." AND dateremis='')";
						}
						if(preg_match("#a14a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datepaid='' AND datedue>".time()." AND dateremis='')";
						}
						if(preg_match("#a4a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datedue<".time()." AND datepaid='')";
						}
						if(preg_match("#a5a#",$_POST['status'])){
							$req .= " OR (type='Sortie' AND datedue<>'' AND datedue<".time()." AND datepaid='')";
						}
						if(preg_match("#a6a#",$_POST['status'])){
							$req .= " OR (dateremis<>'')";
						}
						if(preg_match("#a7a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datepaid<>'')";
						}
						if(preg_match("#a12a#",$_POST['status'])){
							$req .= " OR (type='Sortie' AND datepaid<>'')";
						}
						if(preg_match("#a8a#",$_POST['status'])){
							$req .= " OR (paid='1')";
						}
						if(preg_match("#a9a#",$_POST['status'])){
							$req .= " OR (type='Entrée' AND datedue>".time().")";
						}
						if(preg_match("#a13a#",$_POST['status'])){
							$req .= " OR (type='Sortie' AND datedue>".time().")";
						}
						if(preg_match("#a10a#",$_POST['status'])){
							$req .= " OR (datedue='')";
						}
						if(preg_match("#a15a#",$_POST['status'])){
							$req .= " OR (datepaid<>'')";
						}
						if(preg_match("#a16a#",$_POST['status'])){
							$req .= " OR (datepaid='')";
						}
						if(preg_match("#a11a#",$_POST['status'])){
							$req .= ") AND paid='0'";
						}
						else{
							$req .= ")";
						}
					}
					if($_POST['worker'] != ""){
						$req .= " AND user IN (".$_POST['worker'].")";
					}
					if($_POST['client'] != ""){
						$req .= " AND client IN ('".str_replace(",","','",$_POST['client'])."')";
					}
					if($_POST['imputation'] != ""){
						$req .= " AND imputation IN('".str_replace(",","','",$_POST['imputation'])."')";
					}
					if($_POST['rib'] != ""){
						$req .= " AND rib IN (".$_POST['rib'].")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND supplier IN ('".str_replace(",","','",$_POST['supplier'])."')";
					}
					if($_POST['type'] != ""){
						$req .= " AND type IN('".str_replace(",","','",$_POST['type'])."')";
					}
					if($_POST['invoiced'] != ""){
						$req .= " AND invoiced IN('".str_replace(",","','",$_POST['invoiced'])."')";
					}
					if($_POST['modepayment'] != ""){
						$req .= " AND modepayment IN('".str_replace(",","','",$_POST['modepayment'])."')";
					}
					if($_POST['pricemin'] != "" AND $_POST['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_POST['pricemin']." AND ".$_POST['pricemax'].")";
					}
					if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['dateduestart'] != "" AND $_POST['datedueend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['dateduestart']));
						$dateend = strtotime(str_replace("/","-",$_POST['datedueend'])) + (60*60*24) - 1;
						$req .= " AND (datedue BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['datepaidstart'] != "" AND $_POST['datepaidend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['datepaidstart']));
						$dateend = strtotime(str_replace("/","-",$_POST['datepaidend'])) + (60*60*24) - 1;
						$req .= " AND (datepaid BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['dateremisstart'] != "" AND $_POST['dateremisend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_POST['dateremisstart']));
						$dateend = strtotime(str_replace("/","-",$_POST['dateremisend'])) + (60*60*24) - 1;
						$req .= " AND (dateremis BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_POST['sortby'] != ""){
						if($_POST['sortby'] == "rest"){
							$req .= " ORDER BY (price - COALESCE((SELECT SUM(price) FROM payments WHERE doc=d.id),0))";
						}
						else{
							$req .= " ORDER BY ".$_POST['sortby'];
						}
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_POST['orderby'];
					$back4 = $bdd->query($req);
					$req .= " LIMIT ".$_POST['start'].",".$_POST['nbpage'];
					$back = $bdd->query($req);
					$nbin = 0;
					$totalin = 0;
					$nbout = 0;
					$totalout = 0;
					while($row = $back->fetch()){
						$background = "";
						if($row['paid'] == "1"){
							$background = "background:rgba(255,0,0,0.1);";
						}
						if($row['datepaid'] != ""){
							$background = "background:rgba(0,128,0,0.1);";
						}
						?>
					<tr style="<?php echo $background;?>" data-umpaid="<?php echo $background;?>">
						<td><label><input type="checkbox" name="caisse" value="<?php echo $row['id'];?>" data-company="<?php echo $row['company'];?>" data-type="<?php echo $row['type'];?>" data-mode="<?php echo preg_match("#Chèque|Effet#",$row['modepayment'])?"Chèques":"";?>" /><del class="checkmark"></del></label></td>
						<td><span><ins><?php echo ($row['dateadd']!=""?date("d/m/Y H:i",$row['dateadd']):"&mdash;");?></ins></span></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						$companytxt = $row1['rs'];
						?>
						<td style="white-space:nowrap;"><span style="white-space-nowrap"><?php echo $row1['rs'];?></span></td>						
						<td><span style="white-space:nowrap;"><?php echo $row['code'];?></span></td>
						<?php
						if($row['type'] == "Entrée"){
							if($row['paid'] == "0"){
								$nbin++;
								$totalin += $row['price'];								
							}
							$color = "color:#a2d695;";
							$background = "background:#a2d695;";
						}
						else{
							if($row['paid'] == "0"){
								$nbout++;
								$totalout += $row['price'];							
							}
							$color = "color:#ff7373;";
							$background = "background:#ff7373;";
						}
						?>
						<td><span style="font-weight:600;<?php echo $color;?>"><?php echo $row['type']=="Entrée"?"Encaissement":"Dépense";?></span></td>
						<td><span><?php echo $row['modepayment'];?></span></td>
						<td style="<?php echo $background;?>;white-space:nowrap;text-align:right;">
							<span><?php echo number_format((float)$row['price'],2,"."," ")." ".$settings['currency'];?> TTC</span>
							<?php
							if($row['invoiced'] == "Oui" AND $row['doc'] == "0"){
								?>
							<span>TVA: <?php echo $row['tva'];?>%</span>
								<?php
							}
							?>							
						</td>
						<td><span style="width:170px;"><?php echo $row['nature'];?></span></td>
						<td><span style="width:170px;"><?php echo $row['title'];?></span></td>
						<td><span style="width:200px;"><?php echo $row['note'];?></span></td>
						<td><span style="display:inline-block;padding:2px 5px;font-weight:500;<?php echo (($row['datepaid']=="" AND $row['datedue']!="" AND $row['datedue']<=time())?"background:orange;color:#FFFFFF;":"");?>border-radius:4px;"><ins><?php echo ($row['datedue']!=""?date("d/m/Y",$row['datedue']):"&mdash;");?></ins></span></td>	
						<?php
						if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
							$back1 = $bdd->query("SELECT fullname,code,codecl FROM clients WHERE id='".$row['client']."'");
							$row1 = $back1->fetch();
							?>
						<td>
							<span style="white-space:nowrap;"><?php echo ($row1['fullname']!=""?($row1['fullname']." (".($row1['codecl']!=""?$row1['codecl']:$row1['code']).")"):"");?></span>
						</td>
							<?php
						}
						if(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
						?>
						<td>
							<?php
							$back1 = $bdd->query("SELECT title,code,codefo FROM suppliers WHERE id='".$row['supplier']."'");
							$row1 = $back1->fetch();
							?>
							<span style="white-space:nowrap;"><?php echo ($row1['title']!=""?($row1['title']." (".($row1['codefo']!=""?$row1['codefo']:$row1['code']).")"):"");?></span>
						</td>
						<?php
						}
						?>
						<td><span><?php echo $row['imputation'];?></span></td>
						<td>	
							<?php
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE id='".$row['rib']."'");
							$rib = $back1->fetch();
							if($row['rib'] != "0"){
								?>
							<span><?php echo $rib['bank']." | ".$rib['rib'];?></span>
								<?php
							}
							?>
						</td>
						<td><span style="display:inline-block;padding:2px 5px;font-weight:500;<?php echo ($row['datepaid']!=""?"background:#7EC855;color:#FFFFFF;":"");?>border-radius:4px;"><ins><?php echo ($row['datepaid']!=""?date("d/m/Y",$row['datepaid']):"&mdash;");?></ins></span></td>
						<td>
							<?php
							if($row['remis'] == '1'){
								?>
						<span style="display:inline-block;padding:2px 5px;font-weight:500;background:#fb8500;color:#FFFFFF;border-radius:4px;">Remis</span>
						<span style="white-space:nowrap;">Le: <?php echo date("d/m/Y",$row['dateremis']);?></span>
								<?php
								if($row['nremise'] != ""){
								?>
						<span>N° de remise: <?php echo $row['nremise'];?></span>
								<?php
								}
							}
							?>
						</td>
						<td>
							<?php
							if($row['paid'] == '1'){
								?>
						<span style="display:inline-block;padding:2px 5px;font-weight:500;background:#FF0000;color:#FFFFFF;border-radius:4px;">Impayé</span>
								<?php
							}
							?>
						</td>
						<td>
							<?php
							if($row['invoiced'] != 'Oui'){
								?>
							<span style="display:inline-block;padding:2px 5px;font-weight:500;color:orange;font-size:14px;border-radius:4px;white-space:nowrap;">Non facturé </span>
								<?php
							}
							else{
								?>
							<span style="display:inline-block;padding:2px 5px;font-weight:500;color:#71b44c;font-size:14px;border-radius:4px;">Facturé</span>
								<?php
							}
							?>
						</td>
						<td style="white-space:nowrap;">
							<?php
							$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
							$user = $back1->fetch();
							?>
							<span><?php echo $user['fullname'];?></span>
						</td>
						<td>
							<?php
							if($row['attachments'] != ""){
								$attachments = explode(",",$row['attachments']);
								for($i=1;$i<count($attachments);$i++){
									?>
							<span style="position:relative;white-space:nowrap;margin-bottom:5px;">
								<a href="javascript:;" class="lx-delete-file"><i class="fa fa-times"></i></a>
								<div class="lx-delete-file-choice">
									<a href="javascript:;" class="lx-yes-delete-file" data-table="payments" data-id="<?php echo $row['id'];?>" data-file="<?php echo $attachments[$i];?>" data-files="<?php echo $row['attachments'];?>">Oui</a>
									<a href="javascript:;" class="lx-no-delete-file">Non</a>
								</div>
								<a href="uploads/<?php echo $attachments[$i];?>" target="_blank" style="white-space:nowrap;"><?php echo $attachments[$i];?></a>
							</span>
									<?php
								}
							}
							?>
							<div class="lx-upload-files">
								<input type="file" name="uploadfiles[]" id="uploadfiles<?php echo $row['id'];?>" data-table="payments" data-id="<?php echo $row['id'];?>" multiple="multiple" />
								<a href="javascript:;" class=""><i class="fa fa-plus"></i> fichier</a>
							</div>
						</td>
						<td style="position:relative;">
							<?php
							if($_POST['state'] == 1){
								?>
							<a href="javascript:;" class="lx-open-edit-menu"><i class="fa fa-ellipsis-v"></i></a>
							<div class="lx-edit-menu">
								<?php
								if(preg_match("#Modifier Trésorerie#",$_SESSION['easybm_roles'])){
								?>
							<a href="javascript:;" class="lx-edit <?php echo $row['doc']!="0"?"lx-edit-caisse":"lx-edit-expense";?> lx-open-popup" data-header="Modifier une" 
								data-id="<?php echo $row['id'];?>"
								data-code="<?php echo $row['code'];?>"
								data-client="<?php echo $row['client'];?>"
								data-supplier="<?php echo $row['supplier'];?>"
								data-type="<?php echo $row['type'];?>"
								data-titl="<?php echo $row['title'];?>"
								data-price="<?php echo $row['price'];?>"
								data-tva="<?php echo $row['tva'];?>"
								data-companytxt="<?php echo $companytxt;?>"
								data-modepayment="<?php echo $row['modepayment'];?>"
								data-company="<?php echo $row['company'];?>"
								data-dateadd="<?php echo ($row['dateadd']!=""?date("d/m/Y",$row['dateadd']):"");?>" 
								data-datedue="<?php echo ($row['datedue']!=""?date("d/m/Y",$row['datedue']):"");?>" 
								data-datepaid="<?php echo ($row['datepaid']!=""?date("d/m/Y",$row['datepaid']):"");?>" 
								data-nature="<?php echo $row['nature'];?>"
								data-rib="<?php echo $row['rib'];?>"
								data-invoiced="<?php echo $row['invoiced'];?>"
								data-imputation="<?php echo $row['imputation'];?>"
								data-description="<?php echo str_replace("\"","'",$row['note']);?>"
								data-remis="<?php echo $row['remis']=="1"?true:false;?>"
								data-dateremis="<?php echo ($row['dateremis']!=""?date("d/m/Y",$row['dateremis']):"");?>" 
								data-nremise="<?php echo $row['nremise'];?>"
								data-paid="<?php echo $row['paid']=="1"?true:false;?>" data-title="<?php echo $row['doc']!="0"?"caisse":"expense";?>">Modifier</a>
								<?php
								}
								if(preg_match("#Supprimer Trésorerie#",$_SESSION['easybm_roles'])){
										?>
							<a href="javascript:;" class="lx-delete lx-delete-caisse lx-open-popup" data-title="deleterecord" data-id="<?php echo $row['id'];?>">Supprimer</a>
										<?php
								}
								?>
							</div>
								<?php
							}
							?>
						</td>
									</tr>
						<?php
					}
					?>
					<tr class="lx-first-tr">
						<td><label><input type="checkbox" name="selectall" value="selectall" /><del class="checkmark"></del></label></td>
						<td>Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td>Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td>Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td>Nature <i class="fa fa-sort" data-sort="type"></i></td>
						<td>Mode de paiement <i class="fa fa-sort" data-sort="modepayment"></i></td>
						<td>Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td>Catégorie <i class="fa fa-sort" data-sort="nature"></i></td>
						<td>Libellé <i class="fa fa-sort" data-sort="title"></i></td>
						<td>Note <i class="fa fa-sort" data-sort="note"></i></td>
						<td>Date d'écheance <i class="fa fa-sort" data-sort="datedue"></i></td>
						<?php
						if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
						?>
						<td>Client <i class="fa fa-sort" data-sort="client"></i></td>
						<?php
						}
						if(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
						?>
						<td>Fournisseur <i class="fa fa-sort" data-sort="supplier"></i></td>
						<?php
						}
						?>
						<td>Imputation comptable <i class="fa fa-sort" data-sort="imputation"></i></td>
						<td>Compte banquaire encaissement / décaissement <i class="fa fa-sort" data-sort="rib"></i></td>
						<td>Date d'encaissement / décaissement (Rapprochement): <i class="fa fa-sort" data-sort="datepaid"></i></td>
						<td>Remis <i class="fa fa-sort" data-sort="remis"></i></td>
						<td>Impayé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td>Facturé <i class="fa fa-sort" data-sort="invoiced"></i></td>
						<td>Utilisateur <i class="fa fa-sort" data-sort="worker"></i></td>
						<td>Attachements</td>
						<td><i class="fa fa-ellipsis-v"></i></td>
									</tr>
				</table>
				<div class="lx-caisse-total3">
					<div style="float:left;margin-right:60px;">
						<p>* Nb entrée: <b><?php echo $nbin;?></b></p>
						<p>* Total entrée: <b><?php echo number_format((float)$totalin,2,"."," ").$settings['currency'];?></b></p>
					</div>
					<div>
						<p>* Nb sortie: <b><?php echo $nbout;?></b></p>
						<p>* Total sortie: <b><?php echo number_format((float)$totalout,2,"."," ").$settings['currency'];?></b></p>
					</div>
					<div class="lx-clear-fix"></div>
					<div class="lx-action-bulk">
						<?php
						if(preg_match("#Exporter Trésorerie#",$_SESSION['easybm_roles'])){
						?>
						<a href="downloadcaisse.php?sortby=<?php echo $_POST['sortby'];?>&
													orderby=<?php echo $_POST['orderby'];?>&
													keyword=<?php echo $_POST['keyword'];?>&
													company=<?php echo $_POST['company'];?>&
													client=<?php echo $_POST['client'];?>&
													supplier=<?php echo $_POST['supplier'];?>&
													user=<?php echo $_POST['worker'];?>&
													imputation=<?php echo $_POST['imputation'];?>&
													rib=<?php echo $_POST['rib'];?>&
													modepayment=<?php echo $_POST['modepayment'];?>&
													status=<?php echo $_POST['status'];?>&
													pricemin=<?php echo $_POST['pricemin'];?>&
													pricemax=<?php echo $_POST['pricemax'];?>&
													type=<?php echo $_POST['type'];?>&
													datestart=<?php echo $_POST['datestart'];?>&
													dateend=<?php echo $_POST['dateend'];?>&
													dateduestart=<?php echo $_POST['dateduestart'];?>&
													datedueend=<?php echo $_POST['datedueend'];?>&
													datepaidstart=<?php echo $_POST['datepaidstart'];?>&
													datepaidend=<?php echo $_POST['datepaidend'];?>&
													dateremisstart=<?php echo $_POST['dateremisstart'];?>&
													dateremisend=<?php echo $_POST['dateremisend'];?>" class="lx-download-file"><i class="fa fa-download"></i> Téléchager PDF</a>
						<a href="javascript:;" class="lx-new lx-open-popup" data-title="export"><i class="fa fa-download"></i> Exporter Excel</a>
						<?php
						}
						?>					
					</div>
					<div class="lx-clear-fix"></div>
				</div>
				<input type="hidden" id="posts" value="<?php echo $back4->rowCount();?>" />
				<?php
				if($back4->rowCount() > ($_POST['start'] + $_POST['nbpage'])){
				?>
				<p><?php echo ($_POST['start'] + $_POST['nbpage']);?> entrée(s) / sortie(s) de <?php echo $back4->rowCount();?></p>
				<?php
				}
				else{
				?>
				<p><?php echo $back4->rowCount();?> entrée(s) / sortie(s) de <?php echo $back4->rowCount();?></p>
				<?php			
				}
			}

			if($_POST['action'] == "loadkpi"){
				$req = "";
				$datestart = time() - (60*60*24*30);
				$dateend = time();
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
				}
				$req .= " AND (d.dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", d.company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
						}
					}
					$req .= ")";
				}
				if($_POST['client'] != "" AND $_POST['supplier'] != ""){
					$req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
				}
				else{
					if($_POST['client'] != ""){
						$req .= " AND d.client IN(".$_POST['client'].")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND d.supplier IN(".$_POST['supplier'].")";
					}
				}
				$req .= $multicompanies;
				$back = $bdd->query("SELECT SUM((CASE WHEN typedoc='facture' THEN tprice WHEN typedoc='avoir' THEN tprice*(-1) ELSE 0 END) * (1 + (tva / 100))) AS sm,
											SUM((CASE WHEN typedoc='facture' THEN tprice WHEN typedoc='avoir' THEN tprice*(-1) ELSE 0 END) * (tva / 100)) AS stva FROM detailsdocuments d WHERE typedoc IN('facture','avoir') AND trash='1'".$req);
				$ca = $back->fetch();
				$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND invoiced='Oui' AND doc='0'".$req);
				$entree = $back->fetch();
				?>
				<div class="lx-g4 lx-pr-0 lx-pb-0 lx-plr-0-mob">
					<div class="lx-state-count" style="background:<?php echo ($ca['sm']+$entree['sm'])>0?"#5cb85c":(($ca['sm']+$entree['sm'])<0?"#CC0000":"#BEBEBE");?>;">
						<a href="javascript:;">
							<i class="fa fa-hand-holding-usd"></i>
							<br />
							<span style="color:#FFFFFF;"><strong><?php echo number_format((float)($ca['sm']+$entree['sm']),2,"."," ")." ".$settings['currency'];?></strong><br /><del class="infobul" data-title="Chiffre d'affaires = (valeur factures + encaissements manuels) - valeur avoirs">Chiffre d'affaires TTC</del></span>
							<div class="lx-clear-fix"></div>
							<?php
							$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND invoiced='Non' AND doc='0'".$req);
							$entree = $back->fetch();
							?>
							<p><b><?php echo number_format((float)($entree['sm']),2,"."," ")." ".$settings['currency'];?></b> non facturé</p>
						</a>
					</div>
				</div>
				<?php
				$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND paid='0' AND invoiced='Oui' AND trash='1'".$req);
				$entree = $back->fetch();
				?>
				<div class="lx-g4 lx-pb-0 lx-plr-0-mob">
					<div class="lx-state-count" style="background:#a2d695;">
						<a href="javascript:;">
							<i class="fas fa-money-bill-wave"></i>
							<br />
							<span style="color:#FFFFFF;"><strong><?php echo number_format((float)$entree['sm'],2,"."," ")." ".$settings['currency'];?></strong><br />Encaissements TTC</span>
							<div class="lx-clear-fix"></div>
							<?php
							$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Entrée' AND paid='0' AND invoiced='Non' AND doc='0'".$req);
							$entree = $back->fetch();
							?>
							<p><b><?php echo number_format((float)($entree['sm']),2,"."," ")." ".$settings['currency'];?></b> non facturé</p>
						</a>
					</div>
				</div>
				<?php
				$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Sortie' AND paid='0' AND invoiced='Oui' AND trash='1'".$req);
				$sortie = $back->fetch();
				?>
				<div class="lx-g4 lx-pl-0 lx-pb-0 lx-plr-0-mob">
					<div class="lx-state-count" style="background:#ff7373;">
						<a href="javascript:;">
							<i class="fas fa-money-bill-wave"></i>
							<br />
							<span style="color:#FFFFFF;"><strong><?php echo number_format((float)$sortie['sm'],2,"."," ")." ".$settings['currency'];?></strong><br />Dépenses TTC</span>
							<div class="lx-clear-fix"></div>
							<?php
							$back = $bdd->query("SELECT SUM(price) AS sm FROM payments d WHERE type='Sortie' AND paid='0' AND invoiced='Non' AND doc='0'".$req);
							$sortie = $back->fetch();
							?>
							<p><b><?php echo number_format((float)($sortie['sm']),2,"."," ")." ".$settings['currency'];?></b> non facturé</p>
						</a>
					</div>
				</div>
				<?php
				$back = $bdd->query("SELECT SUM(price * (tva / 100)) AS sm FROM payments d WHERE type='Entrée'".$req);
				$tvaentree = $back->fetch();
				$back = $bdd->query("SELECT SUM(price * (tva / 100)) AS sm FROM payments d WHERE type='Sortie'".$req);
				$tvasortie = $back->fetch();
				$back = $bdd->query("SELECT SUM(tprice * (tva / 100)) AS stva FROM detailsdocuments d WHERE typedoc='bc' AND trash='1'".$req);
				$tva = $back->fetch();				
				?>
				<div class="lx-g4 lx-pl-0 lx-pb-0 lx-plr-0-mob">
					<div class="lx-state-count" style="padding:27px 10px;background:#90b5c1;">
						<a href="javascript:;">
							<i class="fa fa-file-invoice"></i>
							<br />
							<?php
							if(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles']) AND preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
								?>
							<span style="color:#FFFFFF;"><strong style="font-size:14px;"><?php echo number_format((float)$ca['stva']+$tvaentree['sm'],2,"."," ")." ".$settings['currency']." / ".number_format((float)$tva['stva']+$tvasortie['sm'],2,"."," ")." ".$settings['currency'];?></strong><br />TVA Collectée / Payée</span>
								<?php
							}
							elseif(preg_match("#Consulter Clients|Consulter Factures,|Consulter Devis|Consulter Factures proforma|Consulter Bons de livraison|Consulter Bons de sortie|Consulter Bons de retour|Consulter Factures avoir#",$_SESSION['easybm_roles'])){
								?>
							<span style="color:#FFFFFF;"><strong style="font-size:14px;"><?php echo number_format((float)$ca['stva']+$tvaentree['sm'],2,"."," ")." ".$settings['currency'];?></strong><br />TVA Collectée</span>
								<?php
							}
							elseif(preg_match("#Consulter Fournisseurs|Consulter Bons de commande|Consulter Bons de récéption#",$_SESSION['easybm_roles'])){
								?>
							<span style="color:#FFFFFF;"><strong style="font-size:14px;"><?php echo number_format((float)$tva['stva']+$tvasortie['sm'],2,"."," ")." ".$settings['currency'];?></strong><br />TVA Payée</span>
								<?php
							}
							?>
							<div class="lx-clear-fix"></div>
						</a>
					</div>
				</div>
				<div class="lx-clear-fix"></div>
				<?php
			}

			if($_POST['action'] == "loaddocuments"){
				$req = "";
				$datestart = time() - (60*60*24*30);
				$dateend = time();
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
				}
				$req .= " AND (d.dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", d.company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
						}
					}
					$req .= ")";
				}
				if($_POST['client'] != "" AND $_POST['supplier'] != ""){
					$req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
				}
				else{
					if($_POST['client'] != ""){
						$req .= " AND d.client IN(".$_POST['client'].")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND d.supplier IN(".$_POST['supplier'].")";
					}
				}
				$req .= $multicompanies;
				if(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='facture' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="factures.php">
					<strong>Factures</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Devis#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='devis' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="devis.php">
					<strong>Devis</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Factures avoir#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='avoir' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="avoirs.php">
					<strong>Factures avoir</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Bons de retour#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='br' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="br.php">
					<strong>Bons de retour</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Factures proforma#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='factureproforma' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="factureproforma.php">
					<strong>Factures proforma</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Bons de livraison#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bl' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="bl.php">
					<strong>Bons de livraison</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Bons de sortie#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bs' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="bs.php">
					<strong>Bons de sortie</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Bons de commande#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bc' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="bc.php">
					<strong>Bons de commande</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				if(preg_match("#Consulter Bons de réception#",$_SESSION['easybm_roles'])){
					$back = $bdd->query("SELECT COUNT(id) AS nb,SUM(price) AS sm FROM documents d WHERE type='bre' AND trash='1'".$req);
					$row = $back->fetch();
					?>
				<a href="bre.php">
					<strong>Bons de réception</strong>
					<span>Nb: <b><?php echo $row['nb'];?></b></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span>Valeur: <b><?php echo number_format((float)$row['sm'],2,"."," ");?> <?php echo $settings['currency'];?></b></span> 
				</a>
					<?php
				}
				?>
				<div class="lx-clear-fix"></div>
				<?php
			}
			
			if($_POST['action'] == "loadca"){
				$multicompanies = str_replace("company","d.company",$multicompanies);
				$datestart = time() - (60*60*24*30);
				$dateend = time();
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
				}
				$dates = "";
				$nbdocs = "";
				$value = "";
				$limit = ($dateend-$datestart)/$_POST['period'];
				for($i=0;$i<=ceil($limit);$i++){
					$dates .= ",".date("d/m/Y",($datestart+($i*$_POST['period'])));
					$req = "SELECT COUNT(DISTINCT d.doc) AS nb,SUM(d.tprice * (1 + (tva / 100))) AS sm FROM detailsdocuments d WHERE trash='1'";
					if($_POST['company'] != ""){
						$comp = explode(",",$_POST['company']);
						$req .= " AND (";
						for($j=0;$j<count($comp);$j++){
							if($j==0){
								$req .= "FIND_IN_SET(".$comp[$j].", d.company)";
							}
							else{
								$req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
							}
						}
						$req .= ")";
					}
					if($_POST['typedoc'] != ""){
						$req .= " AND d.typedoc IN('".str_replace(",","','",$_POST['typedoc'])."')";
					}
					if($_POST['client'] != "" AND $_POST['supplier'] != ""){
						$req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
					}
					else{
						if($_POST['client'] != ""){
							$req .= " AND d.client IN(".$_POST['client'].")";
						}
						if($_POST['supplier'] != ""){
							$req .= " AND d.supplier IN(".$_POST['supplier'].")";
						}
					}
					if($_POST['product'] != ""){
						$req .= " AND d.title IN('".str_replace(",","','",$_POST['product'])."')";
					}
					$req .= " AND (d.dateadd BETWEEN '".($datestart+($i*$_POST['period']))."' AND '".($datestart+(($i+1)*$_POST['period']))."')";
					$req .= $multicompanies;
					$back = $bdd->query($req);
					$row = $back->fetch();
					$nbdocs .= ",".$row['nb'];
					$value .= ",".number_format((float)($row['sm']!=""?$row['sm']:0),2,".","");
				}
				$dates = substr($dates,1);
				$nbdocs = substr($nbdocs,1);
				$value = substr($value,1);
				echo $dates."|".$nbdocs."|".$value;
			}
			
			if($_POST['action'] == "loadtop"){
				$multicompanies = str_replace("company","d.company",$multicompanies);
				$datestart = time() - (60*60*24*30);
				$dateend = time();
				if($_POST['datestart'] != "" AND $_POST['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_POST['datestart']));
					$dateend = strtotime(str_replace("/","-",$_POST['dateend'])) + (60*60*24);
				}
				$topname = "";
				$topvalue = "";
				$ca = "SUM(ca)";
				if($_POST['topwhat'] == "value"){
					$ca = "SUM(d.tprice * (1 + (d.tva / 100)))";
				}
				elseif($_POST['topwhat'] == "nbdocs"){
					$ca = "COUNT(DISTINCT d.doc)";
				}
				$gb = "d.client";
				if($_POST['topwho'] == "products"){
					$gb = "d.title";
				}	
				elseif($_POST['topwho'] == "suppliers"){
					$gb = "d.supplier";
				}
				$req = "";
				if($_POST['company'] != ""){
					$comp = explode(",",$_POST['company']);
					$req .= " AND (";
					for($j=0;$j<count($comp);$j++){
						if($j==0){
							$req .= "FIND_IN_SET(".$comp[$j].", d.company)";
						}
						else{
							$req .= " OR FIND_IN_SET(".$comp[$j].", d.company)";
						}
					}
					$req .= ")";
				}
				if($_POST['typedoc'] != ""){
					$req .= " AND d.typedoc IN('".str_replace(",","','",$_POST['typedoc'])."')";
				}
				if($_POST['client'] != "" AND $_POST['supplier'] != ""){
					$req .= " AND (d.client IN(".$_POST['client'].") OR d.supplier IN(".$_POST['supplier']."))";
				}
				else{
					if($_POST['client'] != ""){
						$req .= " AND d.client IN(".$_POST['client'].")";
					}
					if($_POST['supplier'] != ""){
						$req .= " AND d.supplier IN(".$_POST['supplier'].")";
					}
				}
				if($_POST['product'] != ""){
					$req .= " AND d.title IN('".str_replace(",","','",$_POST['product'])."')";
				}
				$req .= $multicompanies;
				if($_POST['topwho'] == "clients"){
					$req .= " AND d.client<>'0'";
				}
				elseif($_POST['topwho'] == "suppliers"){
					$req .= " AND d.supplier<>'0'";
				}	
				$req = "SELECT ".$ca." AS ca,".$gb." FROM detailsdocuments d WHERE (d.dateadd BETWEEN ".$datestart." AND ".$dateend.")".$req." GROUP BY ".$gb." ORDER BY ".$ca." DESC LIMIT 0,10";
				$back = $bdd->query($req);
				while($row = $back->fetch()){
					$column = "fullname";
					if($_POST['topwho'] != "clients"){
						$column = "title";
					}
					$where = "";
					if($_POST['topwho'] == "clients"){
						$where = " AND id='".$row['client']."'";
					}
					if($_POST['topwho'] == "suppliers"){
						$where = " AND id='".$row['supplier']."'";
					}
					if($_POST['topwho'] == "products"){
						$_POST['topwho'] = "detailsdocuments";
					}				
					$back1 = $bdd->query("SELECT ".$column." AS title FROM ".$_POST['topwho']." WHERE 1=1".$where);
					$row1 = $back1->fetch();
					$topname .= ",".($_POST['topwho']=="detailsdocuments"?$row['title']:$row1['title']);
					$topvalue .= ",".number_format((float)($row['ca']),2,".","");
				}
				$topname = substr($topname,1);
				$topvalue = substr($topvalue,1);
				echo $topname."|".$topvalue;
			}
			
			if($_POST['action'] == "changestate"){
				if($_POST['table'] == "projects"){
					if($_POST['state'] == "off"){
						$req = $bdd->prepare("UPDATE projects SET dateend='' WHERE id='".$_POST['id']."'");
						$req->execute();
					}
				}
				$req = $bdd->prepare("UPDATE `".$_POST['table']."` SET `".$_POST['column']."`='".$_POST['state']."' WHERE id='".$_POST['id']."'");
				$req->execute();
			}

			if($_POST['action'] == "updatebulk"){
				if($_POST['state'] == "delete"){
					$req = $bdd->prepare("UPDATE `".$_POST['table']."` SET trash='0' WHERE `".$_POST['column']."` IN(".$_POST['ids'].")");
					$req->execute();
				}
				elseif($_POST['state'] == "deletepermenantly"){
					$req = $bdd->prepare("DELETE FROM `".$_POST['table']."` WHERE `".$_POST['column']."` IN(".$_POST['ids'].")");
					$req->execute();
				}
				elseif($_POST['state'] == "restore"){
					$req = $bdd->prepare("UPDATE `".$_POST['table']."` SET trash='1' WHERE `".$_POST['column']."` IN(".$_POST['ids'].")");
					$req->execute();
				}
				else{
					$backcommand = $bdd->query("SELECT id,code FROM commands WHERE id IN(".$_POST['ids'].")");
					while($command = $backcommand->fetch()){
						$req = $bdd->prepare("UPDATE commands SET state='".sanitize_vars($_POST['state'])."',dateupdate='".time()."' WHERE id='".$command['id']."'");
						$req->execute();
						$req = $bdd->prepare("INSERT INTO commandshistory(id,command,state,agent,dateadd) VALUES ('0','".$command['code']."','".sanitize_vars($_POST['state'])."','".$_SESSION['easybm_fullname']."','".time()."')");
						$req->execute();
					}
				}
			}
			
			if($_POST['action'] == "loadnotification"){
					$nbnotif = 0;
					if(preg_match("#Consulter Factures,#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['facture'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['facture'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM documents WHERE type='facture' AND state IN('Non payée','Partiellement payée') AND trash='1'".$multicompanies." AND dateadd < ".(time() - $value1));
							if($back->rowCount() > 0){
								$back1 = $bdd->query("SELECT dateadd FROM documents WHERE type='facture' AND dateadd<>'' ORDER BY dateadd ASC");
								$row1 = $back1->fetch();
							?>
					<p style="background:#ffe5e5;"><a href="factures.php?paid=Non payée,Partiellement payée&datestart=<?php echo date("d/m/Y",$row1['dateadd']);?>&dateend=<?php echo date("d/m/Y",(time() - $value1));?>"><b><?php echo $back->rowCount();?></b> Factures non payées <ins>(≥ <?php echo explode("-",$notifications['facture'])[1];?> J passés)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
					}

					if(preg_match("#Consulter Factures avoir#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['avoir'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['avoir'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM documents WHERE type='avoir' AND state IN('Non remboursée','Partiellement remboursée') AND trash='1'".$multicompanies." AND dateadd < ".(time() - $value1));
							if($back->rowCount() > 0){
								$back1 = $bdd->query("SELECT dateadd FROM documents WHERE type='avoir' AND dateadd<>'' ORDER BY dateadd ASC");
								$row1 = $back1->fetch();
							?>
					<p style="background:#ffe5e5;"><a href="avoirs.php?paid=Non remboursée,Partiellement remboursée&datestart=<?php echo date("d/m/Y",$row1['dateadd']);?>&dateend=<?php echo date("d/m/Y",(time() - $value1));?>"><b><?php echo $back->rowCount();?></b> Avoirs non remboursés <ins>(≥ <?php echo explode("-",$notifications['avoir'])[1];?> J passés)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
					}
						
					if(preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['caissein'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['caissein'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='0' AND type='Entrée' AND paid='0' AND trash='1' AND datepaid=''".$companies." AND datedue<>'' AND datedue < ".(time() - $value1));
							if($back->rowCount() > 0){
								$back1 = $bdd->query("SELECT dateadd FROM payments WHERE dateadd<>'' ORDER BY dateadd ASC");
								$row1 = $back1->fetch();	
							?>
					<p style="background:#ffe5e5;"><a href="payments.php?statusid=a4a,a11a&status=Réglements reçus échus non encaissés&dateduestart=<?php echo date("d/m/Y",$row1['dateadd']);?>&datedueend=<?php echo date("d/m/Y",(time() - $value1));?>"><b><?php echo $back->rowCount();?></b> Réglements reçus échus non encaissés <ins>(≥ <?php echo explode("-",$notifications['caissein'])[1];?> J passés)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
						$value = intval(explode("-",$notifications['rcaissein'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['rcaissein'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='0' AND type='Entrée' AND paid='0' AND trash='1' AND datepaid=''".$companies." AND datedue<>'' AND datedue > ".strtotime(date("d-m-Y",time()+86400))." AND datedue < ".(time() + $value));
							if($back->rowCount() > 0){
							?>
					<p style="background:#fff6e5;"><a href="payments.php?statusid=a1a,a11a&status=Réglements reçus non échus&dateduestart=<?php echo date("d/m/Y",time()+86400);?>&datedueend=<?php echo date("d/m/Y",(time() + $value));?>"><b><?php echo $back->rowCount();?></b> Réglements reçus seront échues dans <ins>(≤ <?php echo explode("-",$notifications['rcaissein'])[1];?> J restants)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
					}

					if(preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['caisseout'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['caisseout'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='0' AND type='Sortie' AND trash='1' AND paid='0' AND datepaid=''".$companies." AND datedue<>'' AND datedue < ".(time() - $value1));
							if($back->rowCount() > 0){
								$back1 = $bdd->query("SELECT dateadd FROM payments WHERE dateadd<>'' ORDER BY dateadd ASC");
								$row1 = $back1->fetch();
							?>
					<p style="background:#ffe5e5;"><a href="payments.php?statusid=a5a,a11a&status=Réglements émis échus non décaissés&dateduestart=<?php echo date("d/m/Y",$row1['dateadd']);?>&datedueend=<?php echo date("d/m/Y",(time() - $value1));?>"><b><?php echo $back->rowCount();?></b> Réglements émis échus non décaissés <ins>(≥ <?php echo explode("-",$notifications['caisseout'])[1];?> J passés)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
						$value = intval(explode("-",$notifications['rcaisseout'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['rcaisseout'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='0' AND type='Sortie' AND trash='1' AND paid='0' AND datepaid=''".$companies." AND datedue<>'' AND datedue > ".strtotime(date("d-m-Y",time()+86400))." AND datedue < ".(time() + $value));
							if($back->rowCount() > 0){
							?>
					<p style="background:#fff6e5;"><a href="payments.php?statusid=a2a,a11a&status=Réglements émis non échus&dateduestart=<?php echo date("d/m/Y",time()+86400);?>&datedueend=<?php echo date("d/m/Y",(time() + $value));?>"><b><?php echo $back->rowCount();?></b> Réglements émis seront échues dans <ins>(≤ <?php echo explode("-",$notifications['rcaisseout'])[1];?> J restants)</ins></a></p>
							<?php
							$nbnotif++;
							}
						}
					}

					if(preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['remis'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['remis'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='0' AND type='Entrée' AND trash='1' AND paid='0' AND modepayment IN('Chèque','Effet') AND dateremis=''".$companies." AND datepaid='' AND datedue<>'' AND datedue < ".(time() - $value1));
							if($back->rowCount() > 0){
								$back1 = $bdd->query("SELECT dateadd FROM payments WHERE dateadd<>'' ORDER BY dateadd ASC");
								$row1 = $back1->fetch();
							?>
					<p style="background:#ffe5e5;"><a href="payments.php?modepayment=Chèque,Effet&statusid=a3a,a11a&status=Chèques / effets échus non remis&dateduestart=<?php echo date("d/m/Y",$row1['dateadd']);?>&datedueend=<?php echo date("d/m/Y",(time() - $value1));?>"><b><?php echo $back->rowCount();?></b> Chèques / effets échus non remis</a></p>
							<?php
							$nbnotif++;
							}
						}
					}
					
					if(preg_match("#Consulter Trésorerie#",$_SESSION['easybm_roles'])){
						$value1 = intval(explode("-",$notifications['unpaid'])[1])*getHoursPassedToday()*60*60;
						$checked = explode("-",$notifications['unpaid'])[0];
						if($checked == "on"){
							$back = $bdd->query("SELECT id FROM payments WHERE paid='1' AND trash='1'".$companies);
							if($back->rowCount() > 0){
							?>
					<p style="background:#ffe5e5;"><a href="payments.php?statusid=a8a&status=Chèques / effets impayés"><b><?php echo $back->rowCount();?></b> Chèques / effets impayés</a></p>
							<?php
							$nbnotif++;
							}
						}
					}
					?>
					<del style="display:none;" class="lx-notifications-nb"><?php echo $nbnotif;?></del>
					<?php
			}
		}
	}
}

function sanitize_vars($var){
	if(preg_match("#script|select|update|delete|concat|create|table|union|length|show_table|mysql_list_tables|mysql_list_fields|mysql_list_dbs#i",$var)){
		$var = "";
	}
	return htmlspecialchars(addslashes(trim($var)));
}

function random(){
	$alphabet = "0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($j = 0; $j < 12; $j++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass);
}

function randomfct(){
	$alphabet = "0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($j = 0; $j < 4; $j++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass);
}

function removeParentheses($input) {
    // Use a regular expression to remove parentheses and their contents
    $output = preg_replace('/\([^)]*\)/', '', $input);
    
    // Remove any extra spaces that may result from the removal
    $output = trim($output);
    
    return $output;
}

function getDateNow($date){
	if($date === date("d/m/Y")){
		$dateorder = time();
	}
	else{
		$dateorder = strtotime(str_replace("/","-",$date)) + (60*60*8);
	}
	return $dateorder;
}

function getHoursPassedToday() {
    // Create a DateTime object for the current time in the server's timezone
    $now = new DateTime('now');
    
    // Get the current hour in 24-hour format
    $currentHour = (int)$now->format('G');
    
    return $currentHour+1;
}
?>
