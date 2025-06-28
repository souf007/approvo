<?php

try{

    $bdd = new PDO('mysql:host=localhost;port=3307;dbname=u815902912_UT;charset=utf8', 'u815902912_UT', 'Misteryou2016@@', array(PDO::ATTR_PERSISTENT => true));

}

catch(Exception $e){

	die('Erreur : '.$e->getMessage());

}					



if(isset($_COOKIE['id'])){

	$_SESSION['easybm_id'] = $_COOKIE['id'];

	$_SESSION['easybm_fullname'] = $_COOKIE['fullname'];

	$_SESSION['easybm_picture'] = $_COOKIE['picture'];

	$_SESSION['easybm_phone'] = $_COOKIE['phone'];

	$_SESSION['easybm_email'] = $_COOKIE['email'];

	$_SESSION['easybm_password'] = $_COOKIE['password'];

	$_SESSION['easybm_roles'] = isset($_COOKIE['roles'])?$_COOKIE['roles']:"";

	$_SESSION['easybm_companies'] = $_COOKIE['companies'];

	$_SESSION['easybm_type'] = $_COOKIE['type'];

	$_SESSION['easybm_superadmin'] = $_COOKIE['superadmin'];

}



$websiteurl = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){ 

	$websiteurl = 'https://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

}



$user = "";

$worker = 0;

if(isset($_SESSION['easybm_type'])){

	$back = $bdd->query("SELECT * FROM parametres WHERE user='".$_SESSION['easybm_id']."'");

	$parametres = $back->fetch();	

	$back = $bdd->query("SELECT * FROM users WHERE id='".$_SESSION['easybm_id']."'");

	$row = $back->fetch();

	$_SESSION['easybm_fullname'] = $row['fullname'];

	$_SESSION['easybm_picture'] = $row['picture'];

	$_SESSION['easybm_roles'] = $row['roles'];

	$_SESSION['easybm_companies'] = "0,".$row['companies'];

	$companies = " AND company IN(".(trim($_SESSION['easybm_companies'],",")!=""?trim($_SESSION['easybm_companies'],","):0).")";

	$companiesid = " AND id IN(".(trim($_SESSION['easybm_companies'],",")!=""?trim($_SESSION['easybm_companies'],","):0).")";

	$multicompanies = "";

	if($_SESSION['easybm_companies'] != ""){

		$comp = explode(",",$_SESSION['easybm_companies']);

		$multicompanies .= " AND (";

		for($j=0;$j<count($comp);$j++){

			if($j==0){

				$multicompanies .= "FIND_IN_SET(".$comp[$j].", company)";

			}

			else{

				$multicompanies .= " OR FIND_IN_SET(".$comp[$j].", company)";

			}

		}

		$multicompanies .= ")";

	}

}



$back = $bdd->query("SELECT * FROM settings");

$settings = $back->fetch();

$back = $bdd->query("SELECT * FROM notifications");

$notifications = $back->fetch();



function chifre_en_lettre($montant, $devise1='', $devise2='')

{

    if(empty($devise1)) $dev1='Dinars'; // Default to Dinars

    else $dev1=$devise1;

    

    if(empty($devise2)) $dev2='Centimes'; // Default to Centimes

    else $dev2=$devise2;



    // Handle integer part

    $valeur_entiere = intval($montant);



    // Fix for decimal part to ensure 2 digits are handled properly

    $valeur_decimal = round(($montant - $valeur_entiere) * 100);



    $unite = [];

    $dix = [];

    $cent = [];



    // Handle decimal digits

    $dix_c = intval($valeur_decimal / 10);  // First digit after decimal

    $unite_c = intval($valeur_decimal % 10);  // Second digit after decimal



    // Handle integer part digit-wise (similar to the original logic)

    $unite[1] = $valeur_entiere % 10;

    $dix[1] = intval($valeur_entiere % 100 / 10);

    $cent[1] = intval($valeur_entiere % 1000 / 100);

    $unite[2] = intval($valeur_entiere % 10000 / 1000);

    $dix[2] = intval($valeur_entiere % 100000 / 10000);

    $cent[2] = intval($valeur_entiere % 1000000 / 100000);

    $unite[3] = intval($valeur_entiere % 10000000 / 1000000);

    $dix[3] = intval($valeur_entiere % 100000000 / 10000000);

    $cent[3] = intval($valeur_entiere % 1000000000 / 100000000);



    $chif = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];



    $secon_c = '';

    $trio_c = '';



    // Handle integer part as in your code (no change)

    for ($i = 1; $i <= 3; $i++) {

        $prim[$i] = '';

        $secon[$i] = '';

        $trio[$i] = '';

        if ($dix[$i] == 0) {

            $secon[$i] = '';

            $prim[$i] = $chif[$unite[$i]];

        } elseif ($dix[$i] == 1) {

            $secon[$i] = '';

            $prim[$i] = $chif[($unite[$i] + 10)];

        } elseif ($dix[$i] == 2) {

            $secon[$i] = ($unite[$i] == 1) ? 'vingt et' : 'vingt';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 3) {

            $secon[$i] = ($unite[$i] == 1) ? 'trente et' : 'trente';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 4) {

            $secon[$i] = ($unite[$i] == 1) ? 'quarante et' : 'quarante';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 5) {

            $secon[$i] = ($unite[$i] == 1) ? 'cinquante et' : 'cinquante';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 6) {

            $secon[$i] = ($unite[$i] == 1) ? 'soixante et' : 'soixante';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 7) {

            $secon[$i] = 'soixante';

            $prim[$i] = $chif[$unite[$i] + 10];

        } else if ($dix[$i] == 8) {

            $secon[$i] = 'quatre-vingt';

            $prim[$i] = $chif[$unite[$i]];

        } else if ($dix[$i] == 9) {

            $secon[$i] = 'quatre-vingt';

            $prim[$i] = $chif[$unite[$i] + 10];

        }

        if ($cent[$i] == 1) $trio[$i] = 'cent';

        else if ($cent[$i] != 0) $trio[$i] = $chif[$cent[$i]] . ' cents';

    }



    // Handle the second digit after decimal (unit part)

    $chif2 = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingts dix'];

    $secon_c = $chif2[$dix_c];

    $prim_c = $chif[$unite_c]; // Correctly handle second digit after decimal



    $output = '';



    if (($cent[3] == 0) && ($dix[3] == 0) && ($unite[3] == 1))

        $output .= $trio[3] . '  ' . $secon[3] . ' ' . $prim[3] . ' million ';

    else if (($cent[3] != 0) || ($dix[3] != 0) || ($unite[3] != 0))

        $output .= $trio[3] . ' ' . $secon[3] . ' ' . $prim[3] . ' millions ';

    else

        $output .= $trio[3] . ' ' . $secon[3] . ' ' . $prim[3];



    if (($cent[2] == 0) && ($dix[2] == 0) && ($unite[2] == 1))

        $output .= ' mille ';

    else if (($cent[2] != 0) || ($dix[2] != 0) || ($unite[2] != 0))

        $output .= $trio[2] . ' ' . $secon[2] . ' ' . $prim[2] . ' milles ';

    else

        $output .= $trio[2] . ' ' . $secon[2] . ' ' . $prim[2];



    $output .= $trio[1] . ' ' . $secon[1] . ' ' . $prim[1];



    $output .= ' ' . $dev1 . ' et '; // Adding "et" between Dirhams (or Dinars) and Centimes



    // Correctly show decimals with both digits

    if ($valeur_decimal == 0)

        $output .= ' zéro ' . $dev2;

    else {

        if ($dix_c == 0) {

            // For values between .01 and .09, include "zéro"

            $output .= 'zéro ' . $prim_c . ' ' . $dev2;

        } else {

            $output .= $secon_c . ' ' . $prim_c . ' ' . $dev2;

        }

    }



    // Capitalize the first letter of each word

    echo ucwords($output);

}

?>