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
	$seccodigo = (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($seccodigo!=0){
		$query = "	SELECT ENC_REG,ENC_ID,ENC_TIPPER,ESTCODIGO
					FROM ENC_GRAL
					WHERE ENC_REG=$seccodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$encreg 	= trim($row['ENC_REG']);
			$encid 	= trim($row['ENC_ID']);
			$enctipper 	= trim($row['ENC_TIPPER']);
			$estcodigo = trim($row['ESTCODIGO']);
			
			$tmpl->setVariable('numeroencuesta'	, $encreg);
			$tmpl->setVariable('idencuesta'	, $encid);
			$tmpl->setVariable('tipoperfil'	, $enctipper);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			
		}


		
	}

	$query = "	SELECT PERTIPO, PERTIPDESESP
				FROM PER_TIPO
				WHERE ESTCODIGO<>3
				ORDER BY PERTIPDESESP ";
		$Table = sql_query($query,$conn);
			for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$pertipo 	= trim($row['PERTIPO']);
			$pertipodesesp 	= trim($row['PERTIPDESESP']);
		
			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('pertipo'	, $pertipo);
			$tmpl->setVariable('pertipodesesp'	, $pertipodesesp);
			$tmpl->parse('browser');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
