<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND SECDESCRI CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT TP.PAICODIGO,TP.PAILET,TP.PAIDESCRI,TP.PAIDESCRIING,TP.PAIREG,TP.TIMEREG,ZO.TIMDESCRI
				FROM TBL_PAIS TP
				LEFT OUTER JOIN TIM_ZONE ZO ON ZO.TIMREG=TP.TIMEREG
				ORDER BY PAIDESCRI ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];



		$paicodigo 	= trim($row['PAICODIGO']);
		$pailet 	= trim($row['PAILET']);
		$paidescri 	=  trim($row['PAIDESCRI']);
		$paidescriing 	= trim($row['PAIDESCRIING']);
		$paireg 	= trim($row['PAIREG']);
		$timereg 	= trim($row['TIMEREG']);
		$timdescri 	= trim($row['TIMDESCRI']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('paicodigo'	, $paicodigo);
		$tmpl->setVariable('pailet'	, $pailet);
		$tmpl->setVariable('paidescri'	, $paidescri);
		$tmpl->setVariable('paidescriing'	, $paidescriing);
		$tmpl->setVariable('paireg'	, $paireg);
		$tmpl->setVariable('timereg'	, $timereg);
		$tmpl->setVariable('timdescri'	, $timdescri);

	
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
