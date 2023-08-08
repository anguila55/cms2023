<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	


		
	$pathimagenes = '../murimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);
	}
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	

	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

	//Control de Datos
	$murreg 		= (isset($_POST['murreg']))? trim($_POST['murreg']) : 0;
	$murtitulo 		= (isset($_POST['murtitulo']))? trim($_POST['murtitulo']) : '';
	//$murtag 		= (isset($_POST['murtag']))? trim($_POST['murtag']) : '';
	$murenlace 		= (isset($_POST['murenlace']))? trim($_POST['murenlace']) : '';
	$percodigo 		= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : '';
	$murdescri 		= (isset($_POST['murdescri']))? trim($_POST['murdescri']) : '';
	$estcodigo 		= 1;
	
	if($percodigo==''){
		$percodigo = $percodlog;
	}
	if ($_FILES["murimg"]["size"] > 2097152) 
	{
		$errcod =2;
		$errmsg = "La imagen es muy grande. Por favor, achiquela a menos de 2MB ";
		echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
		die;
	}
	//--------------------------------------------------------------------------------------------------------------
	$murtitulo		= VarNullBD($murtitulo		, 'S');
	$murdescri		= VarNullBD($murdescri		, 'S');
	//$murtag			= VarNullBD($murtag			, 'S');
	$murenlace		= VarNullBD($murenlace		, 'S');

	
	
	if($murreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_MUR,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$murreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		$ahora = date('H:i');
		$query = " 	INSERT INTO MUR_MAEST(MURREG,PERCODIGO,MURTITULO,MURTAG,MURENLACE,MURFCH,ESTCODIGO,MURDESCRI)
					VALUES($murreg,$percodigo,$murtitulo,'',$murenlace,CAST('$ahora' AS TIME),$estcodigo,$murdescri) ";

	}else{

		//Editar post
		 $ahora = date('H:i');
		 $query = " 	UPDATE MUR_MAEST SET 
		 			 PERCODIGO=$percodigo, MURTITULO=$murtitulo,MURTAG='', MURENLACE=$murenlace, MURFCH=CAST('$ahora' AS TIME),ESTCODIGO=$estcodigo,MURDESCRI=$murdescri
					WHERE MURREG=$murreg ";
		
	}
	$err = sql_execute($query,$conn,$trans);	
	
	$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+3
					WHERE PERCODIGO=$percodlog ";

		$err = sql_execute($query,$conn,$trans);
	if (isset($_FILES['murimg'])) {
		$ext 	= pathinfo($_FILES['murimg']['name'], PATHINFO_EXTENSION);
		$name 	= "MUR_IMG" . date("H-i-s.") . $ext;
		

		if (!file_exists($pathimagenes . $murreg)) {
			mkdir($pathimagenes . $murreg);
		}
		if (file_exists($pathimagenes . $murreg . '/' . $name)) {
			unlink($pathimagenes . $murreg . '/' . $name);
		}
		
		//Limpio el directorio - - - - - - - - - - - - - - - - - 
		$dirint = dir($pathimagenes . $murreg . '/');
		while (($archivo = $dirint->read()) !== false) {
			if (strpos($archivo, 'MUR_IMG') !== false) {
				unlink($pathimagenes . $murreg . '/' . $archivo);
			}
		}
		$dirint->close();
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	
		move_uploaded_file($_FILES['murimg']['tmp_name'], $pathimagenes . $murreg . '/' . $name);
	
	
		$query = "	UPDATE MUR_MAEST SET MURIMG='$name' WHERE MURREG=$murreg ";
		$err = sql_execute($query, $conn, $trans);
	}

	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Posteo realizado con éxito!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'No se pudo guardar correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>