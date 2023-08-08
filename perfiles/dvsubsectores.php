<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('dvsubsectores.html');
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$sectores 		= '';
	$subsectores 	= '';
	
	if(isset($_POST['sectores'])){
		foreach($_POST['sectores'] as $ind => $data){
			$sectores .= $data['seccodigo'].','; 
		}
		if(trim($sectores)==',') $sectores='';
		else $sectores = '0,'.$sectores;
	}
	
	if(isset($_POST['subsectores'])){
		foreach($_POST['subsectores'] as $ind => $data){
			$subsectores .= $data['secsubcod'].','; 
		}
		if(trim($subsectores)==',') $subsectores='';
		else $subsectores = '0,'.$subsectores;
	}
	

	if ($IdiomView == 'ESP'){
		$ORDER_SUBSEC = 'SM.SECDESCRI';
		}else if($IdiomView == 'POR'){
			$ORDER_SUBSEC = 'SM.SECDESCRI';
		}else if($IdiomView == 'ING'){
			$ORDER_SUBSEC = 'SM.SECDESING';
		}
	//Cargo los Subsectores segun los sectores seleccionados
	$query = "	SELECT SS.SECSUBCOD,SS.SECSUBDES,SS.SECSUBDESING,SS.SECCODIGO,SM.SECDESCRI, SM.SECDESING
				FROM SEC_SUB SS
				LEFT OUTER JOIN SEC_MAEST SM ON SM.SECCODIGO = SS.SECCODIGO
				WHERE SS.SECCODIGO IN ($sectores 0) AND SS.ESTCODIGO<>3 ORDER BY $ORDER_SUBSEC";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$secsubcod 		= trim($row['SECSUBCOD']);
		$secsubdes 		= trim($row['SECSUBDES']);
		$secsubdesing 	= trim($row['SECSUBDESING']);
		$seccod 		= trim($row['SECCODIGO']);
		$secdescri 		= trim($row['SECDESCRI']);
		$secdescriing 		= trim($row['SECDESING']);
		
		$tmpl->setCurrentBlock('subsectores');
		$tmpl->setVariable('secsubcod'		, $secsubcod	);

		if ($IdiomView == 'ESP'){
			$tmpl->setVariable('secsubdes'	, $secsubdes	);
			$tmpl->setVariable('secdescri'	, $secdescri	);
			}else if($IdiomView == 'POR'){
				$tmpl->setVariable('secsubdes'	, $secsubdes	);
				$tmpl->setVariable('secdescri'	, $secdescri	);
			}else if($IdiomView == 'ING'){
				$tmpl->setVariable('secsubdes'	, $secsubdesing	);
				$tmpl->setVariable('secdescri'	, $secdescriing	);
			}

		$tmpl->setVariable('seccod'			, $seccod	);
		
		if(strpos($subsectores,$secsubcod.',')!==false){
			$tmpl->setVariable('secsubchecked'	, 'checked'	);
		}
		
		$tmpl->parse('subsectores');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
