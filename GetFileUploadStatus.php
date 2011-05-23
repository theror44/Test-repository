<?php
require_once("../config.php");
require_once($config["util"]."CFileUtil.php");
require_once($config["util"]."CStringUtil.php");
require_once($config["form"]."CForm.php");

//Mise en session et en répertoire temporaire des fichiers uploadés, cela permet de pouvoir
//lancer des upload en AJAX puis de les suivre
$aFiles = $_FILES;
$strFileId = "";
foreach ($aFiles as $strInput => $aFileData) {
	$strFileExtension = CFileUtil::GetExtension($aFileData["name"]);
	$strTmpName = $config["temp"].md5_file($aFileData["tmp_name"]).CStringUtil::randString().".".$strFileExtension;
	move_uploaded_file($aFileData["tmp_name"], $strTmpName);
	$aFiles[$strInput]["tmp_name"] = $strTmpName;
	$aFiles[$strInput]["extension"] = $strFileExtension;
	$strFileId = $strInput;
	break;
}

$strDownloadArea = CForm::RetrievePostEntry("idFileContainer");

session_start();
if(!isset($_SESSION["FILES"])) {
	$_SESSION["FILES"] = array();
}
foreach ($aFiles as $idFile => $aFileData) {
	$_SESSION["FILES"][$idFile] = $aFileData;
	break;
}
session_write_close();

$aFileData = $aFiles[$strFileId];
$bIsImage = CFileUtil::isImage($aFileData["tmp_name"]);
if($bIsImage) {
	$aFileData["name"] = CFileUtil::getFileName($aFileData["tmp_name"]);
	CFileUtil::copy($aFileData["tmp_name"], $config["TempImage"].$aFileData["name"]);
}
?>
<script
	type="text/javascript" src="<?= $config["commons"] ?>js/prototype.js"></script>
<script
	type="text/javascript"
	src="<?= $config["commons"] ?>js/CAjaxFileManager.js"></script>
<script type="text/javascript">
	oFileDescription = {
			name: "<?= addslashes($aFileData["name"]) ?>", 
			size: "<?= CFileUtil::getFileSizeLabel($aFileData["tmp_name"]); ?>",
			strType: "<?= $strType ?>",
			inputName: "<?= $strFileId ?>",
			idFileContainer: "<?= $strDownloadArea ?>"
	};
	new CAjaxFileRenderer(oFileDescription, true, <?= $bIsImage ? "true" : "false" ?>);
</script>
