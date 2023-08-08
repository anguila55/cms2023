<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$tipo = trim($_POST['tipo']);
	
	    //Seleccionamos los perfiles
		
	$jsonData = '[';
	
	$where='';

	if ($tipo==999999){

	}else{
		$where.= "AND ESTCODIGO=$tipo";
	}
	//Cargo los Subsectores segun los sectores seleccionados
	$querymesas = "	SELECT MESCODIGO,MESNUMERO
	FROM MES_MAEST 
	WHERE ESTCODIGO<>3 $where
	";
	$Tablemesas = sql_query($querymesas,$conn);
	for($j=0; $j<$Tablemesas->Rows_Count; $j++){
		
		$rowmesas= $Tablemesas->Rows[$j];
		$mescodigo 		= trim($rowmesas['MESCODIGO']);
		$mesnumero		= trim($rowmesas['MESNUMERO']);
		


		
		$jsonData .= '{	"mescodigo":"'.$mescodigo.'",
						"mesnumero":"'.$mesnumero.'"},';
		
	}
	
	if($Tablemesas->Rows_Count>0){
		$jsonData = substr($jsonData,0,strlen($jsonData)-1);
	}
	$jsonData .= ']';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	
	echo $jsonData;
?>	
