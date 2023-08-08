<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
	require_once GLBRutaFUNC.'/constants.php';

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);


	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$fltbuscar 	= (isset($_POST['fltbuscar']))? $_POST['fltbuscar']:0;
	
	$conn= sql_conectar();//Apertura de Conexion
	if (in_array($percodigo, $arraysuperadmin)){
		$query = "	SELECT PARREG,PARCODIGO,PARVALOR,PARDESCRI,PARNOMESP,PARNOMING,PARNOMPOR
					FROM PAR_MAEST
					WHERE PARSEC = $fltbuscar
					ORDER BY PARREG ";



		//logerror($query);
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$parreg 	= trim($row['PARREG']);
			$parcodigo 	= trim($row['PARCODIGO']);
			$parvalor  	= trim($row['PARVALOR']);
			$pardescri 	= trim($row['PARDESCRI']);
			$parnomesp 	= trim($row['PARNOMESP']);
			$parnoming 	= trim($row['PARNOMING']);
			$parnompor	= trim($row['PARNOMPOR']);

			
			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('parreg' 	, $parreg);
			$tmpl->setVariable('pardescri' 	, $pardescri);
			$tmpl->setVariable('parnomesp' 	, $parnomesp);
			$tmpl->setVariable('parnoming' 	, $parnoming);
			$tmpl->setVariable('parnompor' 	, $parnompor);
			if ($parvalor== 'true' ){
				$tmpl->setVariable('vischecked','checked');
			}else{
				$tmpl->setVariable('vischecked','');
			}

			$tmpl->parse('browser');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
