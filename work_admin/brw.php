<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND WORK_TITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Seleccionamos los datos que se mostrarar en el brw
	$query = "	SELECT A.WORK_REG, SUBSTRING(A.WORK_TITULO FROM 1 FOR 20) AS WORK_TITULO, 
						SUBSTRING(A.WORK_DESCRI FROM 1 FOR 20) AS WORK_DESCRI, A.WORK_FCH, 
						A.WORK_HORINI, A.WORK_HORFIN, A.ESTCODIGO
				FROM WORK_MAEST A
				WHERE A.ESTCODIGO=1 $where
				ORDER BY A.WORK_FCH, A.WORK_HORINI";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$workreg 	= trim($row['WORK_REG']);
		$worktitulo 	= trim($row['WORK_TITULO']);
		$workdescri 	= trim($row['WORK_DESCRI']);
		$workfch     = BDConvFch($row['WORK_FCH']);

		$haux = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['WORK_HORINI']))));
		$workhorini = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux)));   // pongo en horario 0
		 //Pongo la hora en huso horario argentino

		 $haux2 = date('H:i:s', strtotime('+10800 seconds', strtotime(trim($row['WORK_HORFIN']))));
		$workhorfin = date('H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2)));   // pongo en horario 0
		$workfchorden	= substr($workfch, 6, 4) . substr($workfch, 3, 2) . substr($workfch, 0, 2); //Formato calendario (yyyy-mm-dd)

		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('workreg'		, $workreg	);
		$tmpl->setVariable('worktitulo'	, $worktitulo	);
		$tmpl->setvariable('workfchorden', $workfchorden);
		$tmpl->setVariable('workdescri'	, $workdescri	);
		$tmpl->setVariable('workfch'		, $workfch	);
		$tmpl->setVariable('workhorini'	, $workhorini	);
		$tmpl->setVariable('workhorfin'	, $workhorfin	);
		$tmpl->parse('browser');

		
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
