<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC . '/constants.php';
			
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

	if ($peradmin!=1){
		header('Location: ../login');	
	}
	
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
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
	
		$fltbuscar 	= (isset($_GET['T']))? $_GET['T']:1;
		$tmpl->setVariable('fltbuscar'	, $fltbuscar);
		$tmpl->setVariable('btnselect1', 'bg-main-event');
		$tmpl->setVariable('btnselect2', 'bg-main-event');
		$tmpl->setVariable('btnselect3', 'bg-main-event');
		$tmpl->setVariable('btnselect4', 'bg-main-event');
		$tmpl->setVariable('btnselect'.$fltbuscar, 'bg-secondary-event');
	$conn= sql_conectar();//Apertura de Conexion
	

	//--------------------------------------------------------------------------------------------------------------
	
	
	$query = "	SELECT * FROM MSG_CABE";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$msgreg 	= trim($row['MSGREG']);
		$msgtitulo 	= trim($row['MSGTITULO']);
		
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('msgreg'	, $msgreg);
		$tmpl->setVariable('msgtitulo'	, $msgtitulo);
		
		$tmpl->parse('browser');
		
	}

	sql_close($conn);	
	$tmpl->show();
	
?>	
