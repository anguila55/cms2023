<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('clasificar.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$percodlog = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';

	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:none;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:none;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$pervencom = (isset($_POST['pervencom']))? trim($_POST['pervencom']) : 'V';
	
	//Productos de Compra-Venta
	$tmpl->setVariable('pervencom'	, $pervencom	);
	$tmpl->setVariable('percodigo'	, $percodigo	);

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	//// ME FIJO QUE TIPO DE REUNION ES
	$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
	$Tablereunion = sql_query($queryreunion, $conn);
		$rowreunion = $Tablereunion->Rows[0];
		$tiporeunion = trim($rowreunion['ZVALUE']);

	//var_dump($tiporeunion);die;
	if ($percodigo==$percodlog){
		////Verificio si es tipo Oferta/Demanda o es Networking
		if ($tiporeunion == 'true') {
			$tmpl->setVariable('linkoferta'	, ''	);
			$tmpl->setVariable('linkdemanda'	, ''	);
		}else{
			$tmpl->setVariable('linkoferta'	, 'd-none'	);
			$tmpl->setVariable('linkdemanda'	, 'd-none'	);
		}	
	}else{
		$tmpl->setVariable('linkoferta'	, 'd-none'	);
		$tmpl->setVariable('linkdemanda'	, 'd-none'	);
	}

	if ($tiporeunion == 'true') {
	
		if ($IdiomView == 'ESP'){
			$tmpl->setVariable('pervencomdes'	, 'Indique a qué Sectores / Subsectores pertenece su:'	);
			}else if($IdiomView == 'POR'){
	
				$tmpl->setVariable('pervencomdes'	, 'Indique para quais Setores/Subsetores você:'	);
			}else if($IdiomView == 'ING'){
				$tmpl->setVariable('pervencomdes'	, 'Indicate to which Sectors / Subsectors you:');
			}
	
		}else{
			if ($IdiomView == 'ESP'){
				$tmpl->setVariable('pervencomdes'	, 'Indique que Sectores / Subsectores está interesado:'	);
				}else if($IdiomView == 'POR'){
		
					$tmpl->setVariable('pervencomdes'	, 'Indique os Setores/Subsetores de seu interesse:'	);
				}else if($IdiomView == 'ING'){
					$tmpl->setVariable('pervencomdes'	, 'Indicate which Sectors / Subsectors you are interested in:');
				}
		}
	
	if($pervencom == 'V'){
		
		$tmpl->setVariable('backgroundcolor1'	, 'bg-main-event'	);
		$tmpl->setVariable('backgroundcolor2'	, 'bg-color-gris');
	}else if($pervencom == 'C'){
		
		$tmpl->setVariable('backgroundcolor1'	, 'bg-color-gris'	);
		$tmpl->setVariable('backgroundcolor2'	, 'bg-main-event'	);
	}
	
	
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

		if ($IdiomView == 'ESP'){
			$tmpl->setVariable('catdescri'	, $catdescri	);
			}else if($IdiomView == 'POR'){
	
				$tmpl->setVariable('catdescri'	, $catdescri	);
			}else if($IdiomView == 'ING'){
				$tmpl->setVariable('catdescri'	, $catdesing	);
			}

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

		if ($IdiomView == 'ESP'){
			$tmpl->setVariable('catsubdes'	, $catsubdes	);
			}else if($IdiomView == 'POR'){
	
				$tmpl->setVariable('catsubdes'	, $catsubdes	);
			}else if($IdiomView == 'ING'){
				$tmpl->setVariable('catsubdes'	, $catsubdesing	);
			}

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
