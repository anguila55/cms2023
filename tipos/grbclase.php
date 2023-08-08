<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$perclase 		= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;
	$perclades 		= (isset($_POST['perclades']))? trim($_POST['perclades']) : '';
	$pertipo 		= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	$tiponuevo 		= (isset($_POST['tiponuevo']))? trim($_POST['tiponuevo']) : '';
	$perusacha 			= (isset($_POST['perusacha'])) ? trim($_POST['perusacha']) : '';
	$perusareu 			= (isset($_POST['perusareu'])) ? trim($_POST['perusareu']) : '';
	$perbloq 			= (isset($_POST['perbloq'])) ? trim($_POST['perbloq']) : '';

	$estcodigo 		= 1;
	
	if($perclase==''){
		$perclase=0;
	}
	if($perclades==''){
		$errcod=2;
		$errmsg='Falta el nombre de la clase';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$perclase		= VarNullBD($perclase		, 'N');
	$perclades		= VarNullBD($perclades		, 'S');
	$tiponuevo		= VarNullBD($tiponuevo		, 'S');
	$pertipo		= VarNullBD($pertipo		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$perusacha			= VarNullBD($perusacha, 'N');
	$perusareu			= VarNullBD($perusareu, 'N');
	$perbloq			= VarNullBD($perbloq, 'S');

	if ($pertipo == 0) {
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERTIPO,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$pertipo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		$query = " 	INSERT INTO PER_TIPO(PERTIPO,PERTIPDESESP,PERTIPDESING,ESTCODIGO)
						VALUES($pertipo,$tiponuevo,$tiponuevo,$estcodigo) ";

		
	}
	$err = sql_execute($query,$conn,$trans);	
	
	if($perclase==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_CLASE,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$perclase 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PER_CLASE(PERCLASE,PERCLADES,PERTIPO,ESTCODIGO,PERUSACHA,PERUSAREU,PERBLOQ)
					VALUES($perclase,$perclades,$pertipo,$estcodigo,$perusacha,$perusareu,$perbloq) ";
	}else{
		$query = " 	UPDATE PER_CLASE SET 
					PERCLASE=$perclase, PERCLADES=$perclades,PERTIPO=$pertipo, ESTCODIGO=$estcodigo,PERUSACHA=$perusacha,PERUSAREU=$perusareu,PERBLOQ=$perbloq
					WHERE PERCLASE=$perclase ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
