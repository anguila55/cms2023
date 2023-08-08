<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	require_once GLBRutaFUNC.'/sendiosmessage.php';
	require_once GLBRutaFUNC.'/constants.php';
	//--------------------------------------------------------------------------------------------------------------
	$errcod 		= 0;
	$errmsg			= '';	
	$err			= 'SQLACCEPT';	
	//--------------------------------------------------------------------------------------------------------------
	//Control de Datos
	$msgreg 	= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;


	$pertipo = VarNullBD($pertipo , 'N');
	$perclase = VarNullBD($perclase , 'N');

	//--------------------------------------------------------------------------------------------------------------	
	$conn		= sql_conectar();//Apertura de Conexion
	$trans		= sql_begin_trans($conn);
	//--------------------------------------------------------------------------------------------------------------	
	if ($msgreg!=0) {
		$query="SELECT MSGTITULO, MSGESTADO, MSGDESCRI
				FROM MSG_CABE 
				WHERE MSGREG=$msgreg";
		$Table = sql_query($query,$conn,$trans);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgestado 	= trim($row['MSGESTADO']);
		$msgdescri 	= trim($row['MSGDESCRI']);

		if ($msgestado == 100){

			$msgtitulo= $msgtitulo.'##'.$msgdescri;
		}
		if ($perclase!=0){
			$percodsol=181778;
			$reureg = $perclase;
		}else{
			$percodsol=181777;
			$reureg = $pertipo;
		}
	
		$percodigo = $percodigo_controlingresos;
	

	// FUERZO NOTIFICACION
		//Inserto Notificacion de Aceptacion
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgtitulo', NULL, $percodsol, 1, $percodigo, $reureg, $msgestado)";
		$err = sql_execute($query,$conn,$trans);
		
		
	
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Su notificacion fue enviada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al enviar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';

	
?>
