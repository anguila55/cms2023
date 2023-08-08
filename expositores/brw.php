<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
	require_once GLBRutaFUNC.'/constants.php';//Idioma


			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "SELECT EM.EXPREG, EM.EXPNOMBRE, EM.EXPWEB, EM.EXPMAIL, EM.EXPSTAND, EM.EXPRUBROS, EM.EXPPOSX, EM.EXPPOSY, EM.ESTCODIGO,EM.EXPCATEGO,EM.EXPPOS,EM.QRCODE,EC.CATDESCRI
				FROM EXP_MAEST EM
				LEFT OUTER JOIN EXP_CAT EC ON EM.EXPCATEGO = EC.CATREG
				WHERE ESTCODIGO<>3
				ORDER BY EXPNOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$expreg 	= trim($row['EXPREG']);
		$expnombre 	= trim($row['EXPNOMBRE']);
		$expweb 	= trim($row['EXPWEB']);
		$expmail 	= trim($row['EXPMAIL']);
		$expstand 	= trim($row['EXPSTAND']);
		$exprubros 	= trim($row['EXPRUBROS']);
		$expposx 	= trim($row['EXPPOSX']);
		$expposy 	= trim($row['EXPPOSY']);
		$expcatego 	= trim($row['CATDESCRI']);
		$exppos 	= trim($row['EXPPOS']);
		$qrcode     = trim($row['QRCODE']);
	
		
		//Asignamos los datos para cargar desde el templatee
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('expreg'		, $expreg);
		$tmpl->setVariable('expnombre'	, $expnombre);
		$tmpl->setVariable('expweb'		, $expweb);
		$tmpl->setVariable('expmail'	, $expmail);
		$tmpl->setVariable('exprubros'	, $exprubros);
		$tmpl->setVariable('expposx'	, $expposx);
		$tmpl->setVariable('expposy'	, $expposy);
		$tmpl->setVariable('expcatego'	, $expcatego);
		$tmpl->setVariable('exppos'		, $exppos);
		$tmpl->setVariable('qrcodevisible'	, 'd-none');
		if ($qrcode!=''){
			$tmpl->setVariable('qrcodevisible'	, '');
			$tmpl->setVariable('qrcode'	, $qrcode);
		}
		$tmpl->setVariable('copyurl'	, URL_WEB.'sponsor/bsq?id='.$expreg);
		 
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
