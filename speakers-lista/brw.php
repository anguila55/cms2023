<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

 $buscar = (isset($_POST['buscar']))? trim($_POST['buscar']):'';
// //Filtro de busqueda por titulo
$where = '';
 if($buscar!=''){
 	$where .= " AND SPKNOMBRE CONTAINING '$buscar' ";
 }

$conn = sql_conectar(); //Apertura de Conexion

$query = "	SELECT *
				FROM SPK_MAEST
				WHERE ESTCODIGO<>3 $where
				ORDER BY SPKPOS,SPKNOMBRE";



//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	$spkempres     = trim($row['SPKEMPRES']);
	$spkimg     = trim($row['SPKIMG']);
	$spkcargo     = trim($row['SPKCARGO']);
	$spklinked     = trim($row['SPKLINKED']);
	$spkins     = trim($row['SPKINS']);
	$spkfac     = trim($row['SPKFAC']);
	$spktwi     = trim($row['SPKTWI']);

	//$aviimagen  = trim($row['AVIIMAGEN']);
	
	if($spklinked ==""){
		$spklinked = "";
	}else{
		$spklinked = "href='". $spklinked."'";
	}
	if($spkfac ==""){
		$spkfac = "";
	}else{
		$spkfac = "href='". $spkfac."'";
	}
	if($spkins ==""){
		$spkins = "";
	}else{
		$spkins = "href='". $spkins."'";
	}
	if($spktwi ==""){
		$spktwi = "";
	}else{
		$spktwi = "href='". $spktwi."'";
	}

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setVariable('spkempres', $spkempres);
	$tmpl->setVariable('spkcargo', $spkcargo);
	$tmpl->setVariable('spkimg', '../spkimg/' . $spkreg . '/' . $spkimg);
	$tmpl->setvariable('spkpos', $spkpos);
	$tmpl->setvariable('spklinked', $spklinked);
	$tmpl->setvariable('spkins', $spkins);
	$tmpl->setvariable('spkfac', $spkfac);
	$tmpl->setvariable('spktwi', $spktwi);

	//$tmpl->setvariable('aviimagen',$aviimagen);
	$tmpl->parse('browser');
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
