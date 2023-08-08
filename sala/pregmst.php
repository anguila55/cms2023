<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('pregmst.html');
	$conn= sql_conectar();//Apertura de Conexion
	//Diccionario de idiomas
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
	
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
		
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	
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
	//--------------------------------------------------------------------------------------------------------------
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$tmpl->setVariable('agereg'	, $agereg	);
	
	
	//$query = "	SELECT A.AGETITULO
	//			FROM AGE_MAEST A
	//			WHERE A.AGEREG=$agereg ";
	//
	//$Table = sql_query($query,$conn);
	//$row = $Table->Rows[0];
	//$agetitulo 	= trim($row['AGETITULO']);
	//$tmpl->setVariable('agereg'		, $agereg );
	//$tmpl->setVariable('agetitulo'	, $agetitulo );
	
	//Cargo todas las encuestas
	//$query = "	SELECT E.ENCREG,E.ENCDESCRI
	//			FROM ENC_CABE E
	//			WHERE E.ESTCODIGO=1 
	//			ORDER BY 2 ";
	//
	//$Table = sql_query($query,$conn);
	//for($i=0; $i<$Table->Rows_Count; $i++){
	//	
	//	$row = $Table->Rows[$i];
	//	
	//	$reg 		= trim($row['ENCREG']);
	//	$encdescri  = trim($row['ENCDESCRI']);
	//	
	//	$tmpl->setCurrentBlock('encuestas');
	//	$tmpl->setVariable('encreg'		, $reg);
	//	$tmpl->setVariable('encdescri'	, $encdescri);
	//	
	//	if($encreg == $reg){
	//		$tmpl->setVariable('encselect'	, 'selected');
	//	}
	//	
	//	$tmpl->parse('encuestas');	
	//}

	
	//--------------------------------------------------------------------------------------------------------------
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
