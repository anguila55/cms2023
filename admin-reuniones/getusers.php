<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$perclase = trim($_POST['perclase']);
	
	    //Seleccionamos los perfiles
		
	$jsonData = '[';
	
	$where='';

	if ($perclase==999999){

	}else{
		$where.= "AND PERCLASE=$perclase";
	}
	//Cargo los Subsectores segun los sectores seleccionados
	$queryperfiles = "	SELECT PERNOMBRE,PERAPELLI,PERCODIGO,PERCOMPAN
	FROM PER_MAEST 
	WHERE ESTCODIGO=1 $where
	ORDER BY UPPER(PERCOMPAN) ";
	$Tableperfiles = sql_query($queryperfiles,$conn);
	for($j=0; $j<$Tableperfiles->Rows_Count; $j++){
		
		$rowperfiles= $Tableperfiles->Rows[$j];
		$percod 		= trim($rowperfiles['PERCODIGO']);
		$pernombre		= trim($rowperfiles['PERNOMBRE']);
		$perapelli		= trim($rowperfiles['PERAPELLI']);
		$percompan	= trim($rowperfiles['PERCOMPAN']);


		
		$jsonData .= '{	"perclase":"'.$percod.'",
						"perclades":"'.$percompan.' ( '.$pernombre.' '.$perapelli.' ) "},';
		
	}
	
	if($Tableperfiles->Rows_Count>0){
		$jsonData = substr($jsonData,0,strlen($jsonData)-1);
	}
	$jsonData .= ']';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	
	echo $jsonData;
?>	
