<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	//require_once GLBRutaFUNC.'/idioma.php';//Idioma	
		
	$IdiomView = '';
	$peridioma = (isset($_GET['ID']))? trim($_GET['ID']):'ESP'; //Nota desde el home acceso directo	//--------------------------------------------------------------------------------------------------------------
	
	if ($peridioma=='ESP'){

	  require_once GLBRutaFUNC.'/idiomaesp.php';
	  
	  $IdiomView = strtoupper('esp');
	  
	  }else{
		  
		  require_once GLBRutaFUNC.'/idiomaing.php';
		  $IdiomView = strtoupper('ing');
	  }
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('clasificarregistro.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	/*$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:none;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:none;'	);*/
	$tmpl->setVariable('btncategorias'	, 'display:none;'	);
	$tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	$tmpl->setVariable('peridioma'	, $peridioma);
	//--------------------------------------------------------------------------------------------------------------
	
	$pervencom = (isset($_POST['pervencom']))? trim($_POST['pervencom']) : 'V';
	
	//Productos de Compra-Venta
	$tmpl->setVariable('pervencom'	, $pervencom	);
	
	if($pervencom == 'V'){
		if ($peridioma=='ESP'){

			$tmpl->setVariable('pervencomdes'	, 'Indique en qué sectores / sub-sectores se encuentra la OFERTA de su empresa'	);


		}else{

			$tmpl->setVariable('pervencomdes'	, 'Indicate the sectors/ sub-sectors your company OFFERS'	);

		}
	}else if($pervencom == 'C'){
		if ($peridioma=='ESP'){

			$tmpl->setVariable('pervencomdes'	, '¿Qué sectores o productos le interesa ver o comprar?'	);


		}else{

			$tmpl->setVariable('pervencomdes'	, 'Which sectors or products are you interested in seeing or buying?'	);
			
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Data Sectores 
	$sectores = '';
	$subsectores = '';
	$categorias = '';
	$subcategorias = '';
	
	if(isset($_POST['dataClasificar'])){
		if(isset($_POST['dataClasificar']['sectores'])){
			foreach($_POST['dataClasificar']['sectores'] as $ind => $data){
				$sectores .= $data['seccodigo'].','; 
			}
			if(trim($sectores)==',') $sectores='';
			else $sectores = '0,'.$sectores;
		}
		
		//Data Subsectores
		if(isset($_POST['dataClasificar']['subsectores'])){
			foreach($_POST['dataClasificar']['subsectores'] as $ind => $data){
				$subsectores .= $data['secsubcod'].','; 
			}
			if(trim($subsectores)==',') $subsectores='';
			else $subsectores = '0,'.$subsectores;
		}
		
		//Data Categorias
		if(isset($_POST['dataClasificar']['categorias'])){
			foreach($_POST['dataClasificar']['categorias'] as $ind => $data){
				$categorias .= $data['catcodigo'].','; 
			}
			if(trim($categorias)==',') $categorias='';
			else $categorias = '0,'.$categorias;
		}
	
		//Data Subcategorias
		if(isset($_POST['dataClasificar']['subcategorias'])){
			foreach($_POST['dataClasificar']['subcategorias'] as $ind => $data){
				$subcategorias .= $data['catsubcod'].','; 
			}
			if(trim($subcategorias)==',') $subcategorias='';
			else $subcategorias = '0,'.$subcategorias;
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	//Cargo los Sectores 
	if ($IdiomView == 'ESP'){
		$ORDER_SEC = 'SECDESCRI';
		}else if($IdiomView == 'POR'){
			$ORDER_SEC = 'SECDESCRI';
		}else if($IdiomView == 'ING'){
			$ORDER_SEC = 'SECDESING';
		}

	//Cargo los Sectores 
	$querysec = "	SELECT SECCODIGO,SECDESCRI,SECDESING
				FROM SEC_MAEST
				WHERE ESTCODIGO<>3
				ORDER BY $ORDER_SEC ";
	$Tablesec = sql_query($querysec,$conn);
	for($j=0; $j<$Tablesec->Rows_Count; $j++){
		$rowsec= $Tablesec->Rows[$j];
		$seccodigo = trim($rowsec['SECCODIGO']);
		$secdescri = trim($rowsec['SECDESCRI']);
		$secdesing = trim($rowsec['SECDESING']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccodigo	);

		if ($IdiomView == 'ESP'){
			$tmpl->setVariable('secdescri'	, $secdescri	);
			}else if($IdiomView == 'POR'){
	
				$tmpl->setVariable('secdescri'	, $secdescri	);
			}else if($IdiomView == 'ING'){
				$tmpl->setVariable('secdescri'	, $secdesing	);
			}

		
		
		if(strpos($sectores,$seccodigo.',')!==false){
			$tmpl->setVariable('secchecked'	, 'checked'	);
		}
		if ($IdiomView == 'ESP'){
			$ORDER_SUBSEC = 'SM.SECDESCRI';
			}else if($IdiomView == 'POR'){
				$ORDER_SUBSEC = 'SM.SECDESCRI';
			}else if($IdiomView == 'ING'){
				$ORDER_SUBSEC = 'SM.SECDESING';
			}
	
		//Cargo los Subsectores 
		$query = "	SELECT SS.SECSUBCOD,SS.SECSUBDES,SS.SECSUBDESING,SS.SECCODIGO, SM.SECDESCRI, SM.SECDESING
					FROM SEC_SUB SS
					LEFT OUTER JOIN SEC_MAEST SM ON SM.SECCODIGO = SS.SECCODIGO
					WHERE SS.SECCODIGO = $seccodigo AND SS.ESTCODIGO<>3 ORDER BY $ORDER_SUBSEC";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];
			$secsubcod 		= trim($row['SECSUBCOD']);
			$secsubdes 		= trim($row['SECSUBDES']);
			$secsubdesing 	= trim($row['SECSUBDESING']);
			$seccod 		= trim($row['SECCODIGO']);

			
			$tmpl->setCurrentBlock('subsectores');
			$tmpl->setVariable('secsubcod'		, $secsubcod	);
	
	
			if ($IdiomView == 'ESP'){
				$tmpl->setVariable('secsubdes'	, $secsubdes	);
				}else if($IdiomView == 'POR'){
					$tmpl->setVariable('secsubdes'	, $secsubdes	);
				}else if($IdiomView == 'ING'){
					$tmpl->setVariable('secsubdes'	, $secsubdesing	);

				}
	
			$tmpl->setVariable('seccod'			, $seccod		);
			
			if(strpos($subsectores,$secsubcod.',')!==false){
				$tmpl->setVariable('secsubchecked'	, 'checked'	);
			}
			
			$tmpl->parse('subsectores');
		}
		
		$tmpl->parse('sectores');
	}
	
	//Cargo las Categorias
	$query = "	SELECT CATCODIGO,CATDESCRI,CATDESING,SECSUBCOD
				FROM CAT_MAEST
				WHERE SECSUBCOD IN ($subsectores 0) AND ESTCODIGO<>3 ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catcodigo 	= trim($row['CATCODIGO']);
		$catdescri 	= trim($row['CATDESCRI']);
		$catdesing 	= trim($row['CATDESING']);
		$secscod 	= trim($row['SECSUBCOD']);
		
		$tmpl->setCurrentBlock('categorias');
		$tmpl->setVariable('catcodigo'	, $catcodigo	);
		$tmpl->setVariable('catdescri'	, $catdescri	);
		$tmpl->setVariable('catdesing'	, $catdesing	);
		$tmpl->setVariable('secscod'	, $secscod		);
		
		if(strpos($categorias,$catcodigo.',')!==false){
			$tmpl->setVariable('catchecked'	, 'checked'	);
		}
		
		$tmpl->parse('categorias');
	}
	
	//Cargo las Subcategorias
	$query = "	SELECT CATSUBCOD,CATSUBDES,CATSUBDESING,CATCODIGO
				FROM CAT_SUB
				WHERE CATCODIGO IN ($categorias 0) AND ESTCODIGO<>3 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$catsubcod 		= trim($row['CATSUBCOD']);
		$catsubdes 		= trim($row['CATSUBDES']);
		$catsubdesing 	= trim($row['CATSUBDESING']);
		$catcod 		= trim($row['CATCODIGO']);
		
		$tmpl->setCurrentBlock('subcategorias');
		$tmpl->setVariable('catsubcod'		, $catsubcod	);
		$tmpl->setVariable('catsubdes'		, $catsubdes	);
		$tmpl->setVariable('catsubdesing'	, $catsubdesing	);
		$tmpl->setVariable('catcod'			, $catcod	);
		
		if(strpos($subcategorias,$catsubcod.',')!==false){
			$tmpl->setVariable('catsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subcategorias');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
