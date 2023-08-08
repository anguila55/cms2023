<?
if (!isset($_SESSION))  session_start();
// include($_SERVER["DOCUMENT_ROOT"] . '/cms/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('bsq.html');
DDIdioma($tmpl);

//Diccionario de idiomas
//var_dump(DDIdioma($tmpl));

// Agregarmos una variable CaptCha

// Fin Agregarmos una variable CaptCha




$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim($_POST['perusuacc']) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim($_POST['perpasacc']) : '';
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

$tmpl->setVariable('percodnotif', $percodigo	);
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
if ($peradmin!=1){
		header('Location: ../login');	
}

$conn = sql_conectar(); //Apertura de Conexion


$errmsg 	= '';


$pathimagenes = '../mapimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);

$query = "	SELECT *
			FROM MAP_TABLE WHERE ESTCODIGO=4 $where";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];

	$mapimg 	= trim($row['IMAGEN']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$visibilidad 	= trim($row['VISIBILIDAD']);
	$mapreg 	= trim($row['MAPREG']);
	
	if($mapimg==''){ 
		$mapimg = $imgBannerHomeNull;
	}else{
		$mapimg = $pathimagenes.$mapimg;
	}
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('mapimg'	, $mapimg	);	
	$tmpl->setVariable('mapreg'	, $mapreg	);
	if ($visibilidad==1){

		$tmpl->setVariable('activo'	, 'selected'	);

	}else{

		$tmpl->setVariable('inactivo'	, 'selected'	);
	}
	

	$tmpl->parse('browser');
}


$tmpl->show();

?>