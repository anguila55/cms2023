<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$pertipo = trim($_POST['pertipo']);
	
	    //Seleccionamos los perfiles
		$query = "	SELECT TIPO
		FROM PER_MAEST 
		WHERE ESTCODIGO=1 AND PERCODIGO=$pertipo ";
		$Table = sql_query($query, $conn);

		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		
		$tiporeunion	= trim($row['TIPO']);
	
		}
	$where='';
	if ($tiporeunion==2){

		$where .= " ";
		
	}else{

		$where .= " AND (TIPO=$tiporeunion OR TIPO=2)  ";
	}
		
	$jsonData = '[';
	
	//Cargo los Subsectores segun los sectores seleccionados
	$queryperfiles = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,TIPO,PERCOMPAN
	FROM PER_MAEST 
	WHERE ESTCODIGO=1 AND PERCODIGO!=$pertipo $where
	ORDER BY UPPER(PERCOMPAN) ";
	$Tableperfiles = sql_query($queryperfiles,$conn);

	for($j=0; $j<$Tableperfiles->Rows_Count; $j++){
		
		$rowperfiles= $Tableperfiles->Rows[$j];
		$percod 		= trim($rowperfiles['PERCODIGO']);
		$pernombre		= trim($rowperfiles['PERNOMBRE']);
		$perapelli		= trim($rowperfiles['PERAPELLI']);
		$tiporeunion	= trim($rowperfiles['TIPO']);
		$percompan	= trim($rowperfiles['PERCOMPAN']);


		
		$jsonData .= '{	"perclase":"'.$percod.'",
						"perclades":"'.$percompan.' - '.$pernombre.' '.$perapelli.'"},';
		
	}
	if($Table->Rows_Count>0){
		$jsonData = substr($jsonData,0,strlen($jsonData)-1);
	}
	$jsonData .= ']';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	
	echo $jsonData;
?>	
