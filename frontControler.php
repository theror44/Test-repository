<?php
require("../config.php");
require("../constNew.php");
require("../const.php");

require_once($config["form"]."CForm.php");
require_once($config["generalFunctions"]."CXmlTraduction.php");

$strCtrlName = ucfirst(CForm::RetrieveAnyEntry("controlerName", "default"));
$strCtrlClassName = "CCtrl".$strCtrlName;
$bHTMLResponse = CForm::RetrieveAnyEntry("HTMLResponse", false);
$bDecodeParam = CForm::RetrieveAnyEntry("DecodeParam", 1);
$bDecodeParam = !empty($bDecodeParam);

//Si le controleur existe
$bShowMessage = true;
if(file_exists($config["controler"]."$strCtrlClassName.php")) {
	require_once($config["controler"]."$strCtrlClassName.php");
} else {
	require_once($config["controler"]."CCtrlDefault.php");
	$strCtrlClassName = "CCtrlDefault";
}

require_once("sessionajax.php");

$oCtrl = new $strCtrlClassName($bDecodeParam);
$oCtrl->executeAction();

if($bHTMLResponse) {
	echo $oCtrl->writeHTMLResponse();
} else {
	$oCtrl->writeXMLResponse();
}
?>