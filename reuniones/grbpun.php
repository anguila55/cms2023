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
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']):0;
	$tipo=25; ///////Son todas los que apretan conectado


	/////////////// Definicion de los tipos///////
	/*

	tipo=1 es visita al stand
	tipo=2 es click banner
	tipo=3 es click linkedin
	tipo=4 es click twitter
	tipo=5 es sala vip de reuniones
	tipo=6 es click a la web del expositor
	tipo=7 es brouchure principal
	tipo=8 es folletos
	tipo=9 videos en a la carta
	tipo=10 documentos a la carta
	tipo=11 videos en vivo sala
	tipo=12 videos diferidos en sala

	*/

	///////// LE DOY VALOR A CADA ACCION///////
	$puntos = 0;
	
	
	
	if($percodlog!=0 && $reureg!=0){
		$codigo = $reureg;
		
		//Verifico si existe la relacion 
		
		$query = "	SELECT PERQRITM
					FROM PER_QR  
					WHERE PERCODIGO=$percodlog AND PERQRPER=$codigo ";		
		$TableCtrl = sql_query($query,$conn);
		

			$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percodlog, GEN_ID(G_PERQRITEM,1), $codigo, $tipo, CURRENT_TIMESTAMP, $puntos)";
			$err = sql_execute($query,$conn);
		
	}
	//--------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
