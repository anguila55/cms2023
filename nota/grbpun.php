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
	
	//Control de Datos
	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	$notahome = (isset($_POST['notahome']))? trim($_POST['notahome']):0;
	$codigo = 900000; // (Desde 100000=Descarga de brochure + IntExpositor)
	$puntaje = 5;
	
	if($percodlog!=0 && $notahome!=0){
		$codigo += $notahome;
		
		//Verifico si existe la relacion 
		
		$query = "	SELECT PERQRITM
					FROM PER_QR  
					WHERE PERCODIGO=$percodlog AND PERQRPER=$codigo ";		
		$TableCtrl = sql_query($query,$conn);
		
			$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percodlog, GEN_ID(G_PERQRITEM,1), $codigo, 20, CURRENT_TIMESTAMP, $puntaje)";
			$err = sql_execute($query,$conn);
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
