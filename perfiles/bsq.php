<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
require_once GLBRutaFUNC.'/constants.php';


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

$tmpl->setVariable('percodnotif', $percodigo	);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('perusuacc', $perusuacc);
$tmpl->setVariable('perpasacc', $perpasacc);
$tmpl->setVariable('peravatar', $peravatar);

//Nombre del Evento
// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
$tmpl->setVariable('displaycrear', '');
$tmpl->setVariable('displaydescargar', 'd-none');
$descargaragendas = (isset($_GET['Descargar']))? trim($_GET['Descargar']):''; 
if ($descargaragendas=='Agendas'){
	$tmpl->setVariable('displaycrear', 'd-none');
	$tmpl->setVariable('displaydescargar', '');
}

if ($peradmin != 1) {
	$tmpl->setVariable('viewadmin', 'none');
	header('Location: ../index');
}
if ($btnsectores != 1) $tmpl->setVariable('btnsectores', 'display:;');
if ($btnsubsectores != 1) $tmpl->setVariable('btnsubsectores', 'display:;');
if ($btncategorias != 1) $tmpl->setVariable('btncategorias', 'display:none;');
if ($btnsubcategorias != 1) $tmpl->setVariable('btnsubcategorias', 'display:none;');

//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false) {
	$tmpl->setVariable('ParamMenuExportar', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false) {
	$tmpl->setVariable('ParamMenuEncuesta', 'display:none;');
}


$conn = sql_conectar(); //Apertura de Conexion


$query = "	SELECT PERCODIGO,PERNOMBRE
				FROM PER_MAEST
				WHERE PERCODIGO=$percodigo				
				ORDER BY PERNOMBRE ";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percodigo 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);

	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('percodigo', $percodigo);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->parse('browser');
}
//--------------------------------------------------------------------------------------------------------------
$pertipcod='';
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO
				WHERE ESTCODIGO=1			
				ORDER BY PERTIPO";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipcod 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		//SI el usuario no es admin, cargo solo los registros asignados
	
		$tmpl->setCurrentBlock('pertipos');
		$tmpl->setVariable('pertipcod'	, $pertipcod 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);
		$tmpl->parse('pertipos');
			
		
	}
	//--------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------
//Reuniones solicitadas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantEnviados = trim($row['CANTIDAD']);
if ($cantEnviados == 0)	$cantEnviados = '';

//Reuniones recibidas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantRecibidos = trim($row['CANTIDAD']);
if ($cantRecibidos == 0)	$cantRecibidos = '';

$tmpl->setVariable('cantEnviados', $cantEnviados);
$tmpl->setVariable('cantRecibidos', $cantRecibidos);

$arrayperfileslista=null;
//Seleccionamos los perfiles
$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1
				ORDER BY PERCOMPAN ASC,UPPER(PERNOMBRE) ";
$TablePerfiles = sql_query($query, $conn);
for ($k = 0; $k < $TablePerfiles->Rows_Count; $k++) {
	$rowPerfiles = $TablePerfiles->Rows[$k];
	$percod 	= trim($rowPerfiles['PERCODIGO']);
	$arrayperfileslista[]=['percodigo'=>$percod];

	
}
$myJSON = json_encode($arrayperfileslista);
$tmpl->setvariable('arrayperfileslista'		, $myJSON);
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
