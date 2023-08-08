<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('encbrw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($agereg!=0){
		$query = "	SELECT A.AGETITULO
					FROM AGE_MAEST A
					WHERE A.AGEREG=$agereg";
	
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$agetitulo 	= trim($row['AGETITULO']);
		$tmpl->setVariable('agereg'		, $agereg );
		$tmpl->setVariable('agetitulo'	, $agetitulo );
		
		
		$query = "	SELECT C.ENCREG,E.ENCDESCRI
					FROM AGE_ENCU C
					LEFT OUTER JOIN ENC_CABE E ON C.ENCREG=E.ENCREG
					WHERE C.AGEREG=$agereg";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$encreg 	= trim($row['ENCREG']);
			$encdescri 	= trim($row['ENCDESCRI']);
			
			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('agenda'		, $agereg		);
			$tmpl->setVariable('encreg'		, $encreg		);
			$tmpl->setVariable('encdescri'	, $encdescri	);
			$tmpl->parse('browser');
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
