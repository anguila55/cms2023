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
	$zonahoritm = (isset($_POST['zonahoritm']))? trim($_POST['zonahoritm']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	

	//logerror("MST:".$iditraitm);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	
	if($zonahoritm!=0){
		$query = "	SELECT TIMREG,  TIMDESCRI , TIMOFFSET, TIMOFFSETDST
		FROM TIM_ZONE WHERE TIMREG = $zonahoritm";

		//logerror($query);
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$timcodigomst 	= trim($row['TIMDESCRI']);
			$timoffsetmst 	= trim($row['TIMOFFSET']);
			$timoffsetdstmst 	= trim($row['TIMOFFSETDST']);
			$zonahoritmmst 	= trim($row['TIMREG']);
			
			
			$tmpl->setVariable('timcodigomst'	, $timcodigomst);
			$tmpl->setVariable('timoffsetmst'	, $timoffsetmst);
			$tmpl->setVariable('timoffsetdstmst'	, $timoffsetdstmst);
			$tmpl->setVariable('zonahoritmmst'	, $zonahoritmmst);
			
			
		}



		
		
	}else{
		$zonahoritmmst = '';
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
