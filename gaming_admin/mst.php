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
	$tipo = (isset($_POST['tipo']))? trim($_POST['tipo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	

	//logerror("MST:".$iditraitm);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	
	if($tipo!=0){
		$query = "	SELECT PTS, TIPO, MODELO, NOMBRE
		FROM GAME_TABLE WHERE TIPO = $tipo";

		//logerror($query);
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			
			$tipo 	= trim($row['TIPO']);
			$pts 	= trim($row['PTS']);
			$modelo 	=  trim($row['MODELO']);
			$nombre 	= trim($row['NOMBRE']);
			
			
			$tmpl->setVariable('tipo'	, $tipo);
			$tmpl->setVariable('pts'	, $pts);
			$tmpl->setVariable('modelo1'	, ''	);
			$tmpl->setVariable('modelo2'	, ''	);
			if ($modelo == 'recurrente'){
				$tmpl->setVariable('modelo1'	, 'selected'	);
			}else{
				$tmpl->setVariable('modelo2'	, 'selected'	);
			}
			$tmpl->setVariable('modelo'	, $modelo);
			$tmpl->setVariable('nombre'	, $nombre);
			
			
		}



		
		
	}else{
		$paicodigo = '';
	}

	// $queryidioma = "SELECT * FROM IDI_MAEST";
	// $Tableidioma = sql_query($queryidioma,$conn);
	// for($i=0; $i<$Tableidioma->Rows_Count; $i++){
		
	// 	$row = $Tableidioma->Rows[$i];
	// 	$idicodigo 	= trim($row['IDICODIGO']);
	// 	$ididescri 	= trim($row['IDIDESCRI']);
		
	// 	$tmpl->setCurrentBlock('idioma');
	// 	$tmpl->setVariable('idicodigo',$idicodigo);

	// 	$tmpl->setVariable('ididescri',$ididescri);

	// 	if($idicodigo == $idicodigomst){
	// 		$tmpl->setVariable('selected', 'selected');
	// 	}

	// 	$tmpl->parse('idioma');
	//}
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
