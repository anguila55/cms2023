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


$pathimagenes = '../popimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);

$query = "	SELECT *
			FROM ADM_POP WHERE POP_REG<>0";

$Table = sql_query($query,$conn);


for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];

	$popupimg 	= trim($row['POP_IMG']);
	$popupurl 	= trim($row['POP_URL']);
	$estcodigo 	= trim($row['ESTCODIGO']);
	$popupreg 	= trim($row['POP_REG']);
	$popupdescri 	= trim($row['POP_DESCRI']);
	$popuptipo 	= trim($row['POP_TIPO']);
	
	if($popupimg==''){ 
		$popupimg = $imgBannerHomeNull;
	}else{
		$popupimg = $pathimagenes.$popupimg;
	}
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('popupimg'	, $popupimg	);
	$tmpl->setVariable('popupurl'	, $popupurl	);
	$tmpl->setVariable('popupdescri'	, $popupdescri	);
	$tmpl->setVariable('popupreg'	, $popupreg	);
	$tmpl->setVariable('popuptipo'	, $popuptipo	);
	if ($estcodigo==1){

		$tmpl->setVariable('activo'	, 'selected'	);

	}else{

		$tmpl->setVariable('inactivo'	, 'selected'	);
	}
	if ($popuptipo==2){

		$tmpl->setVariable('imgsolo'	, 'selected'	);

	}else if ($popuptipo==3){

		$tmpl->setVariable('textosolo'	, 'selected'	);
	}else{

		$tmpl->setVariable('imgtexto'	, 'selected'	);
	}
	

	$tmpl->parse('browser');
}


$queryinfo = "	SELECT POP_URL
			FROM ADM_POP WHERE POP_REG=0";

$Tableinfo = sql_query($queryinfo,$conn);

if($Tableinfo->Rows_Count>0){
	$rowinfo = $Tableinfo->Rows[0];

	$popupinfo 	= trim($rowinfo['POP_URL']);

	$arrayinfo = explode(',',$popupinfo);

	if ($arrayinfo[0] == 0){
		$tmpl->setVariable('novisible'	, 'selected'	);
	}else{
		$tmpl->setVariable('visible'	, 'selected'	);
	}

	if ($arrayinfo[1] == 0){
		$tmpl->setVariable('novisible1'	, 'selected'	);
	}else{
		$tmpl->setVariable('visible1'	, 'selected'	);
	}

	if ($arrayinfo[2] == 0){
		$tmpl->setVariable('novisible2'	, 'selected'	);
	}else{
		$tmpl->setVariable('visible2'	, 'selected'	);
	}

	if ($arrayinfo[3] == 0){
		$tmpl->setVariable('novisible3'	, 'selected'	);
	}else{
		$tmpl->setVariable('visible3'	, 'selected'	);
	}



}

sql_close($conn);
$tmpl->show();

?>