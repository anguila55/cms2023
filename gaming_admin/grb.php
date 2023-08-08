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
	$tipo 		= (isset($_POST['tipo']))? trim($_POST['tipo']) : 0;
	$nombre 		= (isset($_POST['nombre']))? trim($_POST['nombre']) : '';
	$modelo 		= (isset($_POST['modelo']))? trim($_POST['modelo']) : '';
	$pts 		= (isset($_POST['pts']))? trim($_POST['pts']) : 0;

	
	
	//$paidescri = utf8_code($paidescri); 

	//--------------------------------------------------------------------------------------------------------------
	$tipo 		= VarNullBD($tipo		, 'N');
	$nombre		= VarNullBD($nombre		, 'S');
	$modelo			= VarNullBD($modelo			, 'S');
	$pts		= VarNullBD($pts		, 'N');

	
	

		$query = " 	UPDATE GAME_TABLE SET 
					MODELO=$modelo, PTS=$pts
					WHERE TIPO=$tipo ";
	
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
