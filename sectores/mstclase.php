<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mstclase.html');

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$subsect = (isset($_POST['subsect'])) ? trim($_POST['subsect']) : 0;
$sector = (isset($_POST['sector'])) ? trim($_POST['sector']) : 0;
$tmpl->setVariable('sector', $sector);



//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

if ($subsect != 0) {
	$query = "	SELECT SECSUBCOD, SECSUBDES,SECSUBDESING
					FROM SEC_SUB
					WHERE SECCODIGO=$sector AND SECSUBCOD=$subsect AND ESTCODIGO=1";




	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$subsectdes = trim($row['SECSUBDES']);
		$subsectdesing = trim($row['SECSUBDESING']);
		$subsect = trim($row['SECSUBCOD']);

		$tmpl->setVariable('subsectdesing', $subsectdesing);
		$tmpl->setVariable('subsectdes', $subsectdes);
		$tmpl->setVariable('subsect', $subsect);
	}
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
