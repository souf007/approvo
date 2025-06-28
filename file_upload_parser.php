<?php
session_start();
include("config.php");
include('classes/Utilities.class.php');
include('classes/SimpleImage.class.php');
include('PHPExcel/IOFactory.php');

if(isset($_SESSION['easybm_id']) AND isset($_SESSION['easybm_fullname'])){
	if(isset($_FILES['file0'])){
		$file = Utilities::upload($_FILES['file0'],'uploads/');
		echo $file;	
	}
	elseif(isset($_FILES['file1'])){
		$file = Utilities::upload($_FILES['file1'],'uploads/');
		if($_POST['table'] == "products"){
			$req = $bdd->prepare("UPDATE products SET pictures=CONCAT(pictures,',','".$file."') WHERE id='".$_POST['id']."'");
		}
		elseif($_POST['table'] == "companies" AND $_POST['column'] == "logo1"){
			$back = $bdd->query("SELECT logo1 FROM companies WHERE id='".$_POST['id']."'");
			$row = $back->fetch();
			if(file_exists("uploads/".$row['logo1'])){
				unlink("uploads/".$row['logo1']);
			}
			if(file_exists("uploads/micro_".$row['logo1'])){
				unlink("uploads/micro_".$row['logo1']);
			}
			if(file_exists("uploads/cropped_".$row['logo1'])){
				unlink("uploads/cropped_".$row['logo1']);
			}
			if(file_exists("uploads/small_".$row['logo1'])){
				unlink("uploads/small_".$row['logo1']);
			}
			if(file_exists("uploads/large_".$row['logo1'])){
				unlink("uploads/large_".$row['logo1']);
			}
			$req = $bdd->prepare("UPDATE companies SET logo1='".$file."' WHERE id='".$_POST['id']."'");
		}
		elseif($_POST['table'] == "companies" AND $_POST['column'] == "signature"){
			$back = $bdd->query("SELECT signature FROM companies WHERE id='".$_POST['id']."'");
			$row = $back->fetch();
			if(file_exists("uploads/".$row['signature'])){
				unlink("uploads/".$row['signature']);
			}
			if(file_exists("uploads/micro_".$row['signature'])){
				unlink("uploads/micro_".$row['signature']);
			}
			if(file_exists("uploads/cropped_".$row['signature'])){
				unlink("uploads/cropped_".$row['signature']);
			}
			if(file_exists("uploads/small_".$row['signature'])){
				unlink("uploads/small_".$row['signature']);
			}
			if(file_exists("uploads/large_".$row['signature'])){
				unlink("uploads/large_".$row['signature']);
			}
			$req = $bdd->prepare("UPDATE companies SET signature='".$file."' WHERE id='".$_POST['id']."'");
		}
		$req->execute();
	}
	elseif(isset($_FILES['file2'])){
		$file = Utilities::uploadFile($_FILES['file2'],'uploads/');
		$req = $bdd->prepare("UPDATE ".$_POST['table']." SET attachments=CONCAT(attachments,',','".$file."') WHERE id='".$_POST['id']."'");
		$req->execute();
	}
	elseif(isset($_FILES['file3'])){
		$inputFileName = 'uploads/'.Utilities::uploadFile($_FILES['file3'],'uploads/');
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} 
		catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		//  Loop through each row of the worksheet in turn
		for ($row = 2; $row <= $highestRow; $row++){
			//  Read a row of data into an array	
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			$rand = "CL".gmdate("ym")."-0001";
			$back1 = $bdd->query("SELECT code FROM clients ORDER BY id DESC LIMIT 0,1");
			if($back1->rowCount() > 0){
				$row1 = $back1->fetch();
				$rand = "CL".gmdate("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
			}
			
			$company = str_replace(";",",",($rowData[0][6]));
			$back1 = $bdd->query("SELECT id FROM companies WHERE id IN(".($company!=""?$company:0).")");
			if($back1->rowCount() > 0){
				$req = $bdd->prepare("INSERT INTO clients(id,code,ice,fullname,phone,address,email,note,company,dateadd,trash) VALUES ('0','".$rand."','".sanitize_vars($rowData[0][1])."','".sanitize_vars($rowData[0][0])."','".sanitize_vars($rowData[0][2])."','".sanitize_vars($rowData[0][3])."','".sanitize_vars($rowData[0][4])."','".addslashes($rowData[0][5])."','".$company."','".time()."','1')");
				$req->execute();
			}
		}
		unlink($inputFileName);	
	}
	elseif(isset($_FILES['file4'])){
		$inputFileName = 'uploads/'.Utilities::uploadFile($_FILES['file4'],'uploads/');
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} 
		catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		//  Loop through each row of the worksheet in turn
		for ($row = 2; $row <= $highestRow; $row++){
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			if($rowData[0][1] != ""){
				$back = $bdd->query("SELECT id FROM suppliers WHERE title='".sanitize_vars($rowData[0][0])."'");
				if($back->rowCount() == 0){
					$rand = "FR".date("ym")."-0001";
					$back1 = $bdd->query("SELECT code FROM suppliers WHERE code<>'' ORDER BY id DESC LIMIT 0,1");
					if($back1->rowCount() > 0){
						$row1 = $back1->fetch();
						$rand = "FR".date("ym")."-".sprintf('%04d',intval(substr($row1['code'],7))+1);
					}
					$company = str_replace(";",",",($rowData[0][6]));
					$back1 = $bdd->query("SELECT id FROM companies WHERE id IN(".($company!=""?$company:0).")");
					if($back1->rowCount() > 0){
						$req = $bdd->prepare("INSERT INTO `suppliers`(`id`, `code`, `title`, `respname`, `respphone`, `respemail`, `respfax`, `note`, `company`, `dateadd`, `trash`)
						VALUES ('','".$rand."','".sanitize_vars($rowData[0][0])."','".sanitize_vars($rowData[0][1])."','".sanitize_vars($rowData[0][2])."','".sanitize_vars($rowData[0][3])."','".sanitize_vars($rowData[0][4])."','".addslashes($rowData[0][5])."','".$company."','".time()."','1')");
						$req->execute();
					}
				}				
			}
		}
		unlink($inputFileName);	
	}
	elseif(isset($_FILES['file5'])){
		$inputFileName = 'uploads/'.Utilities::uploadFile($_FILES['file5'],'uploads/');
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} 
		catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		//  Loop through each row of the worksheet in turn
		for ($row = 2; $row <= $highestRow; $row++){
			//  Read a row of data into an array	
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			if($rowData[0][0] != "" AND $rowData[0][1] != "" AND $rowData[0][2] != "" AND $rowData[0][3] != "" AND $rowData[0][4] != "" AND $rowData[0][4] != "" AND $rowData[0][5] != "" AND $rowData[0][9] != ""){
				$back = $bdd->query("SELECT id FROM products WHERE ((code='".sanitize_vars($rowData[0][7])."' AND code<>'') OR ref='".sanitize_vars($rowData[0][1])."' OR title='".sanitize_vars($rowData[0][0])."') AND type='product'");
				if($back->rowCount() == 0){
					$exactfamily['id'] = 0;
					$family = "0";
					$back = $bdd->query("SELECT id,parent FROM families WHERE id='".sanitize_vars($rowData[0][6])."'");
					if($back->rowCount() > 0){
						$exactfamily = $back->fetch();
						$back = $bdd->query("SELECT id FROM families WHERE parent='".$exactfamily['id']."'");
						if($back->rowCount() == 0){
							$back = $bdd->query("SELECT id FROM families WHERE id='".$exactfamily['parent']."'");
							if($back->rowCount() > 0){
								$family = $exactfamily['id'];
								if($exactfamily['parent'] != "0"){
									$family = $exactfamily['parent'].",".$exactfamily['id'];
									$back = $bdd->query("SELECT parent FROM families WHERE title='".$exactfamily['parent']."'");
									$row1 = $back->fetch();
									if($row1['parent'] != "0" AND $row1['parent'] != ""){
										$family = $row1['parent'].",".$family;
										$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
										$row1 = $back->fetch();
										if($row1['parent'] != "0" AND $row1['parent'] != ""){
											$family = $row1['parent'].",".$family;
											$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
											$row1 = $back->fetch();
											if($row1['parent'] != "0" AND $row1['parent'] != ""){
												$family = $row1['parent'].",".$family;
												$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
												$row1 = $back->fetch();
												if($row1['parent'] != "0" AND $row1['parent'] != ""){
													$family = $row1['parent'].",".$family;
													$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
													$row1 = $back->fetch();
													if($row1['parent'] != "0" AND $row1['parent'] != ""){
														$family = $row1['parent'].",".$family;
													}
												}
											}
										}
									}
								}
							}
							else{
								$family = $exactfamily['id'];
							}
						}
						else{
							$family = $exactfamily['id'];
						}
					}
					$bprice = 0;
					$price = sanitize_vars($rowData[0][2]);
					if(sanitize_vars($rowData[0][3]) == "HT"){
						$price = sanitize_vars($rowData[0][2])*(1+(sanitize_vars($rowData[0][4])/100));
					}
					$unit = $rowData[0][5];
					$back = $bdd->query("SELECT title FROM unites WHERE title='".sanitize_vars($rowData[0][5])."' AND type='product'");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO unites(id,title,type,trash) VALUES ('0','".sanitize_vars($rowData[0][5])."','product','1')");
						$req->execute();
					}
					else{
						$row1 = $back->fetch();
						$unit = $row1['title'];
					}
					$company = str_replace(";",",",($rowData[0][9]));
					$back1 = $bdd->query("SELECT id FROM companies WHERE id IN(".($company!=""?$company:0).")");
					if($back1->rowCount() > 0){
						$req = $bdd->prepare("INSERT INTO `products`(`id`, `company`, `code`, `brand`, `title`, `ref`, `bprice`,`bpricebase`,`btva`, `price`,`pricebase`,`tva`, `unit`, `family`, `exactfamily`, `invoiced`, `type`, `pictures`, `dateadd`, `trash`)
						VALUES ('','".sanitize_vars($company)."','".sanitize_vars($rowData[0][7])."','".sanitize_vars($rowData[0][8])."','".sanitize_vars($rowData[0][0])."','".sanitize_vars($rowData[0][1])."','".sanitize_vars($bprice)."','".sanitize_vars($rowData[0][3])."','".sanitize_vars($rowData[0][4])."','".sanitize_vars($price)."','".sanitize_vars($rowData[0][3])."','".sanitize_vars($rowData[0][4])."','".sanitize_vars($unit)."','".$family."','".$exactfamily['id']."','','product','','".time()."','1')");
						$req->execute();
					}
				}				
			}
		}
		unlink($inputFileName);	
	}
	elseif(isset($_FILES['file6'])){
		$inputFileName = 'uploads/'.Utilities::uploadFile($_FILES['file6'],'uploads/');
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} 
		catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		//  Loop through each row of the worksheet in turn
		for ($row = 2; $row <= $highestRow; $row++){
			//  Read a row of data into an array	
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			if($rowData[0][0] != "" AND $rowData[0][1] != "" AND $rowData[0][2] != "" AND $rowData[0][3] != "" AND $rowData[0][4] != "" AND $rowData[0][5] != "" AND $rowData[0][6] != "" AND $rowData[0][10] != ""){
				$back = $bdd->query("SELECT id FROM products WHERE (title='".sanitize_vars($rowData[0][0])."' OR ref='".sanitize_vars($rowData[0][1])."') AND type='service'");
				if($back->rowCount() == 0){
					$exactfamily['id'] = 0;
					$family = "0";
					$back = $bdd->query("SELECT id,parent FROM families WHERE id='".sanitize_vars($rowData[0][7])."'");
					if($back->rowCount() > 0){
						$exactfamily = $back->fetch();
						$back = $bdd->query("SELECT id FROM families WHERE parent='".$exactfamily['id']."'");
						if($back->rowCount() == 0){
							$back = $bdd->query("SELECT id FROM families WHERE id='".$exactfamily['parent']."'");
							if($back->rowCount() > 0){
								$family = $exactfamily['id'];
								if($exactfamily['parent'] != "0"){
									$family = $exactfamily['parent'].",".$exactfamily['id'];
									$back = $bdd->query("SELECT parent FROM families WHERE title='".$exactfamily['parent']."'");
									$row1 = $back->fetch();
									if($row1['parent'] != "0" AND $row1['parent'] != ""){
										$family = $row1['parent'].",".$family;
										$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
										$row1 = $back->fetch();
										if($row1['parent'] != "0" AND $row1['parent'] != ""){
											$family = $row1['parent'].",".$family;
											$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
											$row1 = $back->fetch();
											if($row1['parent'] != "0" AND $row1['parent'] != ""){
												$family = $row1['parent'].",".$family;
												$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
												$row1 = $back->fetch();
												if($row1['parent'] != "0" AND $row1['parent'] != ""){
													$family = $row1['parent'].",".$family;
													$back = $bdd->query("SELECT parent FROM families WHERE title='".$row1['parent']."'");
													$row1 = $back->fetch();
													if($row1['parent'] != "0" AND $row1['parent'] != ""){
														$family = $row1['parent'].",".$family;
													}
												}
											}
										}
									}
								}
							}
							else{
								$family = $exactfamily['id'];
							}
						}
						else{
							$family = $exactfamily['id'];
						}
					}
					$price = sanitize_vars($rowData[0][3]);
					if(sanitize_vars($rowData[0][4]) == "HT"){
						$price = sanitize_vars($rowData[0][3])*(1+(sanitize_vars($rowData[0][5])/100));
					}
					$unit = $rowData[0][6];
					$back = $bdd->query("SELECT title FROM unites WHERE title='".sanitize_vars($rowData[0][6])."' AND type='service'");
					if($back->rowCount() == 0){
						$req = $bdd->prepare("INSERT INTO unites(id,title,type,trash) VALUES ('0','".sanitize_vars($rowData[0][6])."','service','1')");
						$req->execute();
					}
					else{
						$row1 = $back->fetch();
						$unit = $row1['title'];
					}
					$company = str_replace(";",",",($rowData[0][10]));
					$back1 = $bdd->query("SELECT id FROM companies WHERE id IN(".($company!=""?$company:0).")");
					if($back1->rowCount() > 0){
						$req = $bdd->prepare("INSERT INTO `products`(`id`, `company`, `code`, `brand`, `title`, `ref`, `bprice`,`bpricebase`,`btva`, `price`,`pricebase`,`tva`, `unit`, `family`, `exactfamily`, `invoiced`, `type`, `pictures`, `dateadd`, `trash`)
						VALUES ('','".sanitize_vars($company)."','".sanitize_vars($rowData[0][8])."','".sanitize_vars($rowData[0][9])."','".sanitize_vars($rowData[0][0])."','".sanitize_vars($rowData[0][1])."','".sanitize_vars($rowData[0][2])."','HT','".sanitize_vars($rowData[0][5])."','".sanitize_vars($price)."','".sanitize_vars($rowData[0][4])."','".sanitize_vars($rowData[0][5])."','".sanitize_vars($unit)."','".$family."','".$exactfamily['id']."','','service','','".time()."','1')");
						$req->execute();	
						$back1 = $bdd->query("SELECT id FROM products ORDER BY id DESC");
						$row1 = $back1->fetch();
						$id = $row1['id'];		
						$bprice = $rowData[0][2]*(1+($rowData[0][5]/100));
						$req = $bdd->prepare("INSERT INTO stockpricelog(id,product,depot,price,qty,qtyoui,qtynon,restqty,restqtyoui,restqtynon,dateadd) VALUES ('0','".$id."','0','".sanitize_vars($bprice)."','2000000','1000000','1000000','2000000','1000000','1000000','".time()."')");
						$req->execute();						
					}
				}				
			}
		}
		unlink($inputFileName);	
	}
}

function sanitize_vars($var){
	return htmlspecialchars(addslashes(trim($var)));
}
?>