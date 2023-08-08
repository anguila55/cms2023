<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$networkreg 		= (isset($_POST['networkreg']))? trim($_POST['networkreg']) : 0;
	$networktitulo 		= (isset($_POST['networktitulo']))? trim($_POST['networktitulo']) : '';
	$networkfch 		= (isset($_POST['networkfch']))? trim($_POST['networkfch']) : '';
	$networkhorini 		= (isset($_POST['networkhorini']))? trim($_POST['networkhorini']) : 0;
	$networkhorfin		= (isset($_POST['networkhorfin']))? trim($_POST['networkhorfin']) : 0;
	$networkbbb		= (isset($_POST['networkbbb']))? trim($_POST['networkbbb']) : '';
	$switch		= (isset($_POST['switch']))? trim($_POST['switch']) : 0;

	
	$estcodigo 		= 1;
	//$fecha= BDConvFch($row['FECHA']);
	if($networkreg==''){
		$networkreg=0;
	}
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}

	

	//--------------------------------------------------------------------------------------------------------------
	$networkreg			= VarNullBD($networkreg			, 'N');
	$networktitulo		= VarNullBD($networktitulo		, 'S');
	$networkhorini		= VarNullBD($networkhorini		, 'S');
	$networkhorfin		= VarNullBD($networkhorfin		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$networkbbb		= VarNullBD($networkbbb		, 'S');
	$switch		= VarNullBD($switch		, 'N');


	//fecha 2019-03-24 -> mm/dd/yyyy BD 
	$networkfch = substr($networkfch,5,2).'/'.substr($networkfch,8,2).'/'.substr($networkfch,0,4); 
	if($networkreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_AGENDA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$networkreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO NETWORK_MAEST (NETWORK_REG, NETWORK_TITULO, NETWORK_FCH, NETWORK_HORINI, NETWORK_HORFIN, ESTCODIGO, NETWORK_BBB, SWITCH)
					VALUES  ($networkreg, $networktitulo, '$networkfch', $networkhorini, $networkhorfin,$estcodigo, $networkbbb, $switch ) ";
					
					
	}else{
		$query = " UPDATE NETWORK_MAEST SET 
					 NETWORK_TITULO=$networktitulo, NETWORK_FCH='$networkfch', NETWORK_HORINI=$networkhorini, NETWORK_HORFIN=$networkhorfin, 
					 ESTCODIGO=$estcodigo, NETWORK_BBB=$networkbbb, SWITCH=$switch
					WHERE NETWORK_REG=$networkreg ";
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
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
		
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
