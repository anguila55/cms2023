<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$mescodigo 		= (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : 0;
	$mesnumero 		= (isset($_POST['mesnumero']))? trim($_POST['mesnumero']) : '';
	$usuario 		= (isset($_POST['usuario']))? trim($_POST['usuario']) : '';
	$tipomesa 		= (isset($_POST['tipomesa']))? trim($_POST['tipomesa']) : '';

	
	if($mescodigo==''){
		$mescodigo=0;
	}
	if($mesnumero==''){
		$errcod=2;
		$errmsg='Complete el campo!';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$mescodigo		= VarNullBD($mescodigo		, 'N');
	$mesnumero		= VarNullBD($mesnumero		, 'S');
	$usuario		= VarNullBD($usuario		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');


	
	$querytenia = "SELECT MESCODIGO
	FROM MES_MAEST
	WHERE PERCODIGO=$usuario AND MESCODIGO<>$mescodigo ";

	$Tabletenia = sql_query($querytenia,$conn);
	

	if ($Tabletenia->Rows_Count>0)
	{
		$row = $Tabletenia->Rows[0];
		$mescodigo2 	= trim($row['MESCODIGO']);


		$querymesa = " 	UPDATE MES_MAEST SET 
		PERCODIGO=0
		WHERE MESCODIGO=$mescodigo2 ";

		$err = sql_execute($querymesa,$conn,$trans);
	}

	
	if($mescodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_MESAS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$mescodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO MES_MAEST(MESCODIGO,MESNUMERO,PERCODIGO,ESTCODIGO)
					VALUES($mescodigo,$mesnumero,$usuario,$tipomesa) ";
		$err = sql_execute($query,$conn,$trans);	

		if ($tipomesa == 1){

			/// BUSCAR LAS REUNIONES QUE TIENE EL USUARIO EN MESAS FLOTANTES
			$query = "	SELECT REUREG
			FROM REU_CABE RC
			LEFT OUTER JOIN MES_MAEST MM ON MM.MESCODIGO = RC.MESCODIGO 
			WHERE (PERCODDST=$usuario OR PERCODSOL=$usuario) AND (PERCODDST!=PERCODSOL) AND MM.ESTCODIGO=2";
			$TableReunionesMesa = sql_query($query,$conn);

			for ($i = 0; $i < $TableReunionesMesa->Rows_Count; $i++) {
				$rowmesa = $TableReunionesMesa->Rows[$i];
				$reureg = trim($rowmesa['REUREG']);

				// ACTUALIZO REUCABE
				$query = "UPDATE REU_CABE SET MESCODIGO=$mescodigo WHERE REUREG=$reureg ";
				$err = sql_execute($query,$conn,$trans);

				// DELETEO MES_DISP
				$query=" DELETE FROM MES_DISP WHERE REUREG=$reureg ";
				$err = sql_execute($query,$conn,$trans);

			}

		}




	}else{
		$query = " 	UPDATE MES_MAEST SET 
					MESNUMERO=$mesnumero
					WHERE MESCODIGO=$mescodigo ";
					$err = sql_execute($query,$conn,$trans);	
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al modificar las mesas flotantes asignadas!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
