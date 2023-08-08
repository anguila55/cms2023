<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$paicodigo = (isset($_POST['paicodigo']))? trim($_POST['paicodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	

	//logerror("MST:".$iditraitm);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	
	if($paicodigo!=0){
		$query = "	SELECT PAICODIGO,PAILET,PAIDESCRI,PAIDESCRIING,PAIREG,TIMEREG
		FROM TBL_PAIS WHERE PAICODIGO = $paicodigo";

		//logerror($query);
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$paicodigo 	= trim($row['PAICODIGO']);
			$pailet 	= trim($row['PAILET']);
			$paidescri 	=  trim($row['PAIDESCRI']);
			$paidescriing 	= trim($row['PAIDESCRIING']);
			$paireg 	= trim($row['PAIREG']);
			$timereg 	= trim($row['TIMEREG']);
			
			
			$tmpl->setVariable('paicodigo'	, $paicodigo);
			$tmpl->setVariable('pailet'	, $pailet);
			$tmpl->setVariable('paidescri'	, $paidescri);
			$tmpl->setVariable('paidescriing'	, $paidescriing);
			$tmpl->setVariable('paireg'	, $paireg);
			$tmpl->setVariable('timereg'	, $timereg);
			
			
		}



		
		
	}else{
		$paicodigo = '';
	}

	// $queryidioma = "SELECT * FROM IDI_MAEST";
	// $Tableidioma = sql_query($queryidioma,$conn);
	// for($i=0; $i<$Tableidioma->Rows_Count; $i++){
		
	// 	$row = $Tableidioma->Rows[$i];
	// 	$idicodigo 	= trim($row['IDICODIGO']);
	// 	$ididescri 	= trim($row['IDIDESCRI']);
		
	// 	$tmpl->setCurrentBlock('idioma');
	// 	$tmpl->setVariable('idicodigo',$idicodigo);

	// 	$tmpl->setVariable('ididescri',$ididescri);

	// 	if($idicodigo == $idicodigomst){
	// 		$tmpl->setVariable('selected', 'selected');
	// 	}

	// 	$tmpl->parse('idioma');
	//}
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
