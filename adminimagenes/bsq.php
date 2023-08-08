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


$pathimagenes = '../admimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);


$query = "	SELECT *
			FROM ADM_IMG";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];

	$bannerhomeimg 	= trim($row['BANNERS']);
	$urlbanner 	= trim($row['URLBAN']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$bannerid 	= trim($row['BANID']);
	
	if($bannerhomeimg==''){ 
		$bannerhomeimg = $imgBannerHomeNull;
	}else{
		$bannerhomeimg = $pathimagenes.$bannerhomeimg;
	}
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('bannerhomeimg'	, $bannerhomeimg	);
	$tmpl->setVariable('urlbanner'	, $urlbanner	);
	$tmpl->setVariable('bannerid'	, $bannerid	);
	$tmpl->setVariable('displaytipo'	, "d-none" 	);
	$tmpl->setVariable('displayvisible'	, "d-flex" 	);
	$tmpl->setVariable('displayurl'	, "d-flex" 	);

	if ($bannerid==0){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displayvisible'	, "d-none" 	);
		$tmpl->setVariable('displayurl'	, "d-none" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Fondo Login" 	);
	}
	if ($bannerid==1){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displayvisible'	, "d-none" 	);
		$tmpl->setVariable('displayurl'	, "d-none" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Fondo Login Responsive" 	);
	}
	if ($bannerid==2){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Banners Inicio" 	);
	}
	if ($bannerid==6){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Banners Auditorios" 	);
	}
	if ($bannerid==8){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Banners Modal Chat" 	);
	}

	if ($bannerid==9){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displayvisible'	, "d-none" 	);
		$tmpl->setVariable('displayurl'	, "d-none" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Favicon" 	);
		$tmpl->setVariable('bannerhomeimg'	, "../assets-nuevodisenio/img/favicon.jpg" 	);


	}

	if ($bannerid==10){
		$tmpl->setVariable('displaytipo'	, "" 	);
		$tmpl->setVariable('displayvisible'	, "d-none" 	);
		$tmpl->setVariable('displayurl'	, "d-none" 	);
		$tmpl->setVariable('displaytitulotipo'	, "Navbar" 	);
	}

	if ($bannerid<6 && $bannerid!=0 && $bannerid!=1){
		$orden=$bannerid - 1;
		$tmpl->setVariable('titulobanner'	, 'Banner Home. Orden: '.$orden.' (cliquea sobre la imagen para editar). Tamaño recomendado (1920x480px):'	);


	}else if ($bannerid==6){
		$tmpl->setVariable('titulobanner'	, 'Banner Superior Streaming 1920x200px (cliquea sobre la imagen para editar):'	);

	}else if ($bannerid==7){

		$tmpl->setVariable('titulobanner'	, 'Banner Inferior Streaming 460x135px (cliquea sobre la imagen para editar):'	);

	}else if ($bannerid==1){
		$tmpl->setVariable('titulobanner'	, 'Fondo Login Responsive 800x1500px (cliquea sobre la imagen para editar):'	);

	}else if ($bannerid==8){
		$tmpl->setVariable('titulobanner'	, 'Banner Inferior Modal Chat 1920x200px idealmente png fondo transparente(cliquea sobre la imagen para editar):'	);

	}else if($bannerid==9){
		$tmpl->setVariable('titulobanner'	, 'Icono de la pestaña del navegador en jpg 1080x1080px (cliquea sobre la imagen para editar):'	);

	}else if($bannerid==10){
		$tmpl->setVariable('titulobanner'	, 'Icono de Navbar 180x60px se recomienda .svg (cliquea sobre la imagen para editar):'	);
	}else{
		$tmpl->setVariable('titulobanner'	, 'Fondo Login 1920x1080px (cliquea sobre la imagen para editar):'	);
	}
	if ($estcodigo==1){

		$tmpl->setVariable('activo'	, 'selected'	);

	}else{

		$tmpl->setVariable('inactivo'	, 'selected'	);
	}
	

	$tmpl->parse('browser');
}


$tmpl->show();

?>