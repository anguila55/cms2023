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
	$networkreg = (isset($_POST['networkreg']))? trim($_POST['networkreg']) : 0;
	
	$estcodigo 	= 1; //Activo por defecto
	$networktitulo 	= '';

		
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($networkreg!=0){
		$query = "SELECT NETWORK_REG, NETWORK_TITULO, NETWORK_FCH, NETWORK_HORINI, NETWORK_HORFIN, 
						ESTCODIGO, NETWORK_BBB, SWITCH
				  FROM NETWORK_MAEST
				  WHERE NETWORK_REG=$networkreg " ;
		
		$Table = sql_query($query,$conn);		
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$networkreg 	= trim($row['NETWORK_REG']);
			$networktitulo 	= trim($row['NETWORK_TITULO']);
			$networkfch 	= BDConvFch($row['NETWORK_FCH']);
			$networkhorini 	= trim($row['NETWORK_HORINI']);
			$networkhorfin 	= trim($row['NETWORK_HORFIN']);
			$estcodigo 	= trim($row['ESTCODIGO']);
			$networkbbb	= trim($row['NETWORK_BBB']);
			$switch	= trim($row['SWITCH']);

			
			$networkfch	= substr($networkfch,6,4).'-'.substr($networkfch,3,2).'-'.substr($networkfch,0,2);
			
			$tmpl->setVariable('networkreg'		, $networkreg		);
			$tmpl->setVariable('networktitulo'	, $networktitulo	);
			$tmpl->setVariable('networkfch'		, $networkfch		);
			$tmpl->setVariable('networkhorini'	, $networkhorini	);
			$tmpl->setVariable('networkhorfin'	, $networkhorfin	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			$tmpl->setVariable('networkbbb'	, $networkbbb	);
			$tmpl->setVariable('checklive'	, $switch	);
			if  ($switch ==1 ){

				$tmpl->setVariable('checklivenombre'	, 'LIVE'	);
				
			}else if($switch ==2 ){

				$tmpl->setVariable('checklivenombre'	, 'FULL'	);

			}else{

				$tmpl->setVariable('checklivenombre'	, 'SOON'	);
			}
			
			

		}
	}
	//--------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
