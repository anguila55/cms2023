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
	$percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';

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
	$tmpl->setVariable('percompan'	, $percompan	);
	
	$conn= sql_conectar();//Apertura de Conexion


	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'fix') {

		$tmpl->setVariable('mostrarchequeo', '');
	} else {
		$tmpl->setVariable('mostrarchequeo', 'd-none');
	}
	}

	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}

	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 
	
	// $diaInicial =  substr($diasini, 0, 2) + 1 - 1;
	$diaInicial = substr($diasini, 0, 2).'-'.substr($diasini,3,2).'-'.substr($diasini,6,4);
	$finalEvento =  date("d/m/Y", strtotime($diaInicial.' + '.$diasdur.' days'));
	$tmpl->setVariable('diainicial', $diasini	);
	$tmpl->setVariable('diafinal', $finalEvento	);

	//Nombre del Evento
	// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:none;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:none;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
	
	$fltbuscar 	= (isset($_GET['T']))? $_GET['T']:1;
	$tmpl->setVariable('fltbuscar'	, $fltbuscar);
	$tmpl->setVariable('btnselect1', 'btnselect');
	$tmpl->setVariable('btnselect2', 'btnselect');
	$tmpl->setVariable('btnselect3', 'btnselect');
	$tmpl->setVariable('btnselect4', 'btnselect');

	$tmpl->setVariable('btnselect'.$fltbuscar, 'shadow-sm border border-dark');

	//$tmpl->setVariable('fltbuscaractive_'.$fltbuscar, 'class="active"');
	
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
		$tmpl->setVariable('ParamMenuNoticias'	, 'display:none;'	);
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PERCODIGO,PERNOMBRE
				FROM PER_MAEST 
				WHERE PERCODIGO=$percodigo
				ORDER BY PERNOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('pernombre'	, $pernombre);		
		$tmpl->parse('browser');
	}


	$queryreuniones = "	SELECT 	R.REUREG
				FROM REU_CABE R
				WHERE R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo";

	$Tablereuniones = sql_query($queryreuniones,$conn);

	if ($Tablereuniones->Rows_Count > 0){

		$tmpl->setVariable('noreunionesdisplay'	, 'd-none');
	}else{
		$tmpl->setVariable('noreunionesdisplay'	, '');
	}

	
	
	//--------------------------------------------------------------------------------------------------------------
	//Reuniones solicitadas y pendientes
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantEnviados= trim($row['CANTIDAD']);
	if($cantEnviados==0)	$cantEnviados='';
	
	//Reuniones recibidas y pendientes
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantRecibidos= trim($row['CANTIDAD']);
	if($cantRecibidos==0)	$cantRecibidos='';
	
	$tmpl->setVariable('cantEnviados'	, $cantEnviados);
	$tmpl->setVariable('cantRecibidos'	, $cantRecibidos);


	/////////////////NOMBRE BANNERS/////////////////////
$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
FROM PAR_MAEST 
WHERE PARCODIGO='reuniones'";
$Tableparam = sql_query($queryparam, $conn);
$rowparam = $Tableparam->Rows[0];
$parnombre = trim($rowparam['PARNOMBRE']);
$paneladmin = trim($rowparam['PARCODIGO']);
$tmpl->setVariable('nombre'.$paneladmin, $parnombre);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
