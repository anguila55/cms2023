<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
	require_once GLBRutaFUNC.'/constants.php';
	require_once GLBRutaAPI  . '/timezone_bot.php';

			
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
	$tmpl->setVariable('percodigousuario', $percodigo	);
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
	
	//Nombre del Evento
	// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
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
		$tmpl->setVariable('display_recomendados'	, 'd-none'	);
		$tmpl->setVariable('display_todos2'	, ''	);
		$tmpl->setVariable('filtrocartera'	, 'd-none'	);
	}else{
		$tmpl->setVariable('activedirectorio'	, 'class="active"'	);
		$tmpl->setVariable('display_recomendados'	, ''	);
		$tmpl->setVariable('display_todos2'	, 'd-none'	);
		$tmpl->setVariable('filtrocartera'	, ''	);
	}
	//// Traigo los perfiles relacionados desde sponsor
	$expreg	= (isset($_GET['E']))? $_GET['E']:0;
	$tmpl->setVariable('expreg'	, $expreg	);
	
	$fltfavoritos = (isset($_GET['F']))? 1 : 0; //Filtro de Favoritos
	$tmpl->setVariable('fltfavoritos'	, $fltfavoritos	);

	$fltcompartidos = (isset($_GET['COMP']))? 1 : 0; //Filtro de Favoritos
	if($fltcompartidos==1){
		$tmpl->setVariable('mostrarcompartidos'	, 'showCompQR();'	);

	}else{
		$tmpl->setVariable('mostrarcompartidos'	, ''	);
	}
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
	
	$conn= sql_conectar();//Apertura de Conexion


	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'CompartirDatos'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$compartirdatos = trim($row['ZVALUE']);
	if ($compartirdatos == 'true') {
		$tmpl->setVariable('mostrarcompartir', '');
	}else if ($compartirdatos == 'false'){
		$tmpl->setVariable('mostrarcompartir', 'd-none');
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

	//// CLASIFICAR EN DIRECTORIO
	
	$fltviewproductos = (isset($_GET['P']))? trim($_GET['P']) : ''; //Acceso directo a Productos
	$tmpl->setVariable('fltviewproductos'	, $fltviewproductos	);

	$dataSectoresVen		= '';
	$dataSubsectoresVen		= '';
	$dataCategoriasVen		= '';
	$dataSubcategoriasVen	= '';
	$dataSectoresCom		= '';
	$dataSubsectoresCom 	= '';
	$dataCategoriasCom		= '';
	$dataSubcategoriasCom	= '';
	//Cargo los Sectores - Ventas
	$queryClas = "	SELECT S.SECCODIGO
	FROM PER_SECT C
	LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
	WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";

$TableClas = sql_query($queryClas,$conn);
for($j=0; $j<$TableClas->Rows_Count; $j++){
$rowClas= $TableClas->Rows[$j];
$seccodigo 	= trim($rowClas['SECCODIGO']);
$dataSectoresVen .= '{"seccodigo":"'.$seccodigo.'"},';
}
$dataSectoresVen = substr($dataSectoresVen,0,strlen($dataSectoresVen)-1);

//Cargo los SubSectores - Ventas
$queryClas = "	SELECT S.SECSUBCOD
	FROM PER_SSEC C
	LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD=C.SECSUBCOD
	WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
$TableClas = sql_query($queryClas,$conn);
for($j=0; $j<$TableClas->Rows_Count; $j++){
$rowClas= $TableClas->Rows[$j];
$secsubcod 	= trim($rowClas['SECSUBCOD']);				
$dataSubsectoresVen .= '{"secsubcod":"'.$secsubcod.'"},';
}
$dataSubsectoresVen = substr($dataSubsectoresVen,0,strlen($dataSubsectoresVen)-1);

//Cargo las Categorias - Ventas
$queryClas = "	SELECT S.CATCODIGO
	FROM PER_CATE C
	LEFT OUTER JOIN CAT_MAEST S ON S.CATCODIGO=C.CATCODIGO
	WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";

$TableClas = sql_query($queryClas,$conn);
for($j=0; $j<$TableClas->Rows_Count; $j++){
$rowClas= $TableClas->Rows[$j];
$catcodigo 	= trim($rowClas['CATCODIGO']);
$dataCategoriasVen .= '{"catcodigo":"'.$catcodigo.'"},';
}
$dataCategoriasVen = substr($dataCategoriasVen,0,strlen($dataCategoriasVen)-1);

//Cargo las SubCategorias - Ventas
$queryClas = "	SELECT S.CATSUBCOD
	FROM PER_SCAT C
	LEFT OUTER JOIN CAT_SUB S ON S.CATSUBCOD=C.CATSUBCOD
	WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";

$TableClas = sql_query($queryClas,$conn);
for($j=0; $j<$TableClas->Rows_Count; $j++){
$rowClas= $TableClas->Rows[$j];
$catsubcod 	= trim($rowClas['CATSUBCOD']);
$dataSubcategoriasVen .= '{"catsubcod":"'.$catsubcod.'"},';
}
$dataSubcategoriasVen = substr($dataSubcategoriasVen,0,strlen($dataSubcategoriasVen)-1);
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//Cargo los Sectores - Compras
	$queryClas = "	SELECT S.SECCODIGO
		FROM PER_SECT C
		LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
		WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";

	$TableClas = sql_query($queryClas,$conn);
	for($j=0; $j<$TableClas->Rows_Count; $j++){
	$rowClas= $TableClas->Rows[$j];
	$seccodigo 	= trim($rowClas['SECCODIGO']);
	$dataSectoresCom .= '{"seccodigo":"'.$seccodigo.'"},';
	}
	$dataSectoresCom = substr($dataSectoresCom,0,strlen($dataSectoresCom)-1);

	//Cargo los SubSectores - Compras
	$queryClas = "	SELECT S.SECSUBCOD
		FROM PER_SSEC C
		LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD=C.SECSUBCOD
		WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
	$TableClas = sql_query($queryClas,$conn);
	for($j=0; $j<$TableClas->Rows_Count; $j++){
	$rowClas= $TableClas->Rows[$j];
	$secsubcod 	= trim($rowClas['SECSUBCOD']);				
	$dataSubsectoresCom .= '{"secsubcod":"'.$secsubcod.'"},';
	}
	$dataSubsectoresCom = substr($dataSubsectoresCom,0,strlen($dataSubsectoresCom)-1);

	//Cargo las Categorias - Compras
	$queryClas = "	SELECT S.CATCODIGO
		FROM PER_CATE C
		LEFT OUTER JOIN CAT_MAEST S ON S.CATCODIGO=C.CATCODIGO
		WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";

	$TableClas = sql_query($queryClas,$conn);
	for($j=0; $j<$TableClas->Rows_Count; $j++){
	$rowClas= $TableClas->Rows[$j];
	$catcodigo 	= trim($rowClas['CATCODIGO']);
	$dataCategoriasCom .= '{"catcodigo":"'.$catcodigo.'"},';
	}
	$dataCategoriasCom = substr($dataCategoriasCom,0,strlen($dataCategoriasCom)-1);

	//Cargo las SubCategorias - Compras
	$queryClas = "	SELECT S.CATSUBCOD
		FROM PER_SCAT C
		LEFT OUTER JOIN CAT_SUB S ON S.CATSUBCOD=C.CATSUBCOD
		WHERE C.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";

	$TableClas = sql_query($queryClas,$conn);
	for($j=0; $j<$TableClas->Rows_Count; $j++){
	$rowClas= $TableClas->Rows[$j];
	$catsubcod 	= trim($rowClas['CATSUBCOD']);
	$dataSubcategoriasCom .= '{"catsubcod":"'.$catsubcod.'"},';
	}
	$dataSubcategoriasCom = substr($dataSubcategoriasCom,0,strlen($dataSubcategoriasCom)-1);

//Clasificacion de Ventas
$tmpl->setVariable('dataSectoresVen'		, $dataSectoresVen 			);
$tmpl->setVariable('dataSubsectoresVen'		, $dataSubsectoresVen 		);
$tmpl->setVariable('dataCategoriasVen'		, $dataCategoriasVen 		);
$tmpl->setVariable('dataSubcategoriasVen'	, $dataSubcategoriasVen 	);

//Clasificacion de Compras
$tmpl->setVariable('dataSectoresCom'		, $dataSectoresCom 			);
$tmpl->setVariable('dataSubsectoresCom'		, $dataSubsectoresCom 		);
$tmpl->setVariable('dataCategoriasCom'		, $dataCategoriasCom 		);
$tmpl->setVariable('dataSubcategoriasCom'	, $dataSubcategoriasCom 	);
///////////////////////////////////////////////////
	
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
	
	//Cargo los Sectores

	$campo = ($IdiomView=='ING')? 'S.SECDESING' : 'S.SECDESCRI';
		
	$query = "	SELECT SECCODIGO, $campo AS SECDESCRI
				FROM SEC_MAEST S
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$seccodigo = trim($row['SECCODIGO']);
		$secdescri = trim($row['SECDESCRI']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccodigo	);
		$tmpl->setVariable('secdescri'	, $secdescri	);
		$tmpl->parse('sectores');
	}
	
	//Cargo los SubSectores
	$campo = ($IdiomView=='ING')? 'SB.SECSUBDESING' : 'SB.SECSUBDES';
	$query = "	SELECT SECSUBCOD, $campo AS SECSUBDES
				FROM SEC_SUB SB
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$secsubcod = trim($row['SECSUBCOD']);
		$secsubdes = trim($row['SECSUBDES']);
		
		$tmpl->setCurrentBlock('subsectores');
		$tmpl->setVariable('secsubcod'	, $secsubcod	);
		$tmpl->setVariable('secsubdes'	, $secsubdes	);
		$tmpl->parse('subsectores');
	}
	
	//Cargo los Categorias
	$campo = ($IdiomView=='ING')? 'C.CATDESING' : 'C.CATDESCRI';
	$query = "	SELECT CATCODIGO, $campo AS CATDESCRI
				FROM CAT_MAEST C
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catcodigo = trim($row['CATCODIGO']);
		$catdescri = trim($row['CATDESCRI']);
		
		$tmpl->setCurrentBlock('categoria');
		$tmpl->setVariable('catcodigo'	, $catcodigo	);
		$tmpl->setVariable('catdescri'	, $catdescri	);
		$tmpl->parse('categoria');
	}
	
	//Cargo los SubCategorias
	$campo = ($IdiomView=='ING')? 'CS.CATSUBDESING' : 'CS.CATSUBDES';
	$query = "	SELECT CATSUBCOD, $campo AS CATSUBDES
				FROM CAT_SUB CS
				WHERE ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catsubcod = trim($row['CATSUBCOD']);
		$catsubdes = trim($row['CATSUBDES']);
		
		$tmpl->setCurrentBlock('subcategoria');
		$tmpl->setVariable('catsubcod'	, $catsubcod	);
		$tmpl->setVariable('catsubdes'	, $catsubdes	);
		$tmpl->parse('subcategoria');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	//Tipo de Perfiles
	$query = "	SELECT PERTIPO,PERTIPDES$IdiomView AS PERTIPDES
				FROM PER_TIPO
				WHERE ESTCODIGO=1				
				ORDER BY PERTIPO ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pertipo 	= trim($row['PERTIPO']);
		$pertipdes	= trim($row['PERTIPDES']);
		
		$tmpl->setCurrentBlock('pertipos');
		$tmpl->setVariable('pertipo'	, $pertipo 		);
		$tmpl->setVariable('pertipdes'	, $pertipdes	);		
		$tmpl->parse('pertipos');
	}

		//Listado de Paises
		$query = "SELECT PAICODIGO,PAIDESCRI,PAIDESCRIING
		FROM TBL_PAIS
		ORDER BY PAIDESCRI";

		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$paicod = trim($row['PAICODIGO']);
		if ($peridioma=='ESP')
		{
			$paides = trim($row['PAIDESCRI']);
		}else{
			$paides = trim($row['PAIDESCRIING']);
		}


		$tmpl->setCurrentBlock('paises');
		$tmpl->setVariable('paicodigo'	, $paicod 	);
		$tmpl->setVariable('paidescri'	, $paides 	);

		$tmpl->parseCurrentBlock('paises');
		}

		$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
		$Table2 = sql_query($query2, $conn);
		for ($i = 0; $i < $Table2->Rows_Count; $i++) {
			$row = $Table2->Rows[$i];
			$tipoevento = trim($row['ZVALUE']);
			if ($tipoevento == 'true') {
				$tmpl->setVariable('valorevento', '2');
				$tmpl->setVariable('mostrarfiltrotipo', '');
			}else if ($tipoevento == 'false'){
				$tmpl->setVariable('valorevento', '0');
				$tmpl->setVariable('mostrarfiltrotipo', 'd-none');
			}else{
				$tmpl->setVariable('valorevento', '1');
				$tmpl->setVariable('mostrarfiltrotipo', 'd-none');
			}
		}
	//--------------------------------------------------------------------------------------------------------------
	/////////////////NOMBRE BANNERS/////////////////////
	$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
				FROM PAR_MAEST 
				WHERE PARCODIGO='directorio'";
		$Tableparam = sql_query($queryparam, $conn);
		$rowparam = $Tableparam->Rows[0];
		$parnombre = trim($rowparam['PARNOMBRE']);
		$paneladmin = trim($rowparam['PARCODIGO']);
		$tmpl->setVariable('nombre'.$paneladmin, $parnombre);

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
