<?php
session_start();
header("Content-Type: application/vnd.ms-excel; charset=utf-8"); 
header('Content-Disposition: attachment;filename="'.$_GET['table'].'-'.date('d/m/Y').'.xls"'); 
include('config.php');
?>
<html>
	<head>
		<meta charset='UTF-8'>
		<style>
			.text{
			  mso-number-format:"\@";/*force text*/
			}
			td{
				white-space:nowrap;
			}
		</style>
	</head>
	<body>
		<table cellpadding="0" cellspacing="0" border="1">
			<tr align="center" valign="top">
				<?php
				$columntitles = explode(",",$_GET['columntitles']);
				$columns = explode(",",$_GET['columns']);
				for($i=1;$i<count($columntitles);$i++){
					?>
				<td style="font-weight:bold;background:#fb8500;"><?php echo $columntitles[$i]?></td>
					<?php						
				}
				?>
			</tr>
			<?php
			$req = "SELECT * FROM ".$_GET['table']." tb WHERE 1=1";
			if($_GET['table'] == "clients"){
				if($_GET['keyword'] != ""){
					$req .= " AND (code LIKE '%".$_GET['keyword']."%' OR note LIKE '%".$_GET['keyword']."%' OR ice LIKE '%".sanitize_vars($_GET['keyword'])."%' OR fullname LIKE '%".sanitize_vars($_GET['keyword'])."%' OR phone LIKE '%".sanitize_vars($_GET['keyword'])."%' OR address LIKE '%".sanitize_vars($_GET['keyword'])."%' OR email LIKE '%".sanitize_vars($_GET['keyword'])."%')";
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
				if($_GET['paid'] == "paid"){
					$req .= " AND id IN(SELECT client FROM documents WHERE state IN('Payée','Remboursée') AND type IN('facture','avoir'))";
				}
				elseif($_GET['paid'] == "encours"){
					$req .= " AND id IN(SELECT client FROM documents WHERE state NOT IN('Payée','Remboursée') AND type IN('facture','avoir'))";
				}			
			}
			elseif($_GET['table'] == "suppliers"){
				if($_GET['keyword'] != ""){
					$req .= " AND (code LIKE '%".$_GET['keyword']."%' OR respemail LIKE '%".$_GET['keyword']."%' OR note LIKE '%".$_GET['keyword']."%' OR title LIKE '%".sanitize_vars($_GET['keyword'])."%' OR respname LIKE '%".sanitize_vars($_GET['keyword'])."%' OR respphone LIKE '%".sanitize_vars($_GET['keyword'])."%')";
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
				if($_GET['paid'] == "paid"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state IN('Payée') AND type IN('bc'))";
				}
				elseif($_GET['paid'] == "encours"){
					$req .= " AND id IN(SELECT supplier FROM documents WHERE state NOT IN('Payée') AND type IN('bc'))";
				}
			}
			elseif($_GET['table'] == "companies"){
				if($_GET['keyword'] != ""){
					$req .= " AND (rs LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR phone LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR address LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR email LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR website LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR rc LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR patente LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR iff LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR cnss LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR ice LIKE '%".sanitize_vars($_GET['keyword'])."%')";
				}			
			}
			elseif($_GET['table'] == "documents"){
				if($_GET['typedoc'] != ""){
					$req .= " AND type='".$_GET['typedoc']."'";
				}
				if($_GET['keyword'] != ""){
					$req .= " AND (code LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR note LIKE '%".sanitize_vars($_GET['keyword'])."%' 
									OR modepayment LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR conditions LIKE '%".sanitize_vars($_GET['keyword'])."%'
									OR abovetable LIKE '%".sanitize_vars($_GET['keyword'])."%')";
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
				if($_GET['client'] != ""){
					$req .= " AND client IN(".$_GET['client'].")";
				}
				if($_GET['user'] != ""){
					$req .= " AND user IN(".$_GET['user'].")";
				}
				if($_GET['product'] != ""){
					$req .= " AND id IN(SELECT doc FROM detailsdocuments WHERE title IN('".str_replace(",","','",$_GET['product'])."'))";
				}				
				if($_GET['statee'] != ""){
					$req .= " AND state IN ('".str_replace(",","','",$_GET['statee'])."')";
				}
				if($_GET['pricemin'] != "" AND $_GET['pricemax'] != ""){
					$req .= " AND (price BETWEEN ".$_GET['pricemin']." AND ".$_GET['pricemax'].")";
				}					
				if($_GET['datestart'] != "" AND $_GET['dateend'] != ""){
					$datestart = strtotime(str_replace("/","-",$_GET['datestart']));
					$dateend = strtotime(str_replace("/","-",$_GET['dateend'])) + (60*60*24) - 1;
					$req .= " AND (dateadd BETWEEN ".$datestart." AND ".$dateend.")";
				}
			}
			elseif($_GET['table'] == "payments"){
				if($_GET['keyword'] != ""){
					$req .= " AND (nremise LIKE '%".sanitize_vars($_GET['keyword'])."%' OR code LIKE '%".sanitize_vars($_GET['keyword'])."%' OR title LIKE '%".sanitize_vars($_GET['keyword'])."%' OR note LIKE '%".addslashes($_GET['keyword'])."%' OR type LIKE '%".sanitize_vars($_GET['keyword'])."%' OR price LIKE '%".sanitize_vars($_GET['keyword'])."%' OR imputation LIKE '%".sanitize_vars($_GET['keyword'])."%')";
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
						$req .= " OR (type='Entrée' AND datedue<>'' AND datepaid='' AND datedue<".time()." AND dateremis='')";
					}
					if(preg_match("#a14a#",$_GET['status'])){
						$req .= " OR (type='Entrée' AND datedue<>'' AND datepaid='' AND datedue>".time()." AND dateremis='')";
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
					if(preg_match("#a15a#",$_GET['status'])){
						$req .= " OR (datepaid<>'')";
					}
					if(preg_match("#a16a#",$_GET['status'])){
						$req .= " OR (datepaid='')";
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
				if($_GET['imputation'] != ""){
					$req .= " AND imputation IN('".str_replace(",","','",$_GET['imputation'])."')";
				}
				if($_GET['rib'] != ""){
					$req .= " AND rib IN (".$_GET['rib'].")";
				}
				if($_GET['supplier'] != ""){
					$req .= " AND supplier IN (".$_GET['supplier'].")";
				}
				if($_GET['type'] != ""){
					$req .= " AND type IN('".str_replace(",","','",$_GET['type'])."')";
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
			}
			$back = $bdd->query($req);
			while($row = $back->fetch()){
				$price1 = 0;
				$price2 = 0;
				$discount = 0;
				$paid = 0;
				$rest = 0;
				?>
			<tr valign="top">
				<?php
				for($i=1;$i<count($columns);$i++){
					if($_GET['table'] == "clients" AND $columns[$i] == "ca"){
						$back1 = $bdd->query("SELECT SUM(CASE WHEN type='facture' THEN price WHEN type='avoir' THEN price*(-1) ELSE 0 END) AS ca FROM documents WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca = $row1['ca'];	
						$back1 = $bdd->query("SELECT SUM(CASE WHEN typedoc='facture' THEN price WHEN typedoc='avoir' THEN price*(-1) ELSE 0 END) AS paid FROM payments WHERE client='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid = $row1['paid'];
						$rest = ($ca - $row1['paid']);
						?>
				<td><?php echo $ca;?></td>
						<?php						
					}
					elseif($_GET['table'] == "clients" AND $columns[$i] == "paid"){
						?>
				<td><?php echo $paid;?></td>
						<?php						
					}
					elseif($_GET['table'] == "clients" AND $columns[$i] == "encours"){
						?>
				<td><?php echo $rest;?></td>
						<?php						
					}
					elseif($_GET['table'] == "suppliers" AND $columns[$i] == "ca"){
						$back1 = $bdd->query("SELECT SUM(price) AS ca FROM documents WHERE type='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$ca = $row1['ca'];
						$back1 = $bdd->query("SELECT SUM(price) AS paid FROM payments WHERE typedoc='bc' AND supplier='".$row['id']."'");
						$row1 = $back1->fetch();
						$paid = $row1['paid'];
						$rest = ($ca - $row1['paid']);
						?>
				<td><?php echo $ca;?></td>
						<?php						
					}
					elseif($_GET['table'] == "suppliers" AND $columns[$i] == "paid"){
						?>
				<td><?php echo $paid;?></td>
						<?php						
					}
					elseif($_GET['table'] == "suppliers" AND $columns[$i] == "encours"){
						?>
				<td><?php echo $rest;?></td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "client"){
						$back1 = $bdd->query("SELECT fullname,code FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
				<td><?php echo $row1['fullname']." (".$row1['code'].")";?></td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "products"){
						?>
				<td>
					<?php
					$back1 = $bdd->query("SELECT * FROM detailsdocuments WHERE doc='".$row['id']."' AND typedoc='".$_GET['typedoc']."' AND trash='1' ORDER BY title");
					while($row1 = $back1->fetch()){
						?>
					|| <?php echo $row1['title']." (<b>".number_format((float)($row1['uprice']*(1+($row1['tva']/100))),2,".","")." ".$settings['currency']."</b> TTC) x <b>".$row1['qty']." ".$row1['unit']."</b>";?>
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
					}
					?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "avoir"){
						?>
				<td>
						<?php
						$back1 = $bdd->query("SELECT id,code,type FROM documents WHERE type='avoir' AND correctdoc='".$row['code']."' AND company='".$row['company']."'");
						if($back1->rowCount() > 0){
						?>
						Avoirs:
						<?php
						}
						while($row1 = $back1->fetch()){
							echo $row1['code'] ." / ";
						}						
						$back1 = $bdd->query("SELECT id,code,type FROM documents WHERE type='br' AND correctdoc='".$row['code']."' AND company='".$row['company']."'");
						if($back1->rowCount() > 0){
						?>
						|| Bons de retour:
						<?php
						}
						while($row1 = $back1->fetch()){
							echo $row1['code'] ." / ";
						}						
					?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "price1"){
						?>
				<td>
					<?php echo number_format((float)abs($price1),2,",","");?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "price2"){
						?>
				<td>
					<?php echo number_format((float)abs($price2),2,",","");?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "discount"){
						?>
				<td>
					<?php echo number_format((float)abs($discount),2,",","");?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "documents" AND $columns[$i] == "rest"){
						$smpaid = 0;
						$phtable = "";
						$back1 = $bdd->query("SELECT * FROM payments WHERE doc='".$row['id']."'");
						while($row1 = $back1->fetch()){
							$smpaid += $row1['price'];
						}
						?>
				<td>
					<?php echo number_format((float)abs($price2-$smpaid),2,",","");?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "payments" AND $columns[$i] == "type"){
						?>
				<td>
					<span style="font-weight:600;<?php echo $row['type']=="Entrée"?"color:#a2d695;":"color:#ff7373;";?>"><?php echo $row['type'];?></span>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "payments" AND $columns[$i] == "price"){
						?>
				<td>
					<span style="font-weight:600;<?php echo $row['type']=="Entrée"?"color:#a2d695;":"color:#ff7373;";?>"><?php echo number_format((float)$row['price'],2,",","");?></span>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "payments" AND $columns[$i] == "remis"){
						?>
				<td>
						<?php
						if($row['remis'] == '1'){
							?>
						<span style="font-weight:500;color:#fb8500;">Remis</span>
						<span style="white-space:nowrap;">Le: <?php echo date("d/m/Y",$row['dateremis']);?></span>
							<?php
							if($row['nremise'] != ""){
							?>
						<span>(N° de remise: <?php echo $row['nremise'];?>)</span>
							<?php
							}
						}
						?>
				</td>
						<?php						
					}
					elseif($_GET['table'] == "payments" AND $columns[$i] == "paid"){
						?>
				<td>
						<?php
						if($row['paid'] == '1'){
							?>
						<span style="font-weight:500;color:#FF0000;">Impayé</span>
							<?php
						}
						?>
				</td>
						<?php						
					}
					elseif($columns[$i] == "dateadd"){
						?>
				<td><?php echo date("d/m/Y H:i",$row['dateadd']);?></td>
						<?php						
					}
					elseif($columns[$i] == "datedue"){
						?>
				<td><span style="font-weight:500;<?php echo (($row['datepaid']=="" AND $row['datedue']!="" AND $row['datedue']<=time())?"color:orange;":"");?>border-radius:4px;"><ins><?php echo ($row['datedue']!=""?date("d/m/Y",$row['datedue']):"&mdash;");?></ins></span></td>
						<?php						
					}
					elseif($columns[$i] == "datepaid"){
						?>
				<td><span style="font-weight:500;<?php echo ($row['datepaid']!=""?"color:#7EC855;":"");?>border-radius:4px;"><ins><?php echo ($row['datepaid']!=""?date("d/m/Y",$row['datepaid']):"&mdash;");?></ins></span></td>
						<?php						
					}
					elseif($columns[$i] == "company"){
						?>
				<td>
						<?php
						$companylist = "";
						$back1 = $bdd->query("SELECT rs FROM companies WHERE id IN(".($row['company']!=""?$row['company']:0).") ORDER BY rs");
						while($company = $back1->fetch()){
							$companylist .= " - ".$company['rs'];
						}
						echo substr($companylist,3);
						?>
				</td>
						<?php
					}
					elseif($columns[$i] == "ice" OR $columns[$i] == "cnss"){
						?>
				<td class="text"><?php echo $row[$columns[$i]]?></td>
						<?php						
					}
					elseif($columns[$i] == "user"){
						$back1 = $bdd->query("SELECT fullname FROM users WHERE id='".$row['user']."'");
						$row1 = $back1->fetch();
						?>
				<td><?php echo $row1['fullname'];?></td>
						<?php						
					}
					elseif($columns[$i] == "rib"){
						$back1 = $bdd->query("SELECT bank,rib FROM bankaccounts WHERE id='".$row['rib']."'");
						$row1 = $back1->fetch();
						?>
				<td><?php echo $row1['rib']!=""?$row1['bank']." | ".$row1['rib'].")":"";?></td>
						<?php						
					}
					elseif($columns[$i] == "client"){
						$back1 = $bdd->query("SELECT fullname,code FROM clients WHERE id='".$row['client']."'");
						$row1 = $back1->fetch();
						?>
				<td><?php echo $row1['fullname']!=""?$row1['fullname']." (".$row1['code'].")":"";?></td>
						<?php						
					}
					elseif($columns[$i] == "supplier"){
						$back1 = $bdd->query("SELECT title,code FROM suppliers WHERE id='".$row['supplier']."'");
						$row1 = $back1->fetch();
						?>
				<td><?php echo $row1['title']!=""?$row1['title']." (".$row1['code'].")":"";?></td>
						<?php						
					}
					else{
					?>
				<td><?php echo strip_tags($row[$columns[$i]]);?></td>
					<?php
					}
				}
				?>
			</tr>
				<?php
			}
			?>
		</table>
	</body>
</html>

<?php
function sanitize_vars($var){
	if(preg_match("#script|select|update|delete|concat|create|table|union|length|show_table|mysql_list_tables|mysql_list_fields|mysql_list_dbs#i",$var)){
		$var = "";
	}
	return htmlspecialchars(addslashes(trim($var)));
}
