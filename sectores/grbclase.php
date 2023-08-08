<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$subsect 		= (isset($_POST['subsect']))? trim($_POST['subsect']) : 0;
	$subsectdes 		= (isset($_POST['subsectdes']))? trim($_POST['subsectdes']) : '';
	$subsectdesing 		= (isset($_POST['subsectdesing']))? trim($_POST['subsectdesing']) : '';
	$sector 		= (isset($_POST['sector']))? trim($_POST['sector']) : '';
	$estcodigo 		= 1;
	
	if($subsect==''){
		$subsect=0;
	}
	if($subsectdes==''){
		$errcod=2;
		$errmsg='Falta el nombre del Subsector';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$subsect		= VarNullBD($subsect		, 'N');
	$subsectdes		= VarNullBD($subsectdes		, 'S');
	$subsectdesing		= VarNullBD($subsectdesing		, 'S');
	$sector		= VarNullBD($sector		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	
	if($subsect==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_SUBSECTOR,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$subsect 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO SEC_SUB(SECSUBCOD,SECSUBDES,SECSUBDESING,ESTCODIGO,SECCODIGO)
					VALUES($subsect,$subsectdes,$subsectdesing,$estcodigo,$sector) ";
	}else{
		$query = " 	UPDATE SEC_SUB SET 
					SECSUBDES=$subsectdes, SECSUBDESING=$subsectdesing, SECCODIGO=$sector
					WHERE SECSUBCOD=$subsect";  
	}
	$err = sql_execute($query,$conn,$trans);	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
