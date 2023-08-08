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

	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$peradmin 	= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$pertipo 	= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	//--------------------------------------------------------------------------------------------------------------
	$percoddst = (isset($_POST['percoddst']))? trim($_POST['percoddst']):0;


	$nombrecontroler=$pernombre.' '.$perapelli;
	
	if( ($pertipo==71 && $percoddst!=0) || ($peradmin == 1 && $percoddst!=0) ){
		
		//Verifico si existe la relacion 
		
	

			$query = "	INSERT INTO CONT_INGRESO (PERCODIGO, CONT_REG, CONTFCH,PERCONTROL) 
					VALUES ($percoddst, GEN_ID(G_CONTREG,1), CURRENT_TIMESTAMP,'$nombrecontroler')";
			$err = sql_execute($query,$conn);
			

		
	}
	//--------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
