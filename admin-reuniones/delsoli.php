<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$pertipo = VarNullBD($reureg , 'N');
	
	if($reureg!=0){
		//Elimino el registro
		$query = " 	UPDATE REU_SOLI SET REUESTADO=3 WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);

		$query = " 	UPDATE REU_CABE SET REUESTADO=3,REUFCHCAN=CURRENT_TIMESTAMP,MESCODIGO=0 WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);


		// VER SI TENIA MESA FLOTANTE
		// BUSCO LA MESA FLOTANTE 
		$querymesa = " 	SELECT MESCODIGO 
		FROM MES_DISP 
		WHERE REUREG=$reureg";

		$TableMesa = sql_query($querymesa,$conn);
		if($TableMesa->Rows_Count>0){
			
		$query=" DELETE FROM MES_DISP WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);
		}

	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Reunión eliminada!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al eliminar la reunión!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
