<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	

	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT M.MESCODIGO, M.MESNUMERO,M.PERCODIGO, P.PERNOMBRE, P.PERAPELLI, P.PERCOMPAN,M.ESTCODIGO
				FROM MES_MAEST M
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=M.PERCODIGO
				WHERE M.ESTCODIGO<>3
				ORDER BY M.MESCODIGO ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$mescodigo 	= trim($row['MESCODIGO']);
		$mesnumero 	= trim($row['MESNUMERO']);
		$tipomesa 	= trim($row['ESTCODIGO']);
		$usuarionombre 	= trim($row['PERNOMBRE']);
		$usuarioapelli 	= trim($row['PERAPELLI']);
		$usuariocompan 	= trim($row['PERCOMPAN']);
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('mescodigo'	, $mescodigo);
		$tmpl->setVariable('mesnumero'	, $mesnumero);

		if ($IdiomView == 'ESP')
		{
			if($tipomesa == 1){
				$tmpl->setVariable('tipomesaelegida'	, 'Fija');
			}else{
				$tmpl->setVariable('tipomesaelegida'	, 'Flotante');
			}
			
		}else if ($IdiomView == 'ING'){
			if($tipomesa == 1){
				$tmpl->setVariable('tipomesaelegida'	, 'Fixed');
			}else{
				$tmpl->setVariable('tipomesaelegida'	, 'Float');
			}
		}else{
			if($tipomesa == 1){
				$tmpl->setVariable('tipomesaelegida'	, 'Fija');
			}else{
				$tmpl->setVariable('tipomesaelegida'	, 'Flutuante');
			}
		}


		
		$tmpl->setVariable('usuarionombre'	, $usuarionombre);
		$tmpl->setVariable('usuarioapelli'	, $usuarioapelli);
		$tmpl->setVariable('usuariocompan'	, $usuariocompan);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
