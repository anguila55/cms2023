<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$mapreg = (isset($_POST['mapreg']))? trim($_POST['mapreg']) : 0;
	$estcodigo 	= 1; //Activo por defecto
	$expcode='';
	$expcode2=0;

		
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($mapreg!=0){
		$query = "SELECT *
		FROM MAP_TABLE
				  WHERE MAPREG=$mapreg " ;
		
		$Table = sql_query($query,$conn);		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
	
			$mapreg 	= trim($row['MAPREG']);
			$link 	= trim($row['LINK']);
			$coord 	= trim($row['COORD']);
			$expcode 	= trim($row['EXPREG']);

			
			$tmpl->setVariable('mapreg'		, $mapreg		);
			$tmpl->setVariable('link'		, $link		);
			$tmpl->setVariable('coord'	, $coord	);
			$tmpl->setVariable('expcode'	, $expcode		);


		}
	}


	//--------------------------------------------------------------------------------------------------------------
	$query="SELECT EXPREG, EXPNOMBRE FROM EXP_MAEST WHERE ESTCODIGO=1 ORDER BY EXPNOMBRE";
		$execute =sql_query($query,$conn);
		
		for($i=0; $i<$execute->Rows_Count; $i++){
		

			$row = $execute->Rows[$i];
			$expreg = trim($row['EXPREG']);
			$exptitulo = trim($row['EXPNOMBRE']);			
			$estcodigo  = trim($row['ESTCODIGO']);

			$tmpl->setCurrentBlock('expositores');
				$tmpl->setVariable('expregcod'		, $expreg		);
				$tmpl->setVariable('expnombre'	, $exptitulo	);
				$tmpl->setVariable('estcodigo'	, $estcodigo	);
		
					
						if($expcode == $expreg){
							 
							$tmpl->setVariable('expregsel'	, 'selected' 	);
						}
			   
			
			$tmpl->parse('expositores');
		

		}
		
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
