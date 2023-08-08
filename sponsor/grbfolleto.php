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
	$prodreg = (isset($_POST['prodreg']))? trim($_POST['prodreg']):0;
	$codigo = 200000; // (Desde 100000=Descarga de brochure + IntExpositor)
	$puntaje = 2;
	
	if($percodlog!=0 && $prodreg!=0){
		$codigo += $prodreg;
		
		//Verifico si existe la relacion 
		$existeRel=false;
		$query = "	SELECT PERQRITM
					FROM PER_QR  
					WHERE PERCODIGO=$percodlog AND PERQRPER=$codigo ";		
		$TableCtrl = sql_query($query,$conn);
		if($TableCtrl->Rows_Count>0){
			$existeRel=true;
		}
				
		if($existeRel==false){
			$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percodlog, GEN_ID(G_PERQRITEM,1), $codigo, 0, CURRENT_TIMESTAMP, $puntaje)";
			$err = sql_execute($query,$conn);
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
