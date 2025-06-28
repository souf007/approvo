<?php
session_start();
session_destroy();
if(isset($_COOKIE['id'])){
	setcookie('id', $_SESSION['easybm_id'], time() - 3600);
	setcookie('fullname', $_SESSION['easybm_fullname'], time() - 3600);
	setcookie('picture', $_SESSION['easybm_picture'], time() - 3600);
	setcookie('phone', $_SESSION['easybm_phone'], time() - 3600);
	setcookie('roles', $_SESSION['easybm_roles'], time() - 3600);
	setcookie('defaultstate', $_SESSION['easybm_defaultstate'], time() - 3600);
	setcookie('depots', $_SESSION['easybm_depots'], time() - 3600);
	setcookie('companies', $_SESSION['easybm_companies'], time() - 3600);
	setcookie('projects', $_SESSION['easybm_projects'], time() - 3600);
	setcookie('type', $_SESSION['easybm_type'], time() - 3600);	
	setcookie('superadmin', $_SESSION['easybm_superadmin'], time() - 3600);	
}
header('Location: login.php');
?>