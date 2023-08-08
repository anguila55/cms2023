<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC . '/sigma.php';
	require_once GLBRutaFUNC . '/zdatabase.php';
	require_once GLBRutaFUNC . '/zfvarias.php';
	require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion
	$trans	= sql_begin_trans($conn);   
    
	/* ------------------------- SECTION  CONTROL DE DATOS ------------------------ */
    
	$dataVisibilidad	= (isset($_POST['dataVisibilidad']))? trim($_POST['dataVisibilidad']) : '';
	$dataNombreEsp	= (isset($_POST['dataNombreEsp']))? trim($_POST['dataNombreEsp']) : '';
	$dataNombreIng	= (isset($_POST['dataNombreIng']))? trim($_POST['dataNombreIng']) : '';
	$dataNombrePor	= (isset($_POST['dataNombrePor']))? trim($_POST['dataNombrePor']) : '';

    
    if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataVisibilidad!=''){

			
			
			$dataVisibilidad = json_decode($dataVisibilidad);
			foreach($dataVisibilidad as $ind => $data){
				$perm 	= $data->perm;
				$tipori 	= $data->tipori;

				
				$pertipo			= VarNullBD($tipori        , 'S');
				$pertipoperm		= VarNullBD($perm    , 'N');
				


				$query = " 	UPDATE PAR_MAEST SET 
								PARVALOR=$pertipo
								WHERE PARREG=$pertipoperm";
				
				$err = sql_execute($query, $conn, $trans);

				
				
			}
		}
	}
	if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataNombreEsp!=''){

			
			
			$dataNombreEsp = json_decode($dataNombreEsp);
			foreach($dataNombreEsp as $ind => $data){
				$perm 	= $data->perm;
				$tipori 	= $data->tipori;

				
				$pertipo			= VarNullBD($tipori        , 'S');
				$pertipoperm		= VarNullBD($perm    , 'N');
				


				$query = " 	UPDATE PAR_MAEST SET 
								PARNOMESP=$pertipo
								WHERE PARREG=$pertipoperm";
				
				$err = sql_execute($query, $conn, $trans);

				
				
			}
		}
	}
	if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataNombreIng!=''){

			
			
			$dataNombreIng = json_decode($dataNombreIng);
			foreach($dataNombreIng as $ind => $data){
				$perm 	= $data->perm;
				$tipori 	= $data->tipori;

				
				$pertipo			= VarNullBD($tipori        , 'S');
				$pertipoperm		= VarNullBD($perm    , 'N');
				


				$query = " 	UPDATE PAR_MAEST SET 
								PARNOMING=$pertipo
								WHERE PARREG=$pertipoperm";
				
				$err = sql_execute($query, $conn, $trans);

				
				
			}
		}
	}
	if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataNombrePor!=''){

			
			
			$dataNombrePor = json_decode($dataNombrePor);
			foreach($dataNombrePor as $ind => $data){
				$perm 	= $data->perm;
				$tipori 	= $data->tipori;

				
				$pertipo			= VarNullBD($tipori        , 'S');
				$pertipoperm		= VarNullBD($perm    , 'N');
				


				$query = " 	UPDATE PAR_MAEST SET 
								PARNOMPOR=$pertipo
								WHERE PARREG=$pertipoperm";
				
				$err = sql_execute($query, $conn, $trans);

				
				
			}
		}
	}
	
 
	


	if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? TrMessage('Guardado correctamente!') : $errmsg;
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
