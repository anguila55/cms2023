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
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$murreg = (isset($_POST['murreg']))? trim($_POST['murreg']) : 0;
	$comentario = (isset($_POST['comentario']))? trim($_POST['comentario']) : 0;
	
	//--------------------------------------------------------------------------------------------------------------

	$percodigo			= VarNullBD($percodigo		, 'N');
	$murreg				= VarNullBD($murreg			, 'N');
	$comentario			= VarNullBD($comentario		, 'S');
	

	if ($percodigo != 0) {

		$query 		= 'SELECT GEN_ID(G_MUR_COMENT,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$comreg 	= trim($RowId['ID']);

		$query = " 	INSERT INTO MUR_COMENT (COMREG,PERCODIGO,MURREG,COMDESCRI)
					VALUES ($comreg,$percodigo,$murreg,$comentario)";
		$err = sql_execute($query,$conn);
		$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+4
					WHERE PERCODIGO=$percodigo ";

			$err = sql_execute($query,$conn,$trans);
			
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>