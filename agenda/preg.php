<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('preg.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($agereg!=0){
		$query = "	SELECT A.AGEPREITM,A.AGEPREGUN,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,A.AGEPREEST
					FROM AGE_PREG A
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=A.PERCODIGO
					WHERE AGEREG=$agereg";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
			$agepreitm = trim($row['AGEPREITM']);
			$agepregun = trim($row['AGEPREGUN']);
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percompan = trim($row['PERCOMPAN']);
			$agepreest = trim($row['AGEPREEST']);
			
			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('agereg'		, $agereg		);
			$tmpl->setVariable('agepreitm'	, $agepreitm	);
			$tmpl->setVariable('agepregun'	, $agepregun	);
			$tmpl->setVariable('pernombre'	, $pernombre	);
			$tmpl->setVariable('perapelli'	, $perapelli	);
			$tmpl->setVariable('percompan'	, $percompan	);
			
			switch(trim($agepreest)){
				case 1:
					$tmpl->setVariable('agepreest'	, 'ACEPTADA' );
					break;
				case 2:
					$tmpl->setVariable('agepreest'	, 'REVOCADA' );
					break;
				case 0:
					$tmpl->setVariable('agepreest'	, 'PENDIENTE' );
					break;
			}
			
			
			$tmpl->parse('browser');
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
