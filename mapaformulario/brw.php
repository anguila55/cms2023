<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

$fltdescri = (isset($_POST['fltdescri'])) ? trim($_POST['fltdescri']) : '';

$where = '';
if ($fltdescri != '') {
	$where .= " AND NETWORK_TITULO CONTAINING '$fltdescri' ";
}

$conn = sql_conectar(); //Apertura de Conexion

//Seleccionamos los datos que se mostrarar en el brw

/* $query = "	SELECT *
	FROM MAP_TABLE
	WHERE ESTCODIGO=1 */

$query = " 	SELECT M.MAPREG, M.LINK, M.EXPREG,M.COORD,
E.EXPNOMBRE, E.EXPREG, E.ESTCODIGO
FROM MAP_TABLE M
LEFT OUTER JOIN EXP_MAEST E ON M.EXPREG=E.EXPREG
WHERE M.ESTCODIGO=1 $where"; 

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$mapreg 	= trim($row['MAPREG']);
	$link 	= trim($row['LINK']);
	$coord 	= trim($row['COORD']);
	$expreg 	= trim($row['EXPREG']);
	$expnombre 	= trim($row['EXPNOMBRE']);

    /* $separarcoordenadas = explode(",", $coord);
	$coordx = trim($separarcoordenadas[0]);
	$coordy = $separarcoordenadas[1]; */

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('mapreg', $mapreg);
	
	/* $tmpl->setVariable('coordx', $coordx);
	$tmpl->setVariable('coordy', $coordy); */
	$tmpl->setVariable('coord', $coord);

	$tmpl->setVariable('link', $link);
	$tmpl->setVariable('expnombre'	, $expnombre);
	$tmpl->setVariable('expreg', $expreg);

	$tmpl->parse('browser');
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
