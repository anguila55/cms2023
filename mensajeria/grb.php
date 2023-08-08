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

	$pathimagenes = '../mailsimg/';
	if (!file_exists($pathimagenes)) {
		mkdir($pathimagenes);
	}

	//Control de Datos
	$msgreg 		= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$msgtitulo 		= (isset($_POST['msgtitulo']))? trim($_POST['msgtitulo']) : '';
	$msgdescri 		= (isset($_POST['msgdescri']))? trim($_POST['msgdescri']) : '';
	$msgsub 		= (isset($_POST['msgsub']))? trim($_POST['msgsub']) : '';
	$msgbot 		= (isset($_POST['msgbot']))? trim($_POST['msgbot']) : '';
	$msglnk 		= (isset($_POST['msglnk']))? trim($_POST['msglnk']) : '';
	$msgrep 		= (isset($_POST['msgrep']))? trim($_POST['msgrep']) : '';
	$msgcc 		= (isset($_POST['msgcc']))? trim($_POST['msgcc']) : '';
	$msgcco 		= (isset($_POST['msgcco']))? trim($_POST['msgcco']) : '';
	$msgimgvacio 		= (isset($_POST['msgimgvacio']))? trim($_POST['msgimgvacio']) : '';
	$msgestadonot 		= (isset($_POST['msgestadonot']))? trim($_POST['msgestadonot']) : 0;
	$msgestado 		= 1;
	
	

	$msgper		= (isset($_POST['msgper']))? trim($_POST['msgper']) : '';
	$msgidioma		= (isset($_POST['msgidioma']))? trim($_POST['msgidioma']) : '';
	$msgsend		= (isset($_POST['msgsend']))? trim($_POST['msgsend']) : '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$msgreg		= VarNullBD($msgreg		, 'N');
	$msgtitulo		= VarNullBD($msgtitulo		, 'S');
	$msgdescri		= VarNullBD($msgdescri		, 'S');
	$msgsub		= VarNullBD($msgsub		, 'S');
	$msgbot		= VarNullBD($msgbot		, 'S');
	$msglnk		= VarNullBD($msglnk		, 'S');
	$msgestado		= VarNullBD($msgestado		, 'N');
	$msgper			= VarNullBD($msgper			, 'N');
	$msgidioma		= VarNullBD($msgidioma		, 'S');
	$msgsend		= VarNullBD($msgsend		, 'N');
	$msgrep		= VarNullBD($msgrep		, 'S');
	$msgcc		= VarNullBD($msgcc		, 'S');
	$msgcco		= VarNullBD($msgcco		, 'S');

	if($msgreg==''){
		$msgreg=0;
	}
	if($msgtitulo==''){
		$errcod=2;
		$errmsg='Falta el titulo';

	}
	if ($msgimgvacio!=''){
		if ($_FILES["msgimg"]["size"] > 2097152) 
		{
			$errcod =2;
			$errmsg = "La imagen es muy grande. Por favor, achiquela a menos de 2MB ";
			echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
			die;
		}

	}

	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	if ($msgestadonot !=0){
		$msgestado=$msgestadonot;
	}
	if($msgreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_MSG,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$msgreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO MSG_CABE(MSGREG,MSGFCHREG,MSGTITULO,MSGDESCRI,MSGESTADO,MSGPER,MSGIDIOMA,MSGSEND,MSGSUB,MSGBOT,MSGLNK,MSGREP,MSGCC,MSGCCO)
					VALUES($msgreg,CURRENT_TIMESTAMP,$msgtitulo,$msgdescri,$msgestado,$msgper,$msgidioma,$msgsend,$msgsub,$msgbot,$msglnk,$msgrep,$msgcc,$msgcco) ";
					
	}else{
		$query = " 	UPDATE MSG_CABE SET 
					MSGREG=$msgreg, MSGTITULO=$msgtitulo, MSGDESCRI=$msgdescri, MSGESTADO=$msgestado,
					MSGPER=$msgper,		  MSGIDIOMA=$msgidioma,
					MSGSEND=$msgsend, MSGSUB=$msgsub, MSGBOT=$msgbot, MSGLNK=$msglnk, MSGREP=$msgrep, MSGCC=$msgcc, MSGCCO=$msgcco
					WHERE MSGREG=$msgreg ";

	}
	$err = sql_execute($query,$conn,$trans);
	
	if (isset($_FILES['msgimg'])) {
		$ext 	= pathinfo($_FILES['msgimg']['name'], PATHINFO_EXTENSION);
		$name 	= "MSG_IMG" . date("H-i-s.") . $ext;
		

		if (!file_exists($pathimagenes . $msgreg)) {
			mkdir($pathimagenes . $msgreg);
		}
		if (file_exists($pathimagenes . $msgreg . '/' . $name)) {
			unlink($pathimagenes . $msgreg . '/' . $name);
		}
		
		//Limpio el directorio - - - - - - - - - - - - - - - - - 
		$dirint = dir($pathimagenes . $msgreg . '/');
		while (($archivo = $dirint->read()) !== false) {
			if (strpos($archivo, 'MSG_IMG') !== false) {
				unlink($pathimagenes . $msgreg . '/' . $archivo);
			}
		}
		$dirint->close();
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	
		move_uploaded_file($_FILES['msgimg']['tmp_name'], $pathimagenes . $msgreg . '/' . $name);
	
	
		$query = "	UPDATE MSG_CABE SET MSGIMG='$name' WHERE MSGREG=$msgreg ";
		$err = sql_execute($query, $conn, $trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
