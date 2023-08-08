<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
	require_once GLBRutaFUNC.'/constants.php';		

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('popupinicio.html');
	DDIdioma($tmpl);

	$percodlog = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';

	if($percodlog!=0){


		$queryinfo = "	SELECT POP_URL
		FROM ADM_POP WHERE POP_REG=0";

		$Tableinfo = sql_query($queryinfo,$conn);

		if($Tableinfo->Rows_Count>0){
	$rowinfo = $Tableinfo->Rows[0];

	$popupinfo 	= trim($rowinfo['POP_URL']);

	$tmpl->setVariable('popupinfo'	, $popupinfo	);


	}


		$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
		$Tablereunion = sql_query($queryreunion, $conn);
		$rowreunion = $Tablereunion->Rows[0];
		$tiporeunion = trim($rowreunion['ZVALUE']);

	if ($tiporeunion == 'true') {
		$tmpl->setVariable('linkintereses'	, 'd-none'	);
		$tmpl->setVariable('linkoferta'	, ''	);
		$tmpl->setVariable('linkdemanda'	, ''	);
	}else{
		$tmpl->setVariable('linkintereses'	, ''	);
		$tmpl->setVariable('linkoferta'	, 'd-none'	);
		$tmpl->setVariable('linkdemanda'	, 'd-none'	);
	}


		$query = "	SELECT P.PAICODIGO,P.TIMREG2, P.PEREMPDES, P.PERIDIOMA
					FROM PER_MAEST P
					WHERE P.PERCODIGO=$percodlog ";
				
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$paicodigo 	= trim($row['PAICODIGO']);
			$timreg 	= trim($row['TIMREG2']);
			$perempdes 	= trim($row['PEREMPDES']);
			$peridioma 	= trim($row['PERIDIOMA']);

			$tmpl->setVariable('perempdes'	, $perempdes 	);
			$tmpl->setVariable('peridioma'	, $peridioma 	);
			$tmpl->setVariable('percodigo'	, $percodlog 	);
	
		}

	
			//Busco la disponibilidad
			$queryDisp="SELECT PERDISFCH,PERDISHOR,DISP_BOOL 
						FROM PER_DISP
						WHERE PERCODIGO=$percodlog
						ORDER BY PERDISFCH,PERDISHOR ";
			$TableDet = sql_query($queryDisp,$conn);
			$dataDisp = '';
			for($j=0; $j<$TableDet->Rows_Count; $j++){
				$rowDet= $TableDet->Rows[$j];
				$perdisfch 	= BDConvFch($rowDet['PERDISFCH']);
				$dispbool 	= trim($rowDet['DISP_BOOL']);
				$perdishor 	= substr(trim($rowDet['PERDISHOR']),0,5);
				$dataDisp .= '{"fecha":"'.$perdisfch.'","hora":"'.$perdishor.'","dispbool":'.$dispbool.'},';
								
			}
			$dataDisp = substr($dataDisp,0,strlen($dataDisp)-1);
			$tmpl->setVariable('dataDisp'	, $dataDisp	);
			
			//Cargo los Sectores - Ventas
			$queryClas = "	SELECT S.SECCODIGO
							FROM PER_SECT C
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=C.SECCODIGO
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('V','A') ";
			
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
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
							WHERE C.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 AND C.PERVENCOM IN ('C','A') ";
			
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
			

	// Read the JSON file 
	$json = file_get_contents('../api/timezone.json');
	// Decode the JSON file
	$json_data = json_decode($json,true);
	foreach ($json_data as &$value) {
		$tmpl->setCurrentBlock('zonahoraria');
		$tmpl->setVariable('timregcod'	, $value 		);
		$tmpl->setVariable('timdescri'	, $value	);
		if($value==$timreg){
			$tmpl->setVariable('timsel', 'selected' );
		}
		$tmpl->parse('zonahoraria');
	}

}
		
	$tmpl->show();
	
?>	
