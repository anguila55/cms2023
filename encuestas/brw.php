<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	$tmpl= new HTML_Template_Sigma();	
				
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$pertipo = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	
	$conn= sql_conectar();//Apertura de Conexion

	

	$query = "	SELECT ENC_REG,ENC_ID,ENC_TIPPER,ESTCODIGO
					FROM ENC_GRAL
					WHERE ESTCODIGO<>3 AND ENC_TIPPER = 0 OR ENC_TIPPER = $pertipo
					ORDER BY ENC_TIPPER ASC";
					 

		$Table = sql_query($query,$conn);


		if($Table->Rows_Count>0){
			
			
			for($i=0; $i<$Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];
			$encreg 	= trim($row['ENC_REG']);
			$encid 	= trim($row['ENC_ID']);
			$enctipper 	= trim($row['ENC_TIPPER']);
			
			$tmpl->setVariable('numeroencuesta'	, $encreg);
			if ($encid==''){
				$tmpl->setVariable('displayencuestas'	, 'd-none');
			}
			$tmpl->setVariable('idencuesta'	, $encid);
			$tmpl->setVariable('tipoperfil'	, $enctipper);
			
			}
		}

	//$tmpl->setVariable('idencuesta'	, '1FAIpQLScGwIKsRdU3Vxnq9hvn8ZVH0zrK7Kkv-mZxMIfhGqcz_Gfu9g'	);

	sql_close($conn);

	$tmpl->show();
	
?>	
