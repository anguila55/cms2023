<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');
DDIdioma($tmpl);


$conn = sql_conectar(); //Apertura de Conexion

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim($_POST['perusuacc']) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim($_POST['perpasacc']) : '';
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

$tmpl->setVariable('percodnotif', $percodigo	);

if ($peradmin!=1){
		header('Location: ../login');	
}

$conn = sql_conectar(); //Apertura de Conexion


$errmsg 	= '';

/////////////////////////////////// Tipo de Evento //////////////////////////////////////

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoNombre'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$textconfirmar = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('textconfirmar', $textconfirmar);
	
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoPassword'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$textliberar = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('textliberar', $textliberar);
	
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefch = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('evefch', $evefch);
	
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkPlayStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefchfin = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('evefchfin', $evefchfin);
	
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkExternalReuniones'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$nombreevento = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('nombreevento', $nombreevento);
	
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'MenuNoticias'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$mailcontacto = trim($row['ZDESCRI']);
	
		$tmpl->setVariable('mailcontacto', $mailcontacto);
	
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'OcRegistro'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$ocregistro = trim($row['ZDESCRI']);
	if($ocregistro=='si'){
		$tmpl->setVariable('si', 'selected');
	}else{
		$tmpl->setVariable('no', 'selected');
	}
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'ColorEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$colorevento = trim($row['ZDESCRI']);

	$tmpl->setVariable('colorevento', $colorevento);
	
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'IngresoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$ingresoevento = trim($row['ZDESCRI']);
	if($ingresoevento=='false'){
		$tmpl->setVariable('ingresoeventolibre', 'selected');
	}else{
		$tmpl->setVariable('ingresoeventosinliberar', 'selected');
	}
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'TipoRegistro'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tiporegistro = trim($row['ZDESCRI']);
	if($tiporegistro=='false'){
		$tmpl->setVariable('registromultiple', 'selected');
	}else{
		$tmpl->setVariable('registrounico', 'selected');
	}
}






//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
