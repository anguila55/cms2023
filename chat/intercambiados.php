<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	$pertipo 	= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 	= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
	//--------------------------------------------------------------------------------------------------------------		
	
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percoddst = (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0;
	$mensaje 	= (isset($_POST['mensaje']))? trim($_POST['mensaje']) : '';

	//--------------------------------------------------------------------------------------------------------------
		
		
		//Verifico si existe la relacion
		$existeRel=false;
		$query = "	SELECT PERQRITM
					FROM PER_QR  
					WHERE ((PERCODIGO=$percoddst AND PERQRPER=$percodigo) OR (PERCODIGO=$percodigo AND PERQRPER=$percoddst)) ";		
		$TableCtrl = sql_query($query,$conn);
		if($TableCtrl->Rows_Count>0){
			$existeRel=true;
		}
				
		if($existeRel==false){
			$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percoddst, GEN_ID(G_PERQRITEM,1), $percodigo, 0, CURRENT_TIMESTAMP, 0)";
			$err = sql_execute($query,$conn);

			$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
					VALUES ($percodigo, GEN_ID(G_PERQRITEM,1), $percoddst, 0, CURRENT_TIMESTAMP, 0)";
			$err = sql_execute($query,$conn);

			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+5
					WHERE PERCODIGO=$percodigo ";

			$err = sql_execute($query,$conn,$trans);
			
			//$query = "	INSERT INTO PER_QR (PERCODIGO, PERQRITM, PERQRPER, PERQRAGE, PERQRFCH, PERQRPUN) 
			//		VALUES ($percodigo, GEN_ID(G_PERQRITEM,1), $percoddst, 0, CURRENT_TIMESTAMP, 0)";
			//$err = sql_execute($query,$conn);
		}
		$query = "	INSERT INTO TBL_CHAT (CHAREG, CHAFCHREG, PERCODIGO, PERCODDST, CHATEXTO, ESTCODIGO, CHALEIDO) 
					VALUES (GEN_ID(G_CHATS,1), CURRENT_TIMESTAMP, $percodigo, $percoddst, '$mensaje', 1, 0);";
		$err = sql_execute($query,$conn);

		sql_close($conn);	
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
