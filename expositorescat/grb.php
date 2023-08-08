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
	$catreg 		= (isset($_POST['catreg']))? trim($_POST['catreg']) : 0;
	$catdescri 		= (isset($_POST['catdescri']))? trim($_POST['catdescri']) : '';
	$catvalor 		= (isset($_POST['catvalor']))? trim($_POST['catvalor']) : 0;
	$catvalormax 	= (isset($_POST['catvalormax']))? trim($_POST['catvalormax']) : 0;
	$catimg 		= (isset($_POST['catimg']))? trim($_POST['catimg']) : 0;
	$catvid 		= (isset($_POST['catvid']))? trim($_POST['catvid']) : 0;
	$cattxt 		= (isset($_POST['cattxt']))? trim($_POST['cattxt']) : 0;
	$catvis 		= (isset($_POST['catvis']))? trim($_POST['catvis']) : 0;
	$catprod 		= (isset($_POST['catprod']))? trim($_POST['catprod']) : 0;
	$catper 		= (isset($_POST['catper']))? trim($_POST['catper']) : 0;
	
	

	//--------------------------------------------------------------------------------------------------------------
	$catreg			= VarNullBD($catreg			, 'N');
	$catdescri		= VarNullBD($catdescri		, 'S');
	$catvalor		= VarNullBD($catvalor		, 'N');
	$catvalormax	= VarNullBD($catvalormax	, 'N');
	$catimg			= VarNullBD($catimg			, 'N');
	$catvid			= VarNullBD($catvid			, 'N');
	$cattxt			= VarNullBD($cattxt			, 'N');
	$catvis			= VarNullBD($catvis			, 'N');
	$catprod		= VarNullBD($catprod		, 'N');
	$catper			= VarNullBD($catper		, 'N');

	
	if($catreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_EXPCAT,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$catreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO EXP_CAT(CATREG,CATDESCRI,CATVALOR,CATVALORMAX,CATIMG,CATVID,CATTXT,CATVIS,CATPROD,CATPER)
					VALUES($catreg,$catdescri,$catvalor,$catvalormax,$catimg,$catvid,$cattxt,$catvis,$catprod,$catper) ";
	}else{
		$query = " 	UPDATE EXP_CAT SET 
					CATREG=$catreg, CATDESCRI=$catdescri,CATVALOR=$catvalor,CATVALORMAX=$catvalormax,CATIMG=$catimg,CATVID=$catvid,CATTXT=$cattxt,CATVIS=$catvis,CATPROD=$catprod,CATPER=$catper
					WHERE CATREG=$catreg ";
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
