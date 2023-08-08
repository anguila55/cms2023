<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------

$varreg = (isset($_POST['varreg']))? trim($_POST['varreg']) : 0;

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

$tmpl->setVariable('visibleopciones', 'd-none');
if($varreg!=0){
	
	$query = "	SELECT *
	FROM VAR_MAEST
	WHERE VARREG=$varreg";
$Table = sql_query($query, $conn);
if ($Table->Rows_Count>0) {
$row = $Table->Rows[0];
$varreg 	= trim($row['VARREG']);
$vardescri 	= trim($row['VARDESCRI']);
$vardescriing 	= trim($row['VARDESCRIING']);
$vardescripor 	= trim($row['VARDESCRIPOR']);
$varmost	= trim($row['VARMOST']);
$varreq	= trim($row['VARREQ']);
$vartipo	= trim($row['VARTIPO']);
$varopc	= trim($row['VAROPC']);


$tmpl->setVariable('varreg', $varreg);
$tmpl->setVariable('vardescri', $vardescri);
$tmpl->setVariable('vardescriing', $vardescriing);
$tmpl->setVariable('vardescripor', $vardescripor);
$tmpl->setVariable('varopc', $varopc);

if($varmost == 'true'){
	$tmpl->setVariable('selected1', 'selected');
}else{
	$tmpl->setVariable('selected2', 'selected');
}

if($varreq == 'true'){
	$tmpl->setVariable('selected3', 'selected');
}else{
	$tmpl->setVariable('selected4', 'selected');
}

if($vartipo == 0){
	$tmpl->setVariable('selected5', 'selected');
	$tmpl->setVariable('visibleopciones', 'd-none');
}else{
	$tmpl->setVariable('selected6', 'selected');
	$tmpl->setVariable('visibleopciones', '');

}


}


}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
