<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	$expcupreg = (isset($_POST['expcupreg']))? trim($_POST['expcupreg']):0;
	

	/////////////// Definicion de los tipos///////
	/*

	tipo=18 es whatsapp sponsor
	tipo=19 es mail sponsor
	tipo=20 es sponsor por ofertas
	


	*/

	///////// LE DOY VALOR A CADA ACCION///////
	

	if($percodlog!=0 && $expcupreg!=0){

		
		$query=" DELETE FROM CUP_PER WHERE PERCODIGO=$percodlog AND EXPCUPREG=$expcupreg ";
			
		$err = sql_execute($query,$conn);
		
	}

	if($err == 'SQLACCEPT'){
		sql_commit_trans($trans);
	}else{            
		sql_rollback_trans($trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	