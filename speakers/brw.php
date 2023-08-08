<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

// $fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
// //Filtro de busqueda por titulo
// $where = '';
// if($fltdescri!=''){
// 	$where .= " AND AVITITULO CONTAINING '$fltdescri' ";
// }

$conn = sql_conectar(); //Apertura de Conexion'

	$fltnombre 		= (isset($_POST['fltnombre']))? trim($_POST['fltnombre']):'';
	$fltorden 		= (isset($_POST['fltorden']))? trim($_POST['fltorden']):'';
	$where = '';
	//Nombre
	if($fltnombre!=''){
		$where .= " AND SPKNOMBRE CONTAINING '$fltnombre' ";
	}

	$orden = ' ORDER BY SPKPOS ';
	switch($fltorden){

		case 0:
			/// Ubicacion
			$orden = ' ORDER BY SPKPOS ';
			break;
		break;
		case 1: //Nombre
			$orden = ' ORDER BY SPKNOMBRE ';
			break;

		case 2: //Nombre
			$orden = ' ORDER BY SPKNOMBRE DESC';
			break;
	}

	//TIpo de Orden: 2=Descendente / 1=DESCendente
	//if($fltordentipo==2){
	//	$orden .= ' DESC';
	//}

$query = "	SELECT SPKREG, SPKNOMBRE, SPKPOS, SPKDESCRI, ESTCODIGO
				FROM SPK_MAEST
				WHERE ESTCODIGO=1 $where
				$orden ";



//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	//$aviimagen  = trim($row['AVIIMAGEN']);

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setvariable('spkpos', $spkpos);

	//$tmpl->setvariable('aviimagen',$aviimagen);
	$tmpl->parse('browser');
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
