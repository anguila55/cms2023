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
	
	$query = "	SELECT TIMREG,  TIMDESCRI , TIMOFFSET, TIMOFFSETDST
				FROM TIM_ZONE
			
				ORDER BY TIMDESCRI ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$timcodigo 	= trim($row['TIMDESCRI']);
		$timoffset 	= trim($row['TIMOFFSET']);
		$timoffsetdst 	= trim($row['TIMOFFSETDST']);
		$zonahoritm 	= trim($row['TIMREG']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('timcodigo'	, $timcodigo);
		$tmpl->setVariable('timoffset'	, $timoffset);
		$tmpl->setVariable('timoffsetdst'	, $timoffsetdst);
		$tmpl->setVariable('zonahoritm'	, $zonahoritm);


		
		
		$timoffset 	= $timoffset / 60.0 /60.0;
		$signo		= ($timoffset>0)? '+':'-';
		$timoffset	= abs($timoffset);
		$horas 		= floor($timoffset);
		$minutos 	= ($timoffset - $horas) * 60;
		
		$horas = str_pad($horas, 2, "0", STR_PAD_LEFT);
		$minutos = str_pad($minutos, 2, "0", STR_PAD_LEFT);
		
		
		$tmpl->setVariable('timdescri'	, "(GMT$signo$horas:$minutos)"	);






		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
