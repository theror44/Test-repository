<?
include_once("../config.php");
include_once($config["util"]."FileUtil.php");

$aFiles = $_FILES;

foreach ($aFiles as $strInput => $aFileData) {

	$strTmpName = $config["temp"].md5_file($aFileData["tmp_name"]).".".CFileUtil::GetExtension($aFileData["name"]);
	move_uploaded_file($aFileData["tmp_name"], $strTmpName);
	$aFiles[$strInput]["tmp_name"] = $strTmpName;
}

session_start();
$_SESSION["FILES"] = $aFiles;
session_write_close();

/*	Copie des fichiers upload�s vers un r�pertoire temporaire afin de la rendre accessible � la session
 d'une autre page appel�e parall�lement. Cette page est appel� parall�lement � un appel AJAX afin de rendre
 accessible les fichiers upload�s � la requ�te AJAX.
 Les fichiers temporaires PHP �tant supprim�s � la fin du traitement de cette page, on effectue une simulation
 du POST en recr�ant le fichier temporaire manuellement. La session �tant ensuite partag�e on �crit les infos dans
 la session courante et on peut ainsi r�cup�rer les informations dans la session de la page AJAX.
 (copie du fichier physique temporaire + transfert des infos fichiers par session)
 */
$strController = $_POST["strController"];
$strAction = $_POST["strAction"];
$idElement = $_POST["idElement"];
?>
<script type="text/javascript">
	window.parent.oAppManager.executeActionByElement('<?= $strController ?>', '<?= $strAction ?>', '<?= $idElement ?>');
</script>
<?
exit();
?>