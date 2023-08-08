<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	$tmpl->setVariable('percodnotif', $percodigo	);
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
	
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );	
	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
	//--------------------------------------------------------------------------------------------------------------
	$fltrecomendado = (isset($_GET['R']))? 1 : 0; //Filtro de Recomendados
	$tmpl->setVariable('fltrecomendado'	, $fltrecomendado	);
	if($fltrecomendado==1){
		$tmpl->setVariable('activerecomendados'	, 'class="active"'	);
	}else{
		$tmpl->setVariable('activedirectorio'	, 'class="active"'	);
	}
	$fltfavoritos = (isset($_GET['F']))? 1 : 0; //Filtro de Favoritos
	$tmpl->setVariable('fltfavoritos'	, $fltfavoritos	);
	//--------------------------------------------------------------------------------------------------------------
	
//Habilito las opciones del Menu
if(json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false){
	$tmpl->setVariable('ParamMenuActividades'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false){
	$tmpl->setVariable('ParamMenuAgenda'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false){
	$tmpl->setVariable('ParamMenuMensajes'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false){
	$tmpl->setVariable('ParamMenuNoticias'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false){
	$tmpl->setVariable('ParamMenuExportar'	, 'display:;'	);
}
if(json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false){
	$tmpl->setVariable('ParamMenuEncuesta'	, 'display:none;'	);
}


$conn = sql_conectar(); //Apertura de Conexion
/////////////////NOMBRE BANNERS/////////////////////
$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
FROM PAR_MAEST 
WHERE PARCODIGO='ofertas'";
$Tableparam = sql_query($queryparam, $conn);
$rowparam = $Tableparam->Rows[0];
$parnombre = trim($rowparam['PARNOMBRE']);
$paneladmin = trim($rowparam['PARCODIGO']);
$tmpl->setVariable('nombre'.$paneladmin, $parnombre);
	

sql_close($conn);
	$tmpl->show();
	
?>	
