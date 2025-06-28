<?php
session_start();
include("config.php");
require_once __DIR__ . '/vendor/autoload.php';

ob_start();
?>
<!DOCTYPE html>
<html lang="zxx">
	<head>
		<meta charset="utf-8">
		<style>
			@page {
				margin:0px;
				header: html_MyCustomHeader;
				footer: html_MyCustomFooter;
			}
			.lx-clear-fix{
				clear:both;
			}
			.lx-caisse-total{
				float:right;
				width:200px;
				margin-bottom:20px;
				text-align:center;
				color:#FFFFFF;
				background:#828282;
				border-radius:6px;
			}
			.lx-caisse-total h3{
				font-size:16px;
				margin:0px;
				padding:8px;
			}
			.lx-caisse-total p{
				font-size:16px;
				margin:0px;
				padding:20px;
				background:rgba(255,255,255,0.2);
			}
			.lx-caisse-total span{
				float:left;
				display:block;
				width:160px;
				padding:10px;
				font-weight:bold;
				font-size:14px;
			}
			.lx-caisse-total strong{
				float:right;
				display:block;
				width:160px;
				padding:10px;
				font-size:14px;
			}
		</style>
	</head>
	<body>
		<div style="padding:20px;font-family:'Arial';">
			<h3 style="margin-bottom:30px;text-align:center;">Historique des paiements</h3>
				<table style="border-collapse:collapse;width:100%;font-size:8px;font-family:'Arial';text-align:center;">
					<tr>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Date <i class="fa fa-sort" data-sort="dateadd"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Société <i class="fa fa-sort" data-sort="company"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Référence <i class="fa fa-sort" data-sort="code"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Nature <i class="fa fa-sort" data-sort="type"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Mode de paiement <i class="fa fa-sort" data-sort="modepayment"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Montant <i class="fa fa-sort" data-sort="price"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Libellé <i class="fa fa-sort" data-sort="title"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Note <i class="fa fa-sort" data-sort="note"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Date d'écheance <i class="fa fa-sort" data-sort="datedue"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Client <i class="fa fa-sort" data-sort="client"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Fournisseur <i class="fa fa-sort" data-sort="supplier"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Imputation comptable <i class="fa fa-sort" data-sort="imputation"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Compte banquaire encaissement / décaissement <i class="fa fa-sort" data-sort="rib"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Date d'encaissement / décaissement (Rapprochement): <i class="fa fa-sort" data-sort="datepaid"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Remis <i class="fa fa-sort" data-sort="remis"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Impayé <i class="fa fa-sort" data-sort="paid"></i></td>
						<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Utilisateur <i class="fa fa-sort" data-sort="worker"></i></td>
					</tr>
					<?php
					$req = "SELECT * FROM payments WHERE trash='1'".$companies.$projects;
					if($_GET['keyword'] != ""){
						$req .= " AND (code LIKE '%".sanitize_vars($_GET['keyword'])."%' OR nature LIKE '%".sanitize_vars($_GET['keyword'])."%' OR title LIKE '%".sanitize_vars($_GET['keyword'])."%' OR description LIKE '%".sanitize_vars($_GET['keyword'])."%' OR type LIKE '%".sanitize_vars($_GET['keyword'])."%' OR type LIKE '%".sanitize_vars($_GET['keyword'])."%' OR price LIKE '%".sanitize_vars($_GET['keyword'])."%' OR imputation LIKE '%".sanitize_vars($_GET['keyword'])."%')";
					}
					if($_GET['company'] != ""){
						$comp = explode(",",$_GET['company']);
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
					if($_GET['status'] != ""){
						if($_GET['status'] != "a11a"){
							$req .= " AND (1=0 ";
						}
						else{
							$req .= " AND (1=1 ";
						}
						if(preg_match("#a1a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datepaid='')";
						}
						if(preg_match("#a2a#",$_GET['status'])){
							$req .= " OR (type='Sortie' AND datepaid='')";
						}
						if(preg_match("#a3a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datedue<".time()." AND dateremis='')";
						}
						if(preg_match("#a14a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datedue>".time()." AND dateremis='')";
						}
						if(preg_match("#a4a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datedue<>'' AND datedue<".time()." AND datepaid='')";
						}
						if(preg_match("#a5a#",$_GET['status'])){
							$req .= " OR (type='Sortie' AND datedue<>'' AND datedue<".time()." AND datepaid='')";
						}
						if(preg_match("#a6a#",$_GET['status'])){
							$req .= " OR (dateremis<>'')";
						}
						if(preg_match("#a7a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datepaid<>'')";
						}
						if(preg_match("#a12a#",$_GET['status'])){
							$req .= " OR (type='Sortie' AND datepaid<>'')";
						}
						if(preg_match("#a8a#",$_GET['status'])){
							$req .= " OR (paid='1')";
						}
						if(preg_match("#a9a#",$_GET['status'])){
							$req .= " OR (type='Entrée' AND datedue>".time().")";
						}
						if(preg_match("#a13a#",$_GET['status'])){
							$req .= " OR (type='Sortie' AND datedue>".time().")";
						}
						if(preg_match("#a10a#",$_GET['status'])){
							$req .= " OR (datedue='')";
						}
						if(preg_match("#a11a#",$_GET['status'])){
							$req .= ") AND paid='0'";
						}
						else{
							$req .= ")";
						}
					}
					if($_GET['user'] != ""){
						$req .= " AND user IN (".$_GET['user'].")";
					}
					if($_GET['client'] != ""){
						$req .= " AND client IN (".$_GET['client'].")";
					}
					if($_GET['supplier'] != ""){
						$req .= " AND supplier IN (".$_GET['supplier'].")";
					}
					if($_GET['type'] != ""){
						$req .= " AND type IN ('".str_replace(",","','",$_GET['type'])."')";
					}
					if($_GET['modepayment'] != ""){
						$req .= " AND modepayment IN('".str_replace(",","','",$_GET['modepayment'])."')";
					}
					if($_GET['pricemin'] != "" AND $_GET['pricemax'] != ""){
						$req .= " AND (price BETWEEN ".$_GET['pricemin']." AND ".$_GET['pricemax'].")";
					}
					if($_GET['datestart'] != "" AND $_GET['dateend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_GET['datestart']));
						$dateend = strtotime(str_replace("/","-",$_GET['dateend'])) + (60*60*24) - 1;
						$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_GET['dateduestart'] != "" AND $_GET['datedueend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_GET['dateduestart']));
						$dateend = strtotime(str_replace("/","-",$_GET['datedueend'])) + (60*60*24) - 1;
						$req .= " AND (datedue BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_GET['datepaidstart'] != "" AND $_GET['datepaidend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_GET['datepaidstart']));
						$dateend = strtotime(str_replace("/","-",$_GET['datepaidend'])) + (60*60*24) - 1;
						$req .= " AND (datepaid BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_GET['dateremisstart'] != "" AND $_GET['dateremisend'] != ""){
						$datestart = strtotime(str_replace("/","-",$_GET['dateremisstart']));
						$dateend = strtotime(str_replace("/","-",$_GET['dateremisend'])) + (60*60*24) - 1;
						$req .= " AND (dateremis BETWEEN ".$datestart." AND ".$dateend.")";
					}
					if($_GET['sortby'] != ""){
						$req .= " ORDER BY ".$_GET['sortby'];
					}
					else{
						$req .= " ORDER BY id";
					}
					$req .= " ".$_GET['orderby'];
					$back = $bdd->query($req);
					$nbin = 0;
					$totalin = 0;
					$nbout = 0;
					$totalout = 0;
					while($row = $back->fetch()){
						?>
					<tr style="<?php echo $row['paid']=="1"?"background:rgba(255,0,0,0.1);":"";?>">
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;"><?php echo ($row['dateadd']!=""?gmdate("d/m/Y H:i",3600+$row['dateadd']):"&mdash;");?></td>
						<?php
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id='".$row['company']."'");
						$row1 = $back1->fetch();
						?>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row1['rs'];?></td>						
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row['code'];?></td>
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
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;<?php echo $color;?>;font-weight:bold;"><?php echo $row['type'];?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row['modepayment'];?></td>
						<td style="text-align:right;padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;width:80px;<?php echo $background;?>">
							<?php echo number_format((float)$row['price'],2,"."," ")." ".$settings['currency'];?> TTC
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row['title'];?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row['note'];?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;<?php echo (($row['datepaid']=="" AND $row['datedue']!="" AND $row['datedue']<=time())?"color:orange;font-weight:bold;;":"");?>"><?php echo ($row['datedue']!=""?gmdate("d/m/Y",3600+$row['datedue']):"&mdash;");?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">	
							<?php
							$back1 = $bdd->query("SELECT code,fullname FROM clients WHERE id='".$row['client']."'");
							$client = $back1->fetch();
							if($row['client'] != "0"){
								echo $client['fullname']." (".$client['code'].")";
							}
							?>
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">	
							<?php
							$back1 = $bdd->query("SELECT title FROM suppliers WHERE id='".$row['supplier']."'");
							$supplier = $back1->fetch();
							if($row['supplier'] != "0"){
								echo $supplier['title'];
							}
							?>
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;"><?php echo $row['imputation'];?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">	
							<?php
							$back1 = $bdd->query("SELECT * FROM bankaccounts WHERE id='".$row['rib']."'");
							$rib = $back1->fetch();
							if($row['rib'] != "0"){
								?>
							<?php echo $rib['bank']." | ".$rib['rib'];?>
								<?php
							}
							?>
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;<?php echo ($row['datepaid']!=""?"color:#7EC855;font-weight:bold;":"");?>"><?php echo ($row['datepaid']!=""?gmdate("d/m/Y",3600+$row['datepaid']):"&mdash;");?></td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">
							<?php
							if($row['remis'] == '1'){
								?>
						Remis<br />
						Le: <?php echo gmdate("d/m/Y",$row['dateremis']+3600)?>
								<?php
							}
							?>
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">
							<?php
							if($row['paid'] == '1'){
								echo "Impayé";
							}
							?>
						</td>
						<td style="padding:4px;border-bottom:1px solid #000000;border-right:1px solid #000000;">
							<?php
							$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
							$user = $back1->fetch();
							?>
							<?php echo $user['fullname'];?>
						</td>
					</tr>
						<?php
					}
					?>
				<tr>
					<td style="height:30px;"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Total entrée</td>
					<td style="padding:4px;border:1px solid #000000;background:#a2d695;text-align:right;"><?php echo number_format((float)$totalin,2,"."," ")." ".$settings['currency'];?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="padding:4px;border:1px solid #000000;font-weight:bold;">Total sortie</td>
					<td style="padding:4px;border:1px solid #000000;background:#ff7373;text-align:right;"><?php echo number_format((float)$totalout,2,"."," ")." ".$settings['currency'];?></td>
				</tr>
			</table>
		</div>
	</body>
</html>
<?php
$content = ob_get_clean();
$mpdf = new \mPDF('c','A4-L');
$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont = true;
$mpdf->WriteHTML($content);
$mpdf->Output("CAISSE.pdf",'D');

function sanitize_vars($var){
	if(preg_match("#script|select|update|delete|concat|create|table|union|length|show_table|mysql_list_tables|mysql_list_fields|mysql_list_dbs#i",$var)){
		$var = "";
	}
	return htmlspecialchars(addslashes(trim($var)));
}
?>