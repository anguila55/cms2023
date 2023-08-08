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

	$estcodigo 		= 1;
    
    
    if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataVisibilidad!=''){

			
			
			$dataVisibilidad = json_decode($dataVisibilidad);
			foreach($dataVisibilidad as $ind => $data){
				$perm 	= $data->perm;
				$tipori 	= $data->tipori;
				$claori 	= $data->claori;
				$tipdst 	= $data->tipdst;
				$cladst 	= $data->cladst;
				$bool 	= $data->bool;
				
				$pertipo			= VarNullBD($tipori        , 'N');
				$pertipdest     	= VarNullBD($tipdst     , 'N');
				$clase		        = VarNullBD($cladst          , 'N');
				$pertipoperm		= VarNullBD($perm    , 'N');
				$codclase			= VarNullBD($claori   	, 'N');

				if ($bool == 1){

					if ($pertipoperm == 0) {
						//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
						//Genero un ID 
						$query 		= 'SELECT GEN_ID(G_PERTIPPERM,1) AS ID FROM RDB$DATABASE';
						$TblId		= sql_query($query, $conn, $trans);
						$RowId		= $TblId->Rows[0];
						$pertipoperm 	= trim($RowId['ID']);
						//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
						$query = " 	INSERT INTO PER_TIPO_PERM(PERTIPOPERM,PERTIPO,PERTIPCLA,PERTIPDST,PERCLADST)
										VALUES($pertipoperm,$pertipo,$codclase,$pertipdest,$clase) ";
				
						
					 } else {
						$query = " 	UPDATE PER_TIPO_PERM SET 
										PERTIPO=$pertipo,PERTIPCLA=$codclase, PERTIPDST=$pertipdest,PERCLADST=$clase
										WHERE PERTIPOPERM=$pertipoperm";
					 }
					 $err = sql_execute($query, $conn, $trans);

				}else{

					if($pertipoperm!=0){
						//Elimino el registro
						$query = "DELETE FROM PER_TIPO_PERM WHERE PERTIPOPERM = $pertipoperm ";
						$err = sql_execute($query,$conn,$trans);
					}


				}

				
				
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
