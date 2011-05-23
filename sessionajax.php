<?php
# récupération de la session
session_start();

if((!isset($_SESSION["idUser"])) || (!isset($_SESSION["login"]))) {
	//session_destroy();
	require_once($config["controler"]."CCtrlDefault.php");
	$oCtrl = new CCtrl();
	$oCtrl->_oActionRedirect = new CRedirectAction(_HTML_REDIRECTION, "login.php");
	$oCtrl->writeXMLResponse();
	exit();
} else {
	$_SESSION["MVCVersion"] = 2;
}
?>