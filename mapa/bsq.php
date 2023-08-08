<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('bsq.html');
DDIdioma($tmpl);

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli = (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc = (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$perpasacc = (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$peradmin = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$peravatar = (isset($_SESSION[GLBAPPPORT . 'PERAVATAR'])) ? trim($_SESSION[GLBAPPPORT . 'PERAVATAR']) : '';
$btnsectores 		= (isset($_SESSION[GLBAPPPORT . 'SECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SECTORES']) : '';
$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT . 'SUBSECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SUBSECTORES']) : '';
$btncategorias 		= (isset($_SESSION[GLBAPPPORT . 'CATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'CATEGORIAS']) : '';
$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS']) : '';

$tmpl->setVariable('percodnotif', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('perusuacc', $perusuacc);
$tmpl->setVariable('perpasacc', $perpasacc);
$tmpl->setVariable('peravatar', $peravatar);

//Nombre del Evento
$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);

if ($peradmin != 1) $tmpl->setVariable('viewadmin', 'none');
if ($btnsectores != 1) $tmpl->setVariable('btnsectores', 'display:;');
if ($btnsubsectores != 1) $tmpl->setVariable('btnsubsectores', 'display:;');
if ($btncategorias != 1) $tmpl->setVariable('btncategorias', 'display:none;');
if ($btnsubcategorias != 1) $tmpl->setVariable('btnsubcategorias', 'display:none;');






$conn = sql_conectar(); //Apertura de Conexion

  $query = "	SELECT *
				FROM MAP_TABLE
				";   

//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$coord	= trim($row['COORD']);
	$expreg	= trim($row['EXPREG']);
	$link	= trim($row['LINK']);

	
	if ($link == "") {
		$link = "../sponsor/bsq?id=" . $expreg;
	}
	
	$tmpl->setCurrentBlock('map');
	$tmpl->setVariable('coord', $coord);
	$tmpl->setVariable('link', $link);
	$tmpl->parse('map');
	
}

$query = "SELECT M.EXPREG,M.LINK,
E.EXPNOMBRE, M.COORD
FROM EXP_MAEST E
LEFT OUTER JOIN MAP_TABLE M ON E.EXPREG=M.EXPREG where M.ESTCODIGO = 1"; 

//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	
	$expnombre	= trim($row['EXPNOMBRE']);
	$coord	= trim($row['COORD']);
	$expreg	= trim($row['EXPREG']);
	$link	= trim($row['LINK']);


	if ($link == "") {
		$link = "../sponsor/bsq?id=" . $expreg;
	}
	
	$tmpl->setCurrentBlock('nombreexp');
	$tmpl->setVariable('expnombre', $expnombre);
	$tmpl->setVariable('coord', $coord);
	$tmpl->setVariable('expreg', $expreg);
	$tmpl->setVariable('link', $link);
	$tmpl->parse('nombreexp'); 

}




$query = "	SELECT IMAGEN FROM MAP_TABLE WHERE ESTCODIGO=4 $where";

//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];

	$mapimg= trim($row['IMAGEN']);

	
	$tmpl->setVariable('mapimg', $mapimg);

}

/////////////////NOMBRE BANNERS/////////////////////
$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
FROM PAR_MAEST 
WHERE PARCODIGO='mapa'";
$Tableparam = sql_query($queryparam, $conn);
$rowparam = $Tableparam->Rows[0];
$parnombre = trim($rowparam['PARNOMBRE']);
$paneladmin = trim($rowparam['PARCODIGO']);
$tmpl->setVariable('nombre'.$paneladmin, $parnombre);

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
